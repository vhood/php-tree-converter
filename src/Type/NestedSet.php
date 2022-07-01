<?php

namespace Vhood\TreeType\Type;

use Vhood\TreeType\Contract\TreeType;
use Vhood\TreeType\Exception\InvalidStructureException;
use Vhood\TreeType\NestedSetConverter;

class NestedSet implements TreeType
{
    private $nodes;
    private $leftValueKey;
    private $rightValueKey;
    private $idKey;

    /**
     * @param array $flatTree
     * @param string $leftValueKey
     * @param string $rightValueKey
     * @param null|string $idKey
     * @return void
     * @throws InvalidStructureException
     */
    public function __construct(array $flatTree, $leftValueKey = 'lft', $rightValueKey = 'rgt', $idKey = null)
    {
        foreach ($flatTree as $index => $node) {
            if (!array_key_exists($leftValueKey, $node)) {
                throw new InvalidStructureException("Node $index has no left value field");
            }

            if (!array_key_exists($leftValueKey, $node)) {
                throw new InvalidStructureException("Node $index has no right value field");
            }

            if (!is_integer($node[$leftValueKey])) {
                throw new InvalidStructureException("Node $index has incorrect left value type");
            }

            if (!is_integer($node[$rightValueKey])) {
                throw new InvalidStructureException("Node $index has incorrect right value type");
            }

            if ($idKey && !array_key_exists($idKey, $node)) {
                throw new InvalidStructureException("Node $index has no id field");
            }
        }

        $this->leftValueKey = $leftValueKey;
        $this->rightValueKey = $rightValueKey;
        $this->idKey = $idKey;

        $this->nodes = array_values($flatTree);
    }

    /**
     * {@inheritdoc}
     */
    public function initConverter()
    {
        return new NestedSetConverter($this->nodes, $this->leftValueKey, $this->rightValueKey, $this->idKey);
    }
}
