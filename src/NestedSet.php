<?php

namespace Vhood\TreeType;

use Vhood\TreeType\Contract\TypeConverter;
use Vhood\TreeType\Exception\InvalidStructureException;

class NestedSet implements TypeConverter
{
    private $data;
    private $leftIdField;
    private $rightIdField;

    public function __construct(array $flatTree, $leftIdField = 'lft', $rightIdField = 'rgt')
    {
        foreach ($flatTree as $index => $node) {
            if (!array_key_exists($leftIdField, $node)) {
                throw new InvalidStructureException("Element $index has no leftIdField");
            }

            if (!array_key_exists($leftIdField, $node)) {
                throw new InvalidStructureException("Element $index has no rightIdField");
            }
        }

        $this->leftIdField = $leftIdField;
        $this->rightIdField = $rightIdField;

        $this->data = array_values($flatTree);
    }

    public function toNestedSet()
    {
        return $this->data;
    }

    public function toTree($childrenKey = 'children')
    {
        $fnBuildTree = function($data, $parentNode = null) use (&$fnBuildTree, $childrenKey) {
            $tree = [];

            foreach ($data as $k => $node) {
                $parents = array_filter($this->data, function ($currentNode) use ($node) {
                    return $currentNode[$this->leftIdField] < $node[$this->leftIdField]
                        && $currentNode[$this->rightIdField] > $node[$this->rightIdField];
                });

                $haveParent = !empty($parents);
                $immediateParent = null;

                if ($haveParent) {
                    uasort($parents, function($first, $second) {
                        return $first[$this->leftIdField] < $second[$this->leftIdField];
                    });
                    $immediateParent = array_shift($parents);
                }

                $isRequestedChild = $parentNode
                    && $immediateParent
                    && $immediateParent[$this->leftIdField] === $parentNode[$this->leftIdField];

                if (!$haveParent && !$parentNode || $isRequestedChild) {
                    $nodeToSave = $node;
                    unset($data[$k]);

                    $haveChildren = $nodeToSave[$this->rightIdField] - $nodeToSave[$this->leftIdField] > 1;

                    $nodeToSave[$childrenKey] = $haveChildren ? $fnBuildTree($data, $nodeToSave) : [];

                    unset($nodeToSave[$this->leftIdField]);
                    unset($nodeToSave[$this->rightIdField]);

                    $tree[] = $nodeToSave;

                    continue;
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

    public function toMaterializedPath()
    {
        return [];
    }
}
