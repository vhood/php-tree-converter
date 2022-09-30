<?php

namespace Vhood\TreeType\Specification;

class MaterializedPathSpecification
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
     * @return bool
     * @uses O(n) big O notation for the runtime
     */
    public function areIdentifiersNumeric()
    {
        return empty(array_filter($this->nodes, function ($node) {
            return !is_numeric(end(array_filter(explode($this->pathSeparator, $node[$this->pathKey]))));
        }));
    }
}
