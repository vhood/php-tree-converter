<?php

namespace Vhood\TreeType;

use Vhood\TreeType\Contract\TypeConverter;
use Vhood\TreeType\Exception\InvalidStructureException;

class MaterializedPath implements TypeConverter
{
    private $data;
    private $pathKey;
    private $pathSeparator;

    public function __construct(array $flatTree, $pathKey = 'path', $pathSeparator = '/')
    {
        foreach ($flatTree as $index => $node) {
            if (!array_key_exists($pathKey, $node)) {
                throw new InvalidStructureException("Element $index has no pathField");
            }

            if (empty(array_filter(explode($pathSeparator, $node[$pathKey])))) {
                throw new InvalidStructureException("Element $index has empty pathField");
            }
        }

        $this->pathKey = $pathKey;
        $this->pathSeparator = $pathSeparator;

        $this->data = array_values($flatTree);
    }

    public function toMaterializedPath()
    {
        return $this->data;
    }

    public function toTree($childrenKey = 'children')
    {
        $fnBuildTree = function ($nodes, $parentNode = null) use (&$fnBuildTree, $childrenKey) {
            $tree = [];

            foreach ($nodes as $node) {
                if ($parentNode && $node[$this->pathKey] === $parentNode[$this->pathKey]) {
                    continue;
                }

                $isRoot = !$parentNode
                    && count(array_filter(explode($this->pathSeparator, $node[$this->pathKey]))) < 2;

                $parentPath = preg_replace(
                    sprintf("/(.+)%s[^$]/m", preg_quote($this->pathSeparator, '/')),
                    "$1",
                    $node[$this->pathKey]
                );

                $isRequestedChild = $parentNode
                    && !$isRoot
                    && $parentPath === $parentNode[$this->pathKey];

                if ($isRoot || $isRequestedChild) {
                    $node[$childrenKey] = $fnBuildTree($nodes, $node);
                    unset($node[$this->pathKey]);
                    $tree[] = $node;
                }
            }

            return $tree;
        };

        return $fnBuildTree($this->data);
    }

    public function toAdjacencyList($idField = 'id', $parentIdField = 'parent_id', $isIntegerInPath = true)
    {
        $al = [];

        foreach ($this->data as $node) {
            $pathList = array_filter(explode($this->pathSeparator, $node[$this->pathKey]));

            $node[$idField] = $isIntegerInPath ? (int)array_pop($pathList) : array_pop($pathList);

            $node[$parentIdField] = $isIntegerInPath ? 0 : '';
            if (!empty($pathList)) {
                $node[$parentIdField] = $isIntegerInPath ? (int)array_pop($pathList) : array_pop($pathList);
            }

            unset($node[$this->pathKey]);

            $al[] = $node;
        }

        return $al;
    }

    public function toNestedSet($leftFieldKey = 'lft', $rightFieldKey = 'rgt')
    {
        $fnBuildNestedSet = function ($nodes, $parentNode = null) use (
            &$fnBuildNestedSet,
            $leftFieldKey,
            $rightFieldKey
        ) {
            $ns = [];

            $left = $parentNode ? $parentNode[$leftFieldKey] + 1 : 1;

            foreach ($nodes as $node) {
                $isFirstLevelNode = count(array_filter(explode($this->pathSeparator, $node[$this->pathKey]))) < 2;

                if (!$isFirstLevelNode && !$parentNode) {
                    continue;
                }

                $childrenLength = count(array_filter($this->data, function ($currentNode) use ($node) {
                    return $node[$this->pathKey] !== $currentNode[$this->pathKey]
                        && $node[$this->pathKey] === substr(
                            $currentNode[$this->pathKey],
                            0,
                            strlen($node[$this->pathKey])
                        );
                })) * 2;

                $right = $left + $childrenLength + 1;

                $node[$leftFieldKey] = $left;
                $node[$rightFieldKey] = $right;

                $nodePath = $node[$this->pathKey];
                $ns[] = $node;

                if ($childrenLength) {
                    $children = array_filter($this->data, function ($currentNode) use ($nodePath) {
                        $parentPath = preg_replace(
                            sprintf("/(.+)%s[^$]/m", preg_quote($this->pathSeparator, '/')),
                            "$1",
                            $currentNode[$this->pathKey]
                        );

                        return $nodePath !== $currentNode[$this->pathKey] && $nodePath === $parentPath;
                    });

                    $ns = array_merge($ns, $fnBuildNestedSet($children, $node));
                }

                $left = $right + 1;
            }

            return $ns;
        };

        $nestedSet = $fnBuildNestedSet($this->data);

        uasort($nestedSet, function($firstNode, $secondNode) {
            $nodeIdRegexp = "/.*%s(.*)%s/";
            $escapedSeparator = preg_quote($this->pathSeparator, '/');

            $firstNodeId = preg_replace(
                sprintf($nodeIdRegexp, $escapedSeparator, $escapedSeparator),
                '$1',
                $firstNode[$this->pathKey]
            );

            $secondNodeId = preg_replace(
                sprintf($nodeIdRegexp, $escapedSeparator, $escapedSeparator),
                '$1',
                $secondNode[$this->pathKey]
            );

            return $firstNodeId > $secondNodeId;
        });

        return array_values(array_map(function($node) {
            unset($node[$this->pathKey]);
            return $node;
        }, $nestedSet));
    }
}
