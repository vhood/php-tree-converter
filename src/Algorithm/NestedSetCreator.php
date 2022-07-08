<?php

namespace Vhood\TreeType\Algorithm;

use Vhood\TreeType\Service\AdjacencyListService;
use Vhood\TreeType\Service\MaterializedPathService;

class NestedSetCreator extends TypeCreator
{
    private $leftValueKey;
    private $rightValueKey;

    /**
     * @param string $leftValueKey
     * @param string $rightValueKey
     * @return void
     */
    public function __construct($leftValueKey, $rightValueKey)
    {
        $this->leftValueKey = $leftValueKey;
        $this->rightValueKey = $rightValueKey;
    }

    /**
     * {@inheritdoc}
     */
    public function fromAdjacencyList($idKey, $parentIdKey, $nodes, $recursiveParentNode = null)
    {
        $nestedSet = [];

        $left = $recursiveParentNode ? $recursiveParentNode[$this->leftValueKey] + 1 : 1;

        $alService = new AdjacencyListService($nodes, $idKey, $parentIdKey);

        $nodesToIterate = $nodes;

        if ($recursiveParentNode) {
            $children = array_filter($nodes, function ($currentNode) use ($recursiveParentNode, $idKey, $parentIdKey) {
                return $currentNode[$parentIdKey] === $recursiveParentNode[$idKey];
            });

            $nodesToIterate = $children;
        }

        foreach ($nodesToIterate as $node) {
            $isFirstLevelNode = !$node[$parentIdKey];

            if (!$isFirstLevelNode && !$recursiveParentNode) {
                continue;
            }

            $childrenLength = $alService->calculateChildren($node) * 2;

            $right = $left + $childrenLength + 1;

            $node[$this->leftValueKey] = $left;
            $node[$this->rightValueKey] = $right;

            $nestedSet[] = $node;

            $left = $right + 1;

            if ($childrenLength) {
                $nestedSet = array_merge($nestedSet, $this->fromAdjacencyList($idKey, $parentIdKey, $nodes, $node));
            }
        }

        return $nestedSet;
    }

    /**
     * {@inheritdoc}
     */
    public function fromMaterializedPath($pathKey, $pathSeparator, $nodes, $recursiveParentNode = null)
    {
        $nestedSet = [];

        $left = $recursiveParentNode ? $recursiveParentNode[$this->leftValueKey] + 1 : 1;

        $mpService = new MaterializedPathService($nodes, $pathKey, $pathSeparator);

        $nodesToIterate = $nodes;

        if ($recursiveParentNode) {
            $children = array_filter(
                $nodes,
                function ($node) use ($recursiveParentNode, $pathKey, $pathSeparator) {
                    $parentPath = $this->initNodeService($node)->findParentsPath($pathKey, $pathSeparator);

                    return $recursiveParentNode[$pathKey] === $parentPath
                        && $recursiveParentNode[$pathKey] !== $node[$pathKey];
                }
            );

            $nodesToIterate = $children;
        }

        foreach ($nodesToIterate as $node) {
            $isFirstLevelNode = count(array_filter(explode($pathSeparator, $node[$pathKey]))) < 2;

            if (!$isFirstLevelNode && !$recursiveParentNode) {
                continue;
            }

            $childrenLength = $mpService->calculateChildren($node) * 2;

            $right = $left + $childrenLength + 1;

            $node[$this->leftValueKey] = $left;
            $node[$this->rightValueKey] = $right;

            $nestedSet[] = $node;


            if ($childrenLength) {
                $nestedSet = array_merge($nestedSet, $this->fromMaterializedPath($pathKey, $pathSeparator, $nodes, $node));
            }

            $left = $right + 1;
        }

        return $nestedSet;
    }

    /**
     * {@inheritdoc}
     * @param string $leftValueKey $nodes leftValue key
     * @param string $rightValueKey $nodes rightValue key
     * @var $idKey not used
     * @var $recursiveParentNode not used
     */
    public function fromNestedSet($leftValueKey, $rightValueKey, $idKey, $nodes, $recursiveParentNode = null)
    {
        return $this
            ->initNodesService($nodes)
            ->renameKeys([
                $this->leftValueKey => $leftValueKey,
                $this->rightValueKey => $rightValueKey
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function fromTree($childrenKey, $idKey, $nodes, $recursiveParentNode = null)
    {
        $nestedSet = [];
        $leftToRightLength = 4;

        $left = $recursiveParentNode ? $recursiveParentNode[$this->leftValueKey] + 1 : 1;

        $nodesToIterate = $recursiveParentNode
            ? $recursiveParentNode[$childrenKey]
            : $nodes;

        foreach ($nodesToIterate as $node) {
            $node[$this->leftValueKey] = $left;

            $right = (count($node[$childrenKey], COUNT_RECURSIVE) / $leftToRightLength * 2) + $left + 1;

            $node[$this->rightValueKey] = $right;

            if (!empty($node[$childrenKey])) {
                $nestedSet = array_merge($nestedSet, $this->fromTree($childrenKey, $idKey, $nodes, $node));
            }

            unset($node[$childrenKey]);

            $nestedSet[] = $node;

            $left = $right + 1;
        }

        return $nestedSet;
    }
}
