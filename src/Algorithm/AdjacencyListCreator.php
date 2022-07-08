<?php

namespace Vhood\TreeType\Algorithm;

class AdjacencyListCreator extends TypeCreator
{
    private $idKey;
    private $parentIdKey;

    /**
     * @param string $idKey
     * @param string $parentIdKey
     * @return void
     */
    public function __construct($idKey, $parentIdKey)
    {
        $this->idKey = $idKey;
        $this->parentIdKey = $parentIdKey;
    }

    /**
     * {@inheritdoc}
     * @param string $idKey $nodes id key
     * @param string $parentIdKey $nodes parentId key
     * @var $recursiveParentNode not used
     */
    public function fromAdjacencyList($idKey, $parentIdKey, $nodes, $recursiveParentNode = null)
    {
        if ($this->idKey === $idKey && $this->parentIdKey === $parentIdKey) {
            return $nodes;
        }

        $service = $this->initService($nodes);

        $keyMap = [];

        if($this->idKey !== $idKey) {
            $keyMap[$this->idKey] = $idKey;
        }

        if($this->parentIdKey !== $parentIdKey) {
            $keyMap[$this->parentIdKey] = $parentIdKey;
        }

        return $service->renameKeys($keyMap);
    }

    /**
     * {@inheritdoc}
     * @var $recursiveParentNode not used
     */
    public function fromMaterializedPath($pathKey, $pathSeparator, $nodes, $recursiveParentNode = null)
    {
        $adjacencyList = [];

        foreach ($nodes as $node) {
            $pathList = array_filter(explode($pathSeparator, $node[$pathKey]));

            $node[$this->idKey] = array_pop($pathList);
            $node[$this->parentIdKey] = null;

            if (!empty($pathList)) {
                $node[$this->parentIdKey] = array_pop($pathList);
            }

            unset($node[$pathKey]);

            $adjacencyList[] = $node;
        }

        return $adjacencyList;
    }

    /**
     * {@inheritdoc}
     * @var $recursiveParentNode not used
     */
    public function fromNestedSet($leftValueKey, $rightValueKey, $idKey, $nodes, $recursiveParentNode = null)
    {
        $adjacencyList = [];

        foreach ($nodes as $node) {
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

            $node[$this->parentIdKey] = $haveParent ? $immediateParent[$this->idKey] : null;

            unset($node[$leftValueKey], $node[$rightValueKey]);

            $adjacencyList[] = $node;
        }

        return $adjacencyList;
    }

    /**
     * {@inheritdoc}
     */
    public function fromTree($childrenKey, $idKey, $nodes, $recursiveParentNode = null)
    {
        $adjacencyList = [];

        foreach ($nodes as $node) {
            $node[$this->parentIdKey] = $recursiveParentNode
                ? $recursiveParentNode[$this->idKey]
                : null;

            if (!empty($node[$childrenKey])) {
                $adjacencyList = array_merge(
                    $adjacencyList,
                    $this->fromTree($childrenKey, $idKey, $node[$childrenKey], $node)
                );
            }

            unset($node[$childrenKey]);

            $adjacencyList[] = $node;
        }

        return $adjacencyList;
    }
}
