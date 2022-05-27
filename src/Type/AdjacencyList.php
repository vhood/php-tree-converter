<?php

namespace Vhood\TreeType\Type;

use Vhood\TreeType\Contract\TreeType;
use Vhood\TreeType\Contract\TypeConverter;
use Vhood\TreeType\Converter\AdjacencyListConverter;
use Vhood\TreeType\Exception\InvalidStructureException;

class AdjacencyList implements TreeType
{
    private $nodes;
    private $idKey;
    private $parentIdKey;

    /**
     * @param array $flatTree
     * @param string $idKey
     * @param string $parentIdKey
     * @return void
     * @throws InvalidStructureException
     */
    public function __construct(array $flatTree, $idKey = 'id', $parentIdKey = 'parent_id')
    {
        foreach ($flatTree as $index => $node) {
            if (!array_key_exists($idKey, $node)) {
                throw new InvalidStructureException("Node $index has no id key");
            }
        }

        $this->idKey = $idKey;
        $this->parentIdKey = $parentIdKey;

        $this->nodes = array_values($flatTree);
    }

    /**
     * {@inheritdoc}
     */
    public function initConverter(): TypeConverter
    {
        return new AdjacencyListConverter($this, $this->nodes, $this->idKey, $this->parentIdKey);
    }
}
