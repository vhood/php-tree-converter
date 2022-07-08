<?php

namespace Vhood\TreeType\Contract;

interface TypeConverter
{
    /**
     * @param string $idKey
     * @param string $parentIdKey
     * @return array
     */
    public function toAdjacencyList($idKey = 'id', $parentIdKey = 'parent_id');

    /**
     * @param string $pathKey
     * @param string $pathSeparator
     * @param null|string $levelKey set to create levels or rename the level key
     * @return array
     */
    public function toMaterializedPath($pathKey = 'path', $pathSeparator = '/', $levelKey = null, $idKey = null);

    /**
     * @param string $leftValueKey
     * @param string $rightValueKey
     * @param null|string $idKey set to create identifiers or rename the identifier key
     * @return array
     */
    public function toNestedSet($leftValueKey = 'lft', $rightValueKey = 'rgt', $idKey = null);

    /**
     * @param string $childrenKey
     * @param null|string $idKey set to create identifiers or rename the identifier key
     * @return array
     */
    public function toAssociativeArrayTree($childrenKey = 'children', $idKey = null);
}
