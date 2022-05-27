<?php

namespace Vhood\TreeType\Type;

use Vhood\TreeType\Exception\InvalidStructureException;

class AssociativeArrayTree
{
    private $data;
    private $childrenField;

    /**
     * @param array $tree
     * @param string $childrenField
     * @return void
     * @throws InvalidStructureException
     */
    public function __construct(array $tree, $childrenField = 'children')
    {
        $this->childrenField = $childrenField;

        if (empty($tree)) {
            throw new InvalidStructureException("Empty tree");
        }

        $this->data = array_values($tree);
    }
}
