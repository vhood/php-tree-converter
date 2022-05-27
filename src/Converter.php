<?php

namespace Vhood\TreeType;

use Vhood\TreeType\Contract\TreeType;
use Vhood\TreeType\Contract\TypeConverter;

class Converter implements TypeConverter
{
    private TypeConverter $typeConverter;

    /**
     * @param TreeType $tree
     * @return void
     */
    public function __construct(TreeType $tree)
    {
        $this->typeConverter = $tree->initConverter();
    }

    /**
     * {@inheritdoc}
     */
    public function toAdjacencyList($idKey = 'id', $parentIdKey = 'parent_id'): array
    {
        return $this->typeConverter->toAdjacencyList($idKey, $parentIdKey);
    }

    /**
     * {@inheritdoc}
     */
    public function toMaterializedPath($pathKey = 'path', $pathSeparator = '/', $levelKey = null, $idKey = null): array
    {
        return $this->typeConverter->toMaterializedPath($pathKey, $pathSeparator, $levelKey, $idKey);
    }

    /**
     * {@inheritdoc}
     */
    public function toNestedSet($leftValueKey = 'lft', $rightValueKey = 'rgt', $idKey = null): array
    {
        return $this->typeConverter->toNestedSet($leftValueKey, $rightValueKey, $idKey);
    }

    /**
     * {@inheritdoc}
     */
    public function toTree($childrenKey = 'children', $idKey = null): array
    {
        return $this->typeConverter->toTree($childrenKey, $idKey);
    }
}
