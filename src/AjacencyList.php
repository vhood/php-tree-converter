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
        $fnBuildTree = function ($node, $requestFromParentId = null) use (&$fnBuildTree, $childrenKey) {
            $tree = [];

            foreach ($node as $data) {
                $thisNodeHaveNoParent = empty($requestFromParentId)
                    && empty($data[$this->parentIdField]);

                $isRequestedChild = !empty($requestFromParentId)
                    && !empty($data[$this->parentIdField])
                    && $data[$this->parentIdField] == $requestFromParentId;

                if ($thisNodeHaveNoParent || $isRequestedChild) {
                    $data[$childrenKey] = $fnBuildTree($node, $data[$this->idField]);
                    unset($data[$this->parentIdField]);
                    $tree[] = $data;
                }
            }

            return $tree;
        };

        return $fnBuildTree($this->data);
    }

    public function toMaterializedPath($pathKey = 'path', $separator = '/')
    {
        $fnBuildPath = function ($node) use (&$fnBuildPath, $separator) {
            $path = $separator;

            foreach ($this->data as $data) {
                if ($node[$this->parentIdField] !== $data[$this->idField]) {
                    continue;
                }

                $path .= $data[$this->idField];
                $path .= $fnBuildPath($data);
            }

            return implode($separator, array_reverse(explode($separator, $path)));
        };

        return array_map(function ($data) use ($fnBuildPath, $pathKey) {
            $data[$pathKey] = $fnBuildPath($data);
            unset($data[$this->parentIdField]);

            return $data;
        }, $this->data);
    }

    public function toNestedSet()
    {
        return [];
    }
}
