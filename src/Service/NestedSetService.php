<?php

namespace Vhood\TreeType\Service;

class NestedSetService
{
    private $nodes;
    private $leftValueKey;
    private $rightValueKey;

    /**
     * @param array $nodes
     * @param string $leftValueKey
     * @param string $rightValueKey
     * @return void
     */
    public function __construct($nodes, $leftValueKey, $rightValueKey)
    {
        $this->leftValueKey = $leftValueKey;
        $this->rightValueKey = $rightValueKey;
        $this->nodes = $nodes;
    }

    /**
     * @param string $idKey
     * @return array
     */
    public function identifyNodes($idKey = 'id')
    {
        $nestedSet = $this->nodes;

        uasort($nestedSet, function ($firstNode, $secondNode) {
            return $firstNode[$this->leftValueKey] > $secondNode[$this->leftValueKey];
        });

        array_walk($nestedSet, function ($node, $id) use ($idKey) {
            $node[$idKey] = $id;
        });

        return $nestedSet;
    }
}
