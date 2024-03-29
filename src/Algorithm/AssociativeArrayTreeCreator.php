<?php

namespace Vhood\TreeType\Algorithm;

use Vhood\TreeType\Service\AssociativeArrayTreeService;

class AssociativeArrayTreeCreator extends TypeCreator
{
    private $childrenKey;

    /**
     * @param string $childrenKey
     * @return void
     */
    public function __construct($childrenKey)
    {
        $this->childrenKey = $childrenKey;
    }

    /**
     * {@inheritdoc}
     * @uses O(n+m) big O notation for the runtime
     */
    public function fromAdjacencyList($idKey, $parentIdKey, $nodes, $recursiveParentNode = null)
    {
        $associativeArrayTree = [];

        foreach ($nodes as $node) {
            $thisNodeHasNoParent = !($recursiveParentNode || $node[$parentIdKey]);

            $isRequestedChild = $recursiveParentNode
                && $node[$parentIdKey]
                && $node[$parentIdKey] == $recursiveParentNode[$idKey];

            if ($thisNodeHasNoParent || $isRequestedChild) {
                $node[$this->childrenKey] = $this->fromAdjacencyList($idKey, $parentIdKey, $nodes, $node);

                unset($node[$parentIdKey]);

                $associativeArrayTree[] = $node;
            }
        }

        return $associativeArrayTree;
    }

    /**
     * {@inheritdoc}
     * @uses O(n+m) big O notation for the runtime
     */
    public function fromMaterializedPath($pathKey, $pathSeparator, $nodes, $recursiveParentNode = null)
    {
        $associativeArrayTree = [];

        foreach ($nodes as $node) {
            if ($recursiveParentNode && $node[$pathKey] === $recursiveParentNode[$pathKey]) {
                continue;
            }

            $isRoot = !$recursiveParentNode
                && count(array_filter(explode($pathSeparator, $node[$pathKey]))) < 2;

            $parentPath = $this->initNodeService($node)->findParentsPath($pathKey, $pathSeparator);

            $isRequestedChild = $recursiveParentNode
                && !$isRoot
                && $parentPath === $recursiveParentNode[$pathKey];

            if ($isRoot || $isRequestedChild) {
                $node[$this->childrenKey] = $this->fromMaterializedPath($pathKey, $pathSeparator, $nodes, $node);

                unset($node[$pathKey]);

                $associativeArrayTree[] = $node;
            }
        }

        return $associativeArrayTree;
    }

    /**
     * {@inheritdoc}
     * @uses O(n²) big O notation for the runtime
     */
    public function fromNestedSet($leftValueKey, $rightValueKey, $idKey, $nodes, $recursiveParentNode = null)
    {
        $associativeArrayTree = [];

        $nodesToIterate = $nodes;

        foreach ($nodesToIterate as $index => $node) {
            $parents = array_filter($nodes, function ($currentNode) use ($node, $leftValueKey, $rightValueKey) {
                return $currentNode[$leftValueKey] < $node[$leftValueKey]
                    && $currentNode[$rightValueKey] > $node[$rightValueKey];
            });

            $haveParent = !empty($parents);
            $immediateParent = null;

            if ($haveParent) {
                usort($parents, function ($first, $second) use ($leftValueKey) {
                    return $first[$leftValueKey] < $second[$leftValueKey];
                });
                $immediateParent = array_shift($parents);
            }

            $isRequestedChild = $recursiveParentNode
                && $immediateParent
                && $immediateParent[$leftValueKey] === $recursiveParentNode[$leftValueKey];

            if (!$haveParent && !$recursiveParentNode || $isRequestedChild) {
                $nodeToSave = $node;

                unset($nodesToIterate[$index]);

                $haveChildren = $nodeToSave[$rightValueKey] - $nodeToSave[$leftValueKey] > 1;

                $nodeToSave[$this->childrenKey] = $haveChildren
                    ? $this->fromNestedSet($leftValueKey, $rightValueKey, $idKey, $nodes, $nodeToSave)
                    : [];

                unset($nodeToSave[$leftValueKey], $nodeToSave[$rightValueKey]);

                $associativeArrayTree[] = $nodeToSave;

                continue;
            }
        }

        return $associativeArrayTree;
    }

    /**
     * {@inheritdoc}
     * @param string $childrenKey $nodes children key
     * @var $idKey not used
     * @var $recursiveParentNode not used
     * @uses O(1) big O notation for the runtime
     */
    public function fromTree($childrenKey, $idKey, $nodes, $recursiveParentNode = null)
    {
        $treeService = new AssociativeArrayTreeService($nodes, $this->childrenKey, null);

        return $this->childrenKey === $childrenKey
            ? $nodes
            : $treeService->renameTheKey($this->childrenKey, $childrenKey);
    }
}
