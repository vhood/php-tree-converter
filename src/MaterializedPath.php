<?php

namespace Vhood\TreeType;

use Vhood\TreeType\Contract\TypeConverter;
use Vhood\TreeType\Exception\InvalidStructureException;

class MaterializedPath implements TypeConverter
{
    private $data;
    private $pathField;
    private $pathSeparator;

    public function __construct(array $flatTree, $pathField = 'path', $pathSeparator = '/')
    {
        foreach ($flatTree as $index => $node) {
            if (!array_key_exists($pathField, $node)) {
                throw new InvalidStructureException("Element $index has no pathField");
            }

            if (empty(array_filter(explode($pathSeparator, $node[$pathField])))) {
                throw new InvalidStructureException("Element $index has empty pathField");
            }
        }

        $this->pathField = $pathField;
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
                if ($parentNode && $node[$this->pathField] === $parentNode[$this->pathField]) {
                    continue;
                }

                $isRoot = !$parentNode
                    && count(array_filter(explode($this->pathSeparator, $node[$this->pathField]))) < 2;

                $parentPath = preg_replace(
                    sprintf("/(.+)%s[^$]/m", preg_quote($this->pathSeparator, '/')),
                    "$1",
                    $node[$this->pathField]
                );

                $isRequestedChild = $parentNode
                    && !$isRoot
                    && $parentPath === $parentNode[$this->pathField];

                if ($isRoot || $isRequestedChild) {
                    $node[$childrenKey] = $fnBuildTree($nodes, $node);
                    unset($node[$this->pathField]);
                    $tree[] = $node;
                }
            }

            return $tree;
        };

        return $fnBuildTree($this->data);
    }

    public function toAjacencyList()
    {
        return [];
    }

    public function toNestedSet()
    {
        return [];
    }
}
