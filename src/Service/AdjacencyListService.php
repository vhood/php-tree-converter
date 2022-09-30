<?php

namespace Vhood\TreeType\Service;

class AdjacencyListService
{
    private $nodes;
    private $idKey;
    private $parentIdKey;

    /**
     * @param array $nodes
     * @param string $idKey
     * @param string $parentIdKey
     * @return void
     */
    public function __construct($nodes, $idKey, $parentIdKey)
    {
        $this->idKey = $idKey;
        $this->parentIdKey = $parentIdKey;
        $this->nodes = $nodes;
    }

    /**
     * @param array $node
     * @param string $pathSeparator
     * @return string path
     * @uses O(n) big O notation for the runtime
     */
    public function buildNodePath($node, $pathSeparator)
    {
        $path = '';

        foreach ($this->nodes as $iterableNode) {
            if ($node[$this->parentIdKey] !== $iterableNode[$this->idKey]) {
                continue;
            }

            $path .= $this->buildNodePath($iterableNode, $pathSeparator);
        }

        $path .= $pathSeparator . $node[$this->idKey];

        return $path;
    }

    /**
     * @param array $node
     * @return int
     * @uses O(n) big O notation for the runtime
     */
    public function calculateChildren($node)
    {
        $children = 0;

        foreach ($this->nodes as $iterableNode) {
            if ($iterableNode[$this->parentIdKey] !== $node[$this->idKey]) {
                continue;
            }

            $children++;
            $children += $this->calculateChildren($iterableNode);
        }

        return $children;
    }
}
