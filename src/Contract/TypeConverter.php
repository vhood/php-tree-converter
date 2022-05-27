<?php

namespace Vhood\TreeType\Contract;

interface TypeConverter
{
    /**
     * @param string $idKey
     * @param string $parentIdKey
     * @return array
     */
    public function toAdjacencyList($idKey = 'id', $parentIdKey = 'parent_id'): array;

    /**
     * @param string $pathKey
     * @param string $pathSeparator
     * @param null|string $levelKey set to create levels or rename the level key
     * @return array
     */
    public function toMaterializedPath($pathKey = 'path', $pathSeparator = '/', $levelKey = null, $idKey = null): array;

    /**
     * @param string $leftValueKey
     * @param string $rightValueKey
     * @param null|string $idKey set to create identifiers or rename the identifier key
     * @return array
     */
    public function toNestedSet($leftValueKey = 'lft', $rightValueKey = 'rgt', $idKey = null): array;

    /**
     * @param string $childrenKey
     * @param null|string $idKey set to create identifiers or rename the identifier key
     * @return array
     */
    public function toTree($childrenKey = 'children', $idKey = null): array;
}
