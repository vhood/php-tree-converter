<?php

namespace Vhood\TreeType\Type;

use Vhood\TreeType\Exception\InvalidStructureException;

class MaterializedPath
{
    private $data;
    private $pathKey;
    private $levelKey;
    private $pathSeparator;

    /**
     * @param array $flatTree
     * @param string $pathKey
     * @param string $levelKey not required field
     * @param string $pathSeparator
     * @return void
     * @throws InvalidStructureException
     */
    public function __construct(array $flatTree, $pathKey = 'path', $pathSeparator = '/', $levelKey = 'level')
    {
        foreach ($flatTree as $index => $node) {
            if (!array_key_exists($pathKey, $node)) {
                throw new InvalidStructureException("Node $index has no path key");
            }

            if (empty(array_filter(explode($pathSeparator, $node[$pathKey])))) {
                throw new InvalidStructureException("Node $index has empty path");
            }
        }

        $this->pathKey = $pathKey;
        $this->levelKey = $levelKey;
        $this->pathSeparator = $pathSeparator;

        $this->data = array_values($flatTree);
    }
}
