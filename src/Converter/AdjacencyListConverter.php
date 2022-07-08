<?php

namespace Vhood\TreeType\Converter;

use Vhood\TreeType\Algorithm\AdjacencyListCreator;
use Vhood\TreeType\Algorithm\AssociativeArrayTreeCreator;
use Vhood\TreeType\Algorithm\MaterializedPathCreator;
use Vhood\TreeType\Algorithm\NestedSetCreator;
use Vhood\TreeType\Contract\TypeConverter;
use Vhood\TreeType\Service\AssociativeArrayTreeService;
use Vhood\TreeType\Service\MaterializedPathService;

class AdjacencyListConverter implements TypeConverter
{
    private $nodes;
    private $idKey;
    private $parentIdKey;

    /**
     * @param array $nodes
     * @param string $idKey
     * @param string $parentIdKey
     * @return void
     */
    public function __construct($nodes, $idKey, $parentIdKey)
    {
        $this->idKey = $idKey;
        $this->parentIdKey = $parentIdKey;
        $this->nodes = $nodes;
    }

    /**
     * {@inheritdoc}
     */
    public function toAdjacencyList($idKey = 'id', $parentIdKey = 'parent_id')
    {
        $creator = new AdjacencyListCreator($this->idKey, $this->parentIdKey);

        return $creator->fromAdjacencyList($idKey, $parentIdKey, $this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function toMaterializedPath($pathKey = 'path', $pathSeparator = '/', $levelKey = null, $idKey = null)
    {
        $creator = new MaterializedPathCreator($pathKey, $pathSeparator);

        $materializedPath = $creator->fromAdjacencyList($this->idKey, $this->parentIdKey, $this->nodes);

        if ($levelKey) {
            $mpService = new MaterializedPathService($materializedPath, $pathKey, $pathSeparator);
            $materializedPath = $mpService->calculateLevels($levelKey);
        }

        $materializedPath = $creator->initService($materializedPath)->removeKeys([$this->parentIdKey]);

        if ($idKey && $idKey !== $this->idKey) {
            $materializedPath = $creator->initService($materializedPath)->renameKeys([$this->idKey => $idKey]);
        }

        if (!$idKey) {
            $materializedPath = $creator->initService($materializedPath)->removeKeys([$this->idKey]);
        }

        return $materializedPath;
    }

    /**
     * {@inheritdoc}
     */
    public function toNestedSet($leftValueKey = 'lft', $rightValueKey = 'rgt', $idKey = null)
    {
        $creator = new NestedSetCreator($leftValueKey, $rightValueKey);
        $nestedSet = $creator->fromAdjacencyList($this->idKey, $this->parentIdKey, $this->nodes);

        usort($nestedSet, function($firstNode, $secondNode) {
            return $firstNode[$this->idKey] > $secondNode[$this->idKey];
        });

        $nestedSet = $creator->initService($nestedSet)->removeKeys([$this->parentIdKey]);

        if ($idKey && $idKey !== $this->idKey) {
            $nestedSet = $creator->initService($nestedSet)->renameKeys([$this->idKey => $idKey]);
        }

        if (!$idKey) {
            $nestedSet = $creator->initService($nestedSet)->removeKeys([$this->idKey]);
        }

        return $nestedSet;
    }

    /**
     * {@inheritdoc}
     */
    public function toTree($childrenKey = 'children', $idKey = null)
    {
        $creator = new AssociativeArrayTreeCreator($childrenKey);

        $adjacencyList = $this->nodes;

        $identifier = $idKey ? $idKey : $this->idKey;

        if ($idKey && $idKey !== $this->idKey) {
            $adjacencyList = $creator
                ->initService($this->nodes)
                ->renameKeys([$this->idKey => $idKey]);
        }

        $tree = $creator->fromAdjacencyList($identifier, $this->parentIdKey, $adjacencyList);

        if (!$idKey) {
            $treeService = new AssociativeArrayTreeService($tree, $childrenKey);
            $tree = $treeService->removeTheField($this->idKey);
        }

        return $tree;
    }
}
