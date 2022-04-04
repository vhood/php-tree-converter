<?php

namespace Vhood\TreeType;

use Vhood\TreeType\Contract\TypeConverter;
use Vhood\TreeType\Exception\InvalidStructureException;

class AjacencyList implements TypeConverter
{
    private $data;
    private $idField;
    private $parentIdField;

    public function __construct(array $flatTree, $idField = 'id', $parentIdField = 'parent_id')
    {
        foreach ($flatTree as $index => $node) {
            if (!array_key_exists($idField, $node)) {
                throw new InvalidStructureException("Element $index has no idField");
            }

            if (!array_key_exists($parentIdField, $node)) {
                throw new InvalidStructureException("Element $index has no parentIdField");
            }
        }

        $this->idField = $idField;
        $this->parentIdField = $parentIdField;

        $this->data = array_values($flatTree);
    }

    public function toAjacencyList()
    {
        return $this->data;
    }

    public function toTree($childrenKey = 'children')
    {
        $fnBuildTree = function ($nodes, $parentNode = null) use (&$fnBuildTree, $childrenKey) {
            $tree = [];

            foreach ($nodes as $node) {
                $thisNodeHasNoParent = !($parentNode || $node[$this->parentIdField]);

                $isRequestedChild = $parentNode
                    && $node[$this->parentIdField]
                    && $node[$this->parentIdField] == $parentNode[$this->idField];

                if ($thisNodeHasNoParent || $isRequestedChild) {
                    $node[$childrenKey] = $fnBuildTree($nodes, $node);
                    unset($node[$this->parentIdField]);
                    $tree[] = $node;
                }
            }

            return $tree;
        };

        return $fnBuildTree($this->data);
    }

    public function toMaterializedPath($pathKey = 'path', $separator = '/')
    {
        $fnBuildPath = function ($currentNode) use (&$fnBuildPath, $separator) {
            $path = '';

            foreach ($this->data as $node) {
                if ($currentNode[$this->parentIdField] !== $node[$this->idField]) {
                    continue;
                }

                $path .= $fnBuildPath($node);
            }

            $path .= $separator . $currentNode[$this->idField];

            return $path;
        };

        return array_map(function ($node) use ($fnBuildPath, $pathKey, $separator) {
            $node[$pathKey] = $fnBuildPath($node) . $separator;
            unset($node[$this->parentIdField]);

            return $node;
        }, $this->data);
    }

    public function toNestedSet($leftFieldKey = 'lft', $rightFieldKey = 'rgt')
    {
        $fnCalculateChildrenLength = function($parentNode) use (&$fnCalculateChildrenLength) {
            $children = 0;

            foreach ($this->data as $node) {
                if ($node[$this->parentIdField] !== $parentNode[$this->idField]) {
                    continue;
                }

                $children++;
                $children += $fnCalculateChildrenLength($node);
            }

            return $children;
        };

        $fnBuildNestedSet = function ($nodes, $parentNode = null) use (
            &$fnBuildNestedSet,
            $fnCalculateChildrenLength,
            $leftFieldKey,
            $rightFieldKey
        ) {
            $ns = [];

            $left = $parentNode ? $parentNode[$leftFieldKey] + 1 : 1;

            foreach ($nodes as $node) {
                $isFirstLevelNode = !$node[$this->parentIdField];

                if (!$isFirstLevelNode && !$parentNode) {
                    continue;
                }

                $childrenLength = $fnCalculateChildrenLength($node) * 2;

                $right = $left + $childrenLength + 1;

                $node[$leftFieldKey] = $left;
                $node[$rightFieldKey] = $right;

                unset($node[$this->parentIdField]);

                $ns[] = $node;

                $left = $right + 1;

                if ($childrenLength) {
                    $children = array_filter($this->data, function($currentNode) use ($node) {
                        return $currentNode[$this->parentIdField] === $node[$this->idField];
                    });

                    $ns = array_merge($ns, $fnBuildNestedSet($children, $node));
                }
            }

            usort($ns, function($firstNode, $secondNode) {
                return $firstNode[$this->idField] > $secondNode[$this->idField];
            });

            return $ns;
        };

        return $fnBuildNestedSet($this->data);
    }
}
