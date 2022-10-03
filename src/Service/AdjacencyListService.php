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
     * @param array $parentsMap @see listParents()
     * @return string path
     * @uses O(1) big O notation for the runtime
     */
    public function buildNodePath($node, $pathSeparator, $parentsMap)
    {
        $path = '';

        if (array_key_exists($node[$this->idKey], $parentsMap)) {
            $path .= $this->buildNodePath($parentsMap[$node[$this->idKey]], $pathSeparator, $parentsMap);
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

    /**
     * @return array
     * @uses O(n) big O notation for the runtime
     */
    public function listParents()
    {
        $children = $nodesWithIdentifiedKeys = [];

        foreach ($this->nodes as $node) {
            $nodesWithIdentifiedKeys[$node[$this->idKey]] = $node;
        }

        foreach ($this->nodes as $node) {
            if (empty($node[$this->parentIdKey])) {
                continue;
            }

            $children[$node[$this->idKey]] = $nodesWithIdentifiedKeys[$node[$this->parentIdKey]];
        }

        return $children;
    }
}
