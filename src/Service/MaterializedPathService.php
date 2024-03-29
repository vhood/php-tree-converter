<?php

namespace Vhood\TreeType\Service;

use Vhood\TreeType\Specification\MaterializedPathSpecification;

class MaterializedPathService
{
    private $nodes;
    private $pathKey;
    private $pathSeparator;

    /**
     * @param array $nodes
     * @param string $pathKey
     * @param string $pathSeparator
     * @return void
     */
    public function __construct($nodes, $pathKey, $pathSeparator)
    {
        $this->pathKey = $pathKey;
        $this->pathSeparator = $pathSeparator;
        $this->nodes = $nodes;
    }

    /**
     * @param string $idKey
     * @return array
     * @uses O(n) big O notation for the runtime
     */
    public function identifyNodes($idKey)
    {
        $nodes = array_map(function ($node) use ($idKey) {
            $nodeIdRegexp = "/.*%s(.*)%s/";
            $escapedSeparator = preg_quote($this->pathSeparator, '/');

            $node = array_merge([$idKey => preg_replace(
                sprintf($nodeIdRegexp, $escapedSeparator, $escapedSeparator),
                '$1',
                $node[$this->pathKey]
            )], $node);

            return $node;
        }, $this->nodes);

        $mpSpecification = new MaterializedPathSpecification($nodes, $this->pathKey, $this->pathSeparator);

        if ($mpSpecification->areIdentifiersNumeric()) {
            $nodes = array_map(function ($node) use ($idKey) {
                $node[$idKey] = (int)$node[$idKey];
                return $node;
            }, $nodes);
        }

        return $nodes;
    }

    /**
     * @param string $pathKey
     * @param string $pathSeparator
     * @return array
     * @uses O(n) big O notation for the runtime
     */
    public function rebuildPath($pathKey, $pathSeparator)
    {
        $needToRenameKey = $this->pathKey !== $pathKey;

        return array_map(function ($node) use ($pathKey, $pathSeparator, $needToRenameKey)  {
            $node[$pathKey] = str_replace($this->pathSeparator, $pathSeparator, $node[$this->pathKey]);

            if ($needToRenameKey) {
                unset($node[$this->pathKey]);
            }

            return $node;
        }, $this->nodes);
    }

    /**
     * @param array $node
     * @return int
     * @uses O(n) big O notation for the runtime
     */
    public function calculateChildren($node)
    {
        return count(array_filter($this->nodes, function ($iterableNode) use ($node) {
            return $node[$this->pathKey] !== $iterableNode[$this->pathKey]
                && $node[$this->pathKey] === substr(
                    $iterableNode[$this->pathKey],
                    0,
                    strlen($node[$this->pathKey])
                );
        }));
    }

    /**
     * @param string $levelKey
     * @return array
     * @uses O(n) big O notation for the runtime
     */
    public function calculateLevels($levelKey)
    {
        return array_map(function ($node) use ($levelKey) {
            $node[$levelKey] = count(array_filter(explode($this->pathSeparator, $node[$this->pathKey])));

            return $node;
        }, $this->nodes);
    }
}
