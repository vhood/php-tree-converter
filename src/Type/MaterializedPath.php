<?php

namespace Vhood\TreeType\Type;

use Vhood\TreeType\Contract\TreeType;
use Vhood\TreeType\Exception\InvalidStructureException;
use Vhood\TreeType\MaterializedPathConverter;

class MaterializedPath implements TreeType
{
    private $nodes;
    private $pathKey;
    private $pathSeparator;
    private $levelKey;
    private $idKey;

    /**
     * @param array $flatTree
     * @param string $pathKey
     * @param string $pathSeparator
     * @param null|string $levelKey
     * @param null|string $idKey
     * @return void
     * @throws InvalidStructureException
     */
    public function __construct(array $flatTree, $pathKey = 'path', $pathSeparator = '/', $levelKey = null, $idKey = null)
    {
        foreach ($flatTree as $index => $node) {
            if (!array_key_exists($pathKey, $node)) {
                throw new InvalidStructureException("Node $index has no path key");
            }

            if (empty(array_filter(explode($pathSeparator, $node[$pathKey])))) {
                throw new InvalidStructureException("Node $index has empty path");
            }

            if ($levelKey && !array_key_exists($levelKey, $node)) {
                throw new InvalidStructureException("Node $index has no level field");
            }

            if ($idKey && !array_key_exists($idKey, $node)) {
                throw new InvalidStructureException("Node $index has no id field");
            }
        }

        $this->pathKey = $pathKey;
        $this->pathSeparator = $pathSeparator;
        $this->levelKey = $levelKey;
        $this->idKey = $idKey;

        $this->nodes = array_values($flatTree);
    }

    /**
     * {@inheritdoc}
     */
    public function initConverter()
    {
        return new MaterializedPathConverter(
            $this->nodes,
            $this->pathKey,
            $this->pathSeparator,
            $this->levelKey,
            $this->idKey
        );
    }
}
