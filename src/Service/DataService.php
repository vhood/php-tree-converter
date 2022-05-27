<?php

namespace Vhood\TreeType\Service;

class DataService
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
        $nodes = $this->nodes;

        foreach ($nodes as $node) {
            foreach ($keyMapping as $currentKey => $newKey) {
                if ($currentKey === $newKey) {
                    continue;
                }

                $node[$newKey] = $node[$currentKey];
                unset($node[$currentKey]);
            }
        }

        return $nodes;
    }

    /**
     * @param array $keys
     * @return array $nodes
     */
    public function removeKeys(array $keys)
    {
        $nodes = $this->nodes;

        foreach ($nodes as $node) {
            foreach ($keys as $key) {
                unset($node[$key]);
            }
        }

        return $nodes;
    }
}
