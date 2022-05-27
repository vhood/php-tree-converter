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
    public function fromAdjacencyList($idKey, $parentIdKey, $nodes, $recursiveParentNode = null): array
    {
        $nestedSet = [];

        $left = $recursiveParentNode ? $recursiveParentNode[$this->leftValueKey] + 1 : 1;

        $alService = new AdjacencyListService($nodes, $idKey, $parentIdKey);

        foreach ($nodes as $node) {
            $isFirstLevelNode = !$node[$parentIdKey];

            if (!$isFirstLevelNode && !$recursiveParentNode) {
                continue;
            }

            $childrenLength = $alService->calculateChildren($node) * 2;

            $right = $left + $childrenLength + 1;

            $node[$this->leftValueKey] = $left;
            $node[$this->rightValueKey] = $right;

            unset($node[$parentIdKey]);

            $nestedSet[] = $node;

            $left = $right + 1;

            if ($childrenLength) {
                $children = array_filter($nodes, function ($currentNode) use ($node, $idKey, $parentIdKey) {
                    return $currentNode[$parentIdKey] === $node[$idKey];
                });

                $nestedSet = array_merge($nestedSet, $this->fromAdjacencyList($idKey, $parentIdKey, $children, $node));
            }
        }

        return $nestedSet;
    }

    /**
     * {@inheritdoc}
     */
    public function fromMaterializedPath($pathKey, $pathSeparator, $nodes, $recursiveParentNode = null): array
    {
        $nestedSet = [];

        $left = $recursiveParentNode ? $recursiveParentNode[$this->leftValueKey] + 1 : 1;

        $mpService = new MaterializedPathService($nodes, $pathKey, $pathSeparator);

        foreach ($nodes as $node) {
            $isFirstLevelNode = count(array_filter(explode($pathSeparator, $node[$pathKey]))) < 2;

            if (!$isFirstLevelNode && !$recursiveParentNode) {
                continue;
            }

            $childrenLength = $mpService->calculateChildren($node) * 2;

            $right = $left + $childrenLength + 1;

            $node[$this->leftValueKey] = $left;
            $node[$this->rightValueKey] = $right;

            $nodePath = $node[$pathKey];
            $nestedSet[] = $node;

            if ($childrenLength) {
                $children = array_filter($this->data, function ($currentNode) use ($nodePath, $pathKey, $pathSeparator) {
                    $parentPath = preg_replace(
                        sprintf(
                            "/(.+%s)\d+%s$/m",
                            preg_quote($pathSeparator, '/'),
                            preg_quote($pathSeparator, '/')
                        ),
                        "$1",
                        $currentNode[$pathKey]
                    );

                    return $nodePath !== $currentNode[$pathKey] && $nodePath === $parentPath;
                });

                $nestedSet = array_merge($nestedSet, $this->fromMaterializedPath($pathKey, $pathSeparator, $children, $node));
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
    public function fromNestedSet($leftValueKey, $rightValueKey, $idKey, $nodes, $recursiveParentNode = null): array
    {
        return $this
            ->initService($nodes)
            ->renameKeys([
                $this->leftValueKey => $leftValueKey,
                $this->rightValueKey => $rightValueKey
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function fromTree($childrenKey, $idKey, $nodes, $recursiveParentNode = null): array
    {
        $nestedSet = [];
        $leftToRightLength = 4;

        $left = $recursiveParentNode ? $recursiveParentNode[$this->leftValueKey] : 1;

        foreach ($nodes as $node) {
            $node[$this->leftValueKey] = $left;
            $right = (count($node[$childrenKey], COUNT_RECURSIVE) / $leftToRightLength * 2) + $left + 1;
            $node[$this->rightValueKey] = $right;

            if (!empty($node[$childrenKey])) {
                $this->fromTree($childrenKey, $idKey, $node[$childrenKey], $node);
            }
            unset($node[$childrenKey]);

            $nestedSet[] = $node;

            $left = $right + 1;
        }

        return $nestedSet;
    }
}
