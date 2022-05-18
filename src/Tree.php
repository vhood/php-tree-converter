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

    public function toAdjacencyList($idField = 'id', $parentIdField = 'parent_id', $noParentValue = 0)
    {
        $idExists = array_key_exists($idField, current($this->data));
        $id = 1;

        $fnBuildAdjacencyList = function ($nodes, $parentNode = null) use (
            &$fnBuildAdjacencyList,
            &$id,
            $idField,
            $parentIdField,
            $noParentValue,
            $idExists
        ) {
            $al = [];

            foreach ($nodes as $node) {
                if (!$idExists) {
                    $node[$idField] = $id;
                    $id++;
                }

                $node[$parentIdField] = $parentNode
                    ? $parentNode[$idField]
                    : $noParentValue;

                if (!empty($node[$this->childrenField])) {
                    $al = array_merge($al, $fnBuildAdjacencyList($node[$this->childrenField], $node));
                }
                unset($node[$this->childrenField]);
                $al[] = $node;
            }

            return $al;
        };

        $adjacencyList = $fnBuildAdjacencyList($this->data);
        usort($adjacencyList, function ($first, $second) use ($idField) {
            return $first[$idField] > $second[$idField];
        });

        return $adjacencyList;
    }

    public function toMaterializedPath($existedIdField = 'id', $pathKey = 'path', $separator = '/')
    {
        $idExists = $existedIdField ? array_key_exists($existedIdField, current($this->data)) : false;
        $idField = $existedIdField && $idExists ? $existedIdField : 'id';

        $mp = [];
        $id = 1;
        $fnBuildPath = function ($node, $parentNode = null) use (
            &$fnBuildPath,
            &$mp,
            &$id,
            $idExists,
            $idField,
            $pathKey,
            $separator
        ) {
            if (!$idExists) {
                $node[$idField] = $id;
                $id++;
            }

            $node[$pathKey] = $parentNode
                ? sprintf('%s%s%s', $parentNode[$pathKey], $separator, $node[$idField])
                : sprintf($node[$idField]);

            if (!empty($node[$this->childrenField])) {
                foreach ($node[$this->childrenField] as $child) {
                    $fnBuildPath($child, $node);
                }
            }

            unset($node[$this->childrenField]);

            $mp[] = $node;
        };

        foreach ($this->data as $node) {
            $fnBuildPath($node);
        }

        usort($mp, function ($first, $second) use ($idField) {
            return $first[$idField] > $second[$idField];
        });

        return array_map(function ($node) use ($pathKey, $separator) {
            $node[$pathKey] = sprintf('%s%s%s', $separator, $node[$pathKey], $separator);
            return $node;
        }, $mp);
    }

    public function toNestedSet($leftFieldKey = 'lft', $rightFieldKey = 'rgt', $existedIdField = 'id')
    {
        $idExists = $existedIdField ? array_key_exists($existedIdField, current($this->data)) : false;
        $idField = $existedIdField && $idExists ? $existedIdField : 'id';

        $ns = [];
        $id = 1;
        $definedFiledsAmount = 4;

        $fnBuildNestedSet = function ($nodes, $lft) use (
            &$fnBuildNestedSet,
            &$ns,
            &$id,
            $definedFiledsAmount,
            $leftFieldKey,
            $rightFieldKey,
            $idExists,
            $idField
        ) {
            foreach ($nodes as $node) {
                if (!$idExists) {
                    $node[$idField] = $id;
                    $id++;
                }

                $node[$leftFieldKey] = $lft;
                $rgt = (count($node[$this->childrenField], COUNT_RECURSIVE) / $definedFiledsAmount * 2) + $lft + 1;
                $node[$rightFieldKey] = $rgt;

                if (!empty($node[$this->childrenField])) {
                    $fnBuildNestedSet($node[$this->childrenField], $lft + 1);
                }
                unset($node[$this->childrenField]);

                $ns[] = $node;

                $lft = $rgt + 1;
            }

            usort($ns, function ($first, $second) use ($idField) {
                return $first[$idField] > $second[$idField];
            });

            return $ns;
        };

        return $fnBuildNestedSet($this->data, 1);
    }
}
