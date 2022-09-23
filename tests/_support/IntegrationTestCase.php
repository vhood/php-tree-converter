<?php

namespace Tests\_support;

use PHPUnit\Framework\TestCase;

class IntegrationTestCase extends TestCase
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
            $filteredNode = array_intersect_key($node, array_flip($keys));
            uksort($filteredNode, function($key) { return $key !== 'id'; });
            return $filteredNode;
        }, $nodes);
    }

    /**
     * @param array $keys
     * AL: [id, parent_id, {name}] \
     * MP: [path, {name}, {level}, {id}] \
     * NS: [lft, rgt, {name}, {id}]
     * @return array
     */
    protected function slugBasedAndSlugSortedNodes(array $keys)
    {
        $nodes = require $this->slugBasedNodesPath;

        $removeId = !in_array('id', $keys);

        if ($removeId) {
            $keys[] = 'id';
        }

        $filteredNodes = array_map(function ($node) use ($keys) {
            $filteredNode = array_intersect_key($node, array_flip($keys));
            uksort($filteredNode, function($key) { return $key !== 'id'; });
            return $filteredNode;
        }, $nodes);

        usort($filteredNodes, function($firstNode, $secondNode) {
            return $firstNode['id'] > $secondNode['id'];
        });

        if ($removeId) {
            $filteredNodes = array_map(function($node) {
                unset($node['id']);
                return $node;
            }, $filteredNodes);
        }

        return $filteredNodes;
    }
}
