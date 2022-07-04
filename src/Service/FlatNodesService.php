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
     * @param array $keyMap ['currentKey' => 'newKey', 'currentKey' => 'newKey']
     * @return array $nodes
     */
    public function renameKeys(array $keyMap)
    {
        $nodesRow = json_encode($this->nodes);

        foreach ($keyMap as $oldKey => $newKey) {
            $nodesRow = str_replace(
                sprintf('"%s":', $oldKey),
                sprintf('"%s":', $newKey),
                $nodesRow
            );
        }

        return json_decode($nodesRow, true);
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
