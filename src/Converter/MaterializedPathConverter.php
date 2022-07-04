<?php

namespace Vhood\TreeType;

use Vhood\TreeType\Algorithm\AdjacencyListCreator;
use Vhood\TreeType\Algorithm\AssociativeArrayTreeCreator;
use Vhood\TreeType\Algorithm\MaterializedPathCreator;
use Vhood\TreeType\Algorithm\NestedSetCreator;
use Vhood\TreeType\Contract\TypeConverter;
use Vhood\TreeType\Service\MaterializedPathService;

class MaterializedPathConverter implements TypeConverter
{
    private $nodes;
    private $pathKey;
    private $pathSeparator;
    private $levelKey;
    private $idKey;

    /**
     * @param array $nodes
     * @param string $pathKey
     * @param string $pathSeparator
     * @param null|string $levelKey
     * @param null|string $idKey
     * @return void
     */
    public function __construct($nodes, $pathKey, $pathSeparator, $levelKey = null, $idKey = null)
    {
        $this->pathKey = $pathKey;
        $this->pathSeparator = $pathSeparator;
        $this->nodes = $nodes;
        $this->idKey = $idKey;
        $this->levelKey = $levelKey;
    }

    /**
     * {@inheritdoc}
     */
    public function toAdjacencyList($idKey = 'id', $parentIdKey = 'parent_id')
    {
        $creator = new AdjacencyListCreator($idKey, $parentIdKey);

        $adjacencyList = $creator->fromMaterializedPath($this->pathKey, $this->pathSeparator, $this->nodes);

        $areIdentifiersInteger = empty(array_filter($this->nodes, function ($node) {
            return !is_integer(str_replace($this->pathSeparator, $node[$this->pathKey], ''));
        }));

        if ($areIdentifiersInteger) {
            $adjacencyList = array_map(
                function ($node) use ($idKey, $parentIdKey) {
                    $node[$idKey] = (int)$node[$idKey];
                    $node[$parentIdKey] = (int)$node[$parentIdKey];
                    return $node;
                },
                $adjacencyList
            );
        }

        return $adjacencyList;
    }

    /**
     * {@inheritdoc}
     */
    public function toMaterializedPath($pathKey = 'path', $pathSeparator = '/', $levelKey = null, $idKey = null)
    {
        $creator = new MaterializedPathCreator($this->pathKey, $this->pathSeparator);

        $materializedPath = $creator->fromMaterializedPath($pathKey, $pathSeparator, $this->nodes);

        if ($levelKey && !$this->levelKey) {
            $mpService = new MaterializedPathService($materializedPath, $this->pathKey, $this->pathSeparator);
            $materializedPath = $mpService->calculateLevels($levelKey);
        }

        if ($levelKey && $this->levelKey && $levelKey !== $this->levelKey) {
            $materializedPath = $creator
                ->initService($materializedPath)
                ->renameKeys([$this->levelKey => $levelKey]);
        }

        if ($idKey && !$this->idKey) {
            $mpService = new MaterializedPathService($materializedPath, $this->pathKey, $this->pathSeparator);
            $materializedPath = $mpService->identifyNodes($idKey);
        }

        if ($idKey && $this->idKey && $idKey !== $this->idKey) {
            $materializedPath = $creator
                ->initService($materializedPath)
                ->renameKeys([$this->idKey => $idKey]);
        }

        return $materializedPath;
    }

    /**
     * {@inheritdoc}
     */
    public function toNestedSet($leftValueKey = 'lft', $rightValueKey = 'rgt', $idKey = null)
    {
        $creator = new NestedSetCreator($leftValueKey, $rightValueKey);

        $materializedPath = $this->nodes;

        if (!$this->idKey) {
            $this->idKey = 'id';
            $mpService = new MaterializedPathService($materializedPath, $this->pathKey, $this->pathSeparator);
            $materializedPath = $mpService->identifyNodes($this->idKey);
        }

        $nestedSet = $creator->fromMaterializedPath($this->pathKey, $this->pathSeparator, $materializedPath);

        uasort($nestedSet, function ($firstNode, $secondNode) {
            return $firstNode[$this->idKey] > $secondNode[$this->idKey];
        });

        if ($idKey && $idKey !== $this->idKey) {
            $nestedSet = $creator
                ->initService($nestedSet)
                ->renameKeys([$this->idKey => $idKey]);
        }

        $keysToRemove = [$this->pathKey];
        if (!$idKey) {
            $keysToRemove[] = $this->idKey;
        }

        $nestedSet = $creator
            ->initService($nestedSet)
            ->removeKeys($keysToRemove);

        return $nestedSet;
    }

    /**
     * {@inheritdoc}
     */
    public function toTree($childrenKey = 'children', $idKey = null)
    {
        $creator = new AssociativeArrayTreeCreator($childrenKey);

        return $creator->fromMaterializedPath($this->pathKey, $this->pathSeparator, $this->nodes);
    }
}
