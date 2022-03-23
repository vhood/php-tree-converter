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

    public function toAjacencyList()
    {
        return [];
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
