<?php

namespace Vhood\TreeType;

use Vhood\TreeType\Algorithm\AdjacencyListCreator;
use Vhood\TreeType\Algorithm\AssociativeArrayTreeCreator;
use Vhood\TreeType\Algorithm\MaterializedPathCreator;
use Vhood\TreeType\Algorithm\NestedSetCreator;
use Vhood\TreeType\Contract\TypeConverter;
use Vhood\TreeType\Service\AssociativeArrayTreeService;
use Vhood\TreeType\Service\MaterializedPathService;

class AssociativeArrayTreeConverter implements TypeConverter
{
    private $nodes;
    private $childrenKey;
    private $idKey;

    /**
     * @param array $nodes
     * @param string $childrenKey
     * @param null|string $idKey
     * @return void
     */
    public function __construct($nodes, $childrenKey = 'children', $idKey = null)
    {
        $this->childrenKey = $childrenKey;
        $this->idKey = $idKey;
        $this->nodes = $nodes;
    }

    /**
     * {@inheritdoc}
     */
    public function toAdjacencyList($idKey = 'id', $parentIdKey = 'parent_id'): array
    {
        $associativeArrayTree = $this->nodes;

        $treeService = new AssociativeArrayTreeService($this->nodes, $this->childrenKey);

        if (!$this->idKey) {
            $associativeArrayTree = $treeService->identifyNodes($idKey);
        }

        if ($this->idKey && $this->idKey !== $idKey) {
            $associativeArrayTree = $treeService->renameTheKey($this->idKey, $idKey);
        }

        $creator = new AdjacencyListCreator($idKey, $parentIdKey);

        $adjacencyList = $creator->fromTree($this->childrenKey, $idKey, $associativeArrayTree);

        usort($adjacencyList, function ($first, $second) use ($idKey) {
            return $first[$idKey] > $second[$idKey];
        });

        return $adjacencyList;
    }

    /**
     * {@inheritdoc}
     */
    public function toMaterializedPath($pathKey = 'path', $pathSeparator = '/', $levelKey = null, $idKey = null): array
    {
        $creator = new MaterializedPathCreator($pathKey, $pathSeparator);

        $associativeArrayTree = $this->nodes;
        $identifier = $idKey ? $idKey : 'id';

        if (!$this->idKey) {
            $treeService = new AssociativeArrayTreeService($this->nodes, $this->childrenKey);
            $associativeArrayTree = $treeService->identifyNodes($identifier);
        }

        $materializedPath = $creator->fromTree($this->childrenKey, $identifier, $associativeArrayTree);

        usort($materializedPath, function ($first, $second) use ($identifier) {
            return $first[$identifier] > $second[$identifier];
        });

        if ($levelKey) {
            $mpService = new MaterializedPathService($materializedPath, $pathKey, $pathSeparator);
            $materializedPath = $mpService->calculateLevels($levelKey);
        }

        if (!$idKey) {
            $materializedPath =  $creator
                ->initService($materializedPath)
                ->removeKeys([$idKey]);
        }

        return $materializedPath;
    }

    /**
     * {@inheritdoc}
     */
    public function toNestedSet($leftValueKey = 'lft', $rightValueKey = 'rgt', $idKey = null): array
    {
        $creator = new NestedSetCreator($leftValueKey, $rightValueKey);

        $associativeArrayTree = $this->nodes;
        $identifier = $idKey ? $idKey : 'id';

        if (!$this->idKey) {
            $treeService = new AssociativeArrayTreeService($this->nodes, $this->childrenKey);
            $associativeArrayTree = $treeService->identifyNodes($identifier);
        }

        $nestedSet = $creator->fromTree($this->childrenKey, $identifier, $associativeArrayTree);

        usort($nestedSet, function ($first, $second) use ($identifier) {
            return $first[$identifier] > $second[$identifier];
        });

        if (!$idKey) {
            $nestedSet = $creator
                ->initService($nestedSet)
                ->removeKeys([$idKey]);
        }

        return $nestedSet;
    }

    /**
     * {@inheritdoc}
     */
    public function toTree($childrenKey = 'children', $idKey = null): array
    {
        $creator = new AssociativeArrayTreeCreator($this->childrenKey);

        $tree = $creator->fromTree($childrenKey, $idKey, $this->nodes);

        $treeService = new AssociativeArrayTreeService($tree, $childrenKey, $idKey);

        if ($idKey && !$this->idKey) {
            $tree = $treeService->identifyNodes($idKey);
        }

        if (!$idKey && $this->idKey) {
            $tree = $treeService->removeTheField($idKey);
        }

        if ($idKey && $this->idKey && $idKey !== $this->idKey) {
            $tree = $treeService->renameTheKey($this->idKey, $idKey);
        }

        return $tree;
    }
}