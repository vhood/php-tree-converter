<?php

namespace Vhood\TreeType\Type;

use Vhood\TreeType\Exception\InvalidStructureException;

class NestedSet
{
    private $data;
    private $leftValueKey;
    private $rightValueKey;

    /**
     * @param array $flatTree
     * @param string $leftValueKey
     * @param string $rightValueKey
     * @return void
     * @throws InvalidStructureException
     */
    public function __construct(array $flatTree, $leftValueKey = 'lft', $rightValueKey = 'rgt')
    {
        foreach ($flatTree as $index => $node) {
            if (!array_key_exists($leftValueKey, $node)) {
                throw new InvalidStructureException("Node $index has no left value field");
            }

            if (!array_key_exists($leftValueKey, $node)) {
                throw new InvalidStructureException("Node $index has no right value Field");
            }

            if ($node[$leftValueKey] < 0) {
                throw new InvalidStructureException("Node $index has left value < 0");
            }

            if ($node[$rightValueKey] < 0) {
                throw new InvalidStructureException("Node $index has right value < 0");
            }
        }

        $this->leftValueKey = $leftValueKey;
        $this->rightValueKey = $rightValueKey;

        $this->data = array_values($flatTree);
    }
}
