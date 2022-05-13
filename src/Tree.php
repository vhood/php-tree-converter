<?php

namespace Vhood\TreeType;

use Vhood\TreeType\Contract\TypeConverter;

class Tree implements TypeConverter
{
    private $data;
    private $childrenField;

    public function __construct(array $tree, $childrenField = 'children')
    {
        $this->childrenField = $childrenField;

        $this->data = array_values($tree);
    }

    public function toTree()
    {
        return $this->data;
    }

    public function toAjacencyList($idField = 'id', $parentIdField = 'parent_id', $noParentValue = 0)
    {
        $idExists = array_key_exists($idField, current($this->data));

        $fnBuildAjacencyList = function ($nodes, $parentNode = null) use (
            &$fnBuildAjacencyList,
            $idField,
            $parentIdField,
            $noParentValue,
            $idExists
        ) {
            $al = [];

            $id = 1;
            foreach ($nodes as $node) {
                if (!$idExists) {
                    $node[$idField] = $id;
                }

                $node[$parentIdField] = $parentNode
                    ? $parentNode[$idField]
                    : $noParentValue;

                if (!empty($node[$this->childrenField])) {
                    $al = array_merge($al, $fnBuildAjacencyList($node[$this->childrenField], $node));
                }
                unset($node[$this->childrenField]);
                $al[] = $node;

                $id++;
            }

            return $al;
        };

        $ajacencyList = $fnBuildAjacencyList($this->data);
        usort($ajacencyList, function ($first, $second) use ($idField) {
            return $first[$idField] > $second[$idField];
        });

        return $ajacencyList;
    }

    public function toMaterializedPath()
    {
        return [];
    }

    public function toNestedSet()
    {
        return [];
    }
}
