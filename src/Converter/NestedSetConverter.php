<?php

namespace Vhood\TreeType\Converter;

use Vhood\TreeType\Algorithm\AdjacencyListCreator;
use Vhood\TreeType\Algorithm\AssociativeArrayTreeCreator;
use Vhood\TreeType\Algorithm\MaterializedPathCreator;
use Vhood\TreeType\Algorithm\NestedSetCreator;
use Vhood\TreeType\Contract\TypeConverter;
use Vhood\TreeType\Service\MaterializedPathService;
use Vhood\TreeType\Service\NestedSetService;

class NestedSetConverter implements TypeConverter
{
    private $nodes;
    private $leftValueKey;
    private $rightValueKey;
    private $idKey;

    /**
     * @param array $nodes
     * @param string $leftValueKey
     * @param string $rightValueKey
     * @param null|string $idKey
     * @return void
     */
    public function __construct($nodes, $leftValueKey, $rightValueKey, $idKey = null)
    {
        $this->leftValueKey = $leftValueKey;
        $this->rightValueKey = $rightValueKey;
        $this->idKey = $idKey;
        $this->nodes = $nodes;
    }

    /**
     * {@inheritdoc}
     */
    public function toAdjacencyList($idKey = 'id', $parentIdKey = 'parent_id')
    {
        $creator = new AdjacencyListCreator($idKey, $parentIdKey);

        $nestedSet = $this->nodes;

        if (!$this->idKey) {
            $nsService = new NestedSetService($nestedSet, $this->leftValueKey, $this->rightValueKey);
            $nestedSet = $nsService->identifyNodes($idKey);
        }

        if ($this->idKey && $this->idKey !== $idKey) {
            $nestedSet = $creator
                ->initService($nestedSet)
                ->renameKeys([$this->idKey => $idKey]);
        }

        $al = $creator->fromNestedSet($this->leftValueKey, $this->rightValueKey, $idKey, $nestedSet);

        uasort($al, function ($firstNode, $secondNode) use ($idKey) {
            return $firstNode[$idKey] > $secondNode[$idKey];
        });

        return array_values($al);
    }

    /**
     * {@inheritdoc}
     */
    public function toMaterializedPath($pathKey = 'path', $pathSeparator = '/', $levelKey = null, $idKey = null)
    {
        $creator = new MaterializedPathCreator($pathKey, $pathSeparator);

        $nestedSet = $this->nodes;

        $identifier = $idKey;

        if (!$this->idKey) {
            $identifier = 'id';
            $nsService = new NestedSetService($nestedSet, $this->leftValueKey, $this->rightValueKey);
            $nestedSet = $nsService->identifyNodes($identifier);
        }

        if ($idKey && $idKey !== $identifier) {
            $nestedSet = $creator
                ->initService($nestedSet)
                ->renameKeys([$identifier => $idKey]);

            $identifier = $idKey;
        }

        $materializedPath = $creator->fromNestedSet($this->leftValueKey, $this->rightValueKey, $identifier, $nestedSet);

        if (!$idKey) {
            $materializedPath = $creator
                ->initService($materializedPath)
                ->removeKeys([$identifier]);
        }

        if ($levelKey) {
            $mpService = new MaterializedPathService($materializedPath, $pathKey, $pathSeparator);
            $materializedPath = $mpService->calculateLevels($levelKey);
        }

        return $materializedPath;
    }

    /**
     * {@inheritdoc}
     */
    public function toNestedSet($leftValueKey = 'lft', $rightValueKey = 'rgt', $idKey = null)
    {
        $creator = new NestedSetCreator($this->leftValueKey, $this->rightValueKey);

        $nestedSet = $creator->fromNestedSet($leftValueKey, $rightValueKey, $idKey, $this->nodes);

        if ($idKey && !$this->idKey) {
            $nsService = new NestedSetService($nestedSet, $leftValueKey, $rightValueKey);
            $nestedSet = $nsService->identifyNodes($idKey);
        }

        if (!$idKey && $this->idKey) {
            $nestedSet = $creator
                ->initService($nestedSet)
                ->removeKeys([$this->idKey]);
        }

        if ($idKey && $this->idKey && $idKey !== $this->idKey) {
            $nestedSet = $creator
                ->initService($nestedSet)
                ->renameKeys([$this->idKey => $idKey]);
        }

        return $nestedSet;
    }

    /**
     * {@inheritdoc}
     */
    public function toTree($childrenKey = 'children', $idKey = null)
    {
        $creator = new AssociativeArrayTreeCreator($childrenKey);

        $nodes = $this->nodes;

        if ($idKey && !$this->idKey) {
            $nsService = new NestedSetService($nodes, $this->leftValueKey, $this->rightValueKey);
            $nodes = $nsService->identifyNodes($idKey);
        }

        if ($idKey && $this->idKey && $idKey !== $this->idKey) {
            $nodes = $creator->initService($nodes)->renameKeys([$this->idKey => $idKey]);
        }

        return $creator->fromNestedSet($this->leftValueKey, $this->rightValueKey, $idKey, $nodes);
    }
}
