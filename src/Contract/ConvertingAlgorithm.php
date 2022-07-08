<?php

namespace Vhood\TreeType\Contract;

interface ConvertingAlgorithm
{
    /**
     * @param string $idKey
     * @param string $parentIdKey
     * @param array $nodes
     * @param array $recursiveParentNode
     * @return array
     */
    public function fromAdjacencyList($idKey, $parentIdKey, $nodes, $recursiveParentNode = null);

    /**
     * @param string $pathKey
     * @param string $pathSeparator
     * @param array $nodes
     * @param array $recursiveParentNode
     * @return array
     */
    public function fromMaterializedPath($pathKey, $pathSeparator, $nodes, $recursiveParentNode = null);

    /**
     * @param string $leftValueKey
     * @param string $rightValueKey
     * @param string $idKey
     * @param array $nodes
     * @param array $recursiveParentNode
     * @return array
     */
    public function fromNestedSet($leftValueKey, $rightValueKey, $idKey, $nodes, $recursiveParentNode = null);

    /**
     * @param string $childrenKey
     * @param string $idKey
     * @param array $nodes
     * @param array $recursiveParentNode
     * @return array
     */
    public function fromTree($childrenKey, $idKey, $nodes, $recursiveParentNode = null);
}
