<?php

namespace Vhood\TreeType;

use Vhood\TreeType\Contract\TypeConverter;
use Vhood\TreeType\Exception\InvalidStructureException;

class MaterializedPath implements TypeConverter
{
    private $data;
    private $pathField;
    private $pathSplitter;

    public function __construct(array $flatTree, $pathField = 'path', $pathSplitter = '/')
    {
        foreach ($flatTree as $index => $node) {
            if (!array_key_exists($pathField, $node)) {
                throw new InvalidStructureException("Element $index has no pathField");
            }
        }

        $this->pathField = $pathField;
        $this->pathSplitter = $pathSplitter;

        $this->data = array_values($flatTree);
    }

    public function toMaterializedPath()
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

    public function toNestedSet()
    {
        return [];
    }
}
