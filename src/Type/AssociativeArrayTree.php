<?php

namespace Vhood\TreeType\Type;

use Vhood\TreeType\AssociativeArrayTreeConverter;
use Vhood\TreeType\Contract\TreeType;
use Vhood\TreeType\Contract\TypeConverter;
use Vhood\TreeType\Exception\InvalidStructureException;
use Vhood\TreeType\Service\AssociativeArrayTreeService;

class AssociativeArrayTree implements TreeType
{
    private $nodes;
    private $childrenKey;
    private $idKey;

    /**
     * @param array $tree
     * @param string $childrenKey
     * @param null|string $idKey
     * @return void
     * @throws InvalidStructureException
     */
    public function __construct(array $tree, $childrenKey = 'children', $idKey = null)
    {
        $this->childrenKey = $childrenKey;
        $this->idKey = $idKey;

        if (empty($tree)) {
            throw new InvalidStructureException("Empty tree");
        }

        if ($idKey) {
            $treeService = new AssociativeArrayTreeService($tree, $childrenKey, $idKey);
            $treeService->validateIdField($idKey);
        }

        $this->nodes = array_values($tree);
    }

    /**
     * {@inheritdoc}
     */
    public function initConverter(): TypeConverter
    {
        return new AssociativeArrayTreeConverter($this->nodes, $this->childrenKey, $this->idKey);
    }
}
