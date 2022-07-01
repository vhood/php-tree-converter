<?php

namespace Vhood\TreeType\Service;

class FlatNodesService
{
    private $nodes;

    /**
     * @param array $nodes
     * @return void
     */
    public function __construct(array $nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * @param array $keyMapping ['currentKey' => 'newKey']
     * @return array $nodes
     */
    public function renameKeys(array $keyMapping)
    {
        return array_map(function ($node) use ($keyMapping) {
            return array_merge(
                array_diff_key($node, $keyMapping),
                array_combine($keyMapping, array_intersect_key($node, $keyMapping))
            );
        }, $this->nodes);
    }

    /**
     * @param array $keys
     * @return array $nodes
     */
    public function removeKeys(array $keys)
    {
        return array_map(function ($node) use ($keys) {
            return array_diff_key($node, array_flip($keys));
        }, $this->nodes);
    }
}
