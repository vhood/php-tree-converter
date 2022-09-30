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
     * @uses O(n) big O notation for the runtime
     */
    public function identifyNodes($idKey = 'id')
    {
        $nodes = $this->nodes;

        usort($nodes, function ($firstNode, $secondNode) {
            return $firstNode[$this->leftValueKey] > $secondNode[$this->leftValueKey];
        });

        $result = [];
        $id = 1;
        while (!empty($nodes)) {
            $result[] = array_merge([$idKey => $id], array_shift($nodes));
            $id++;
        }

        return $result;
    }
}
