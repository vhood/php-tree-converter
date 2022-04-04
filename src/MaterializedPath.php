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

    public function toAjacencyList($idField = 'id', $parentIdField = 'parent_id', $isIntegerInPath = true)
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

    public function toNestedSet()
    {
        return [];
    }
}
