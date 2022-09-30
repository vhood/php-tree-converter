<?php

namespace Vhood\TreeType\Algorithm;

use Vhood\TreeType\Service\AdjacencyListService;
use Vhood\TreeType\Service\MaterializedPathService;

class MaterializedPathCreator extends TypeCreator
{
    private $pathKey;
    private $pathSeparator;

    /**
     * @param string $pathKey
     * @param string $pathSeparator
     * @return void
     */
    public function __construct($pathKey, $pathSeparator)
    {
        $this->pathKey = $pathKey;
        $this->pathSeparator = $pathSeparator;
    }

    /**
     * {@inheritdoc}
     * @var $recursiveParentNode not used
     * @uses O(n²) big O notation for the runtime
     */
    public function fromAdjacencyList($idKey, $parentIdKey, $nodes, $recursiveParentNode = null)
    {
        $alService = new AdjacencyListService($nodes, $idKey, $parentIdKey);

        return array_map(function ($node) use ($alService) {
            $node[$this->pathKey] = sprintf(
                '%s%s',
                $alService->buildNodePath($node, $this->pathSeparator),
                $this->pathSeparator
            );

            return $node;
        }, $nodes);
    }

    /**
     * {@inheritdoc}
     * @param string $pathKey $nodes path key
     * @param string $pathSeparator $nodes path separator
     * @var $recursiveParentNode not used
     * @uses O(1)|O(n) big O notation for the runtime
     */
    public function fromMaterializedPath($pathKey, $pathSeparator, $nodes, $recursiveParentNode = null)
    {
        $mpService = new MaterializedPathService($nodes, $this->pathKey, $this->pathSeparator);

        if ($pathKey === $this->pathKey && $pathSeparator === $this->pathSeparator) {
            return $nodes;
        }

        return $mpService->rebuildPath($pathKey, $pathSeparator);
    }

    /**
     * {@inheritdoc}
     * @var $recursiveParentNode not used
     * @uses O(n²) big O notation for the runtime
     */
    public function fromNestedSet($leftValueKey, $rightValueKey, $idKey, $nodes, $recursiveParentNode = null)
    {
        $materializedPath = [];

        foreach ($nodes as $node) {
            $parents = array_filter($nodes, function ($currentNode) use ($node, $leftValueKey, $rightValueKey) {
                return $currentNode[$leftValueKey] < $node[$leftValueKey]
                    && $currentNode[$rightValueKey] > $node[$rightValueKey];
            });

            $parentsPath = null;
            if (!empty($parents)) {
                usort($parents, function ($first, $second) use ($leftValueKey, $rightValueKey) {
                    return $first[$leftValueKey] > $second[$leftValueKey];
                });
                $parentsPath = implode($this->pathSeparator, array_map(function ($currentNode) use ($idKey) {
                    return $currentNode[$idKey];
                }, $parents));
            }

            $node[$this->pathKey] = $parentsPath ? sprintf('%s%s', $this->pathSeparator, $parentsPath) : '';
            $node[$this->pathKey] .= sprintf('%s%s%s', $this->pathSeparator, $node[$idKey], $this->pathSeparator);

            unset($node[$leftValueKey], $node[$rightValueKey]);

            $materializedPath[] = $node;
        }

        return $materializedPath;
    }

    /**
     * {@inheritdoc}
     * @uses O(n) big O notation for the runtime
     */
    public function fromTree($childrenKey, $idKey, $nodes, $recursiveParentNode = null)
    {
        $materializedPath = [];

        $nodesToIterate = $recursiveParentNode
            ? $recursiveParentNode[$childrenKey]
            : $nodes;

        foreach ($nodesToIterate as $node) {
            $node[$this->pathKey] = $recursiveParentNode
                ? sprintf('%s%s%s', $recursiveParentNode[$this->pathKey], $this->pathSeparator, $node[$idKey])
                : sprintf($node[$idKey]);

            if (!empty($node[$childrenKey])) {
                $materializedPath = array_merge(
                    $materializedPath,
                    $this->fromTree($childrenKey, $idKey, $nodes, $node)
                );
            }

            unset($node[$childrenKey]);

            $materializedPath[] = $node;
        }

        return $materializedPath;
    }
}
