<?php

namespace Tests\_support;

use PHPUnit\Framework\TestCase;

class FunctionalTestCase extends TestCase
{
    private $numBasedNodesPath = __DIR__ . '/../_data/num-based-nodes.php';
    private $slugBasedNodesPath = __DIR__ . '/../_data/slug-based-nodes.php';

    /**
     * @return array
     */
    public function minimalTree()
    {
        return require __DIR__ . '/../_data/tree/not-identified.php';
    }

    /**
     * @return array
     */
    public function numBasedTree()
    {
        return require __DIR__ . '/../_data/tree/num-identified.php';
    }

    /**
     * @return array
     */
    public function slugBasedTree()
    {
        return require __DIR__ . '/../_data/tree/slug-identified.php';
    }

    /**
     * @param array $keys
     * AL: [id, parent_id, {name}] \
     * MP: [path, {name}, {level}, {id}] \
     * NS: [lft, rgt, {name}, {id}]
     * @return array
     */
    protected function numBasedNodes(array $keys)
    {
        $nodes = require $this->numBasedNodesPath;

        return array_map(function ($node) use ($keys) {
            $filteredNodes = array_intersect_key($node, array_flip($keys));
            uksort($filteredNodes, function($key) { return $key !== 'id'; });
            return $filteredNodes;
        }, $nodes);
    }

    /**
     * @param array $keys
     * AL: [id, parent_id, {name}] \
     * MP: [path, {name}, {level}, {id}] \
     * NS: [lft, rgt, {name}, {id}]
     * @return array
     */
    protected function slugBasedNodes(array $keys)
    {
        $nodes = require $this->slugBasedNodesPath;

        return array_map(function ($node) use ($keys) {
            $filteredNodes = array_intersect_key($node, array_flip($keys));
            uksort($filteredNodes, function($key) { return $key !== 'id'; });
            return $filteredNodes;
        }, $nodes);
    }
}
