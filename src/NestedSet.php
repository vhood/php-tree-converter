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

    public function toTree()
    {
        return [];
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
