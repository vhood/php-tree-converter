<?php

namespace Vhood\TreeType\Service;

class NodeService
{
    private $node;

    public function __construct(array $node)
    {
        $this->node = $node;
    }

    /**
     * @param string $pathKey
     * @param string $pathSeparator
     * @return string
     * @uses O(1) big O notation for the runtime
     */
    public function findParentsPath($pathKey, $pathSeparator)
    {
        if (!array_key_exists($pathKey, $this->node) || !is_string($this->node[$pathKey])) {
            return '';
        }

        $parentPath = (string)preg_replace(
            sprintf(
                "/(.+%s).+%s$/m",
                preg_quote($pathSeparator, '/'),
                preg_quote($pathSeparator, '/')
            ),
            "$1",
            $this->node[$pathKey]
        );

        return $parentPath === $this->node[$pathKey] ? '' : $parentPath;
    }
}
