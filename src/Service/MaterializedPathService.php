<?php

namespace Vhood\TreeType\Service;

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
     */
    public function identifyNodes($idKey)
    {
        return array_map(function ($node) use ($idKey) {
            $nodeIdRegexp = "/.*%s(.*)%s/";
            $escapedSeparator = preg_quote($this->pathSeparator, '/');

            $node = array_merge([$idKey => preg_replace(
                sprintf($nodeIdRegexp, $escapedSeparator, $escapedSeparator),
                '$1',
                $node[$this->pathKey]
            )], $node);

            return $node;
        }, $this->nodes);
    }

    /**
     * @param string $pathKey
     * @param string $pathSeparator
     * @return array
     */
    public function rebuildPath($pathKey, $pathSeparator)
    {
        return array_map(function($node) use ($pathKey, $pathSeparator)  {
            $node[$pathKey] = str_replace($this->pathSeparator, $pathSeparator, $node[$this->pathKey]);

            unset($node[$this->pathKey]);

            return $node;
        }, $this->nodes);
    }

    /**
     * @param array $node
     * @return int
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
     */
    public function calculateLevels($levelKey)
    {
        return array_map(function ($node) use ($levelKey) {
            $node[$levelKey] = count(array_filter(explode($this->pathSeparator, $node[$this->pathKey])));

            return $node;
        }, $this->nodes);
    }
}
