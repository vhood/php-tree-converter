<?php

namespace Vhood\TreeType\Service;

use Vhood\TreeType\Exception\InvalidStructureException;

class AssociativeArrayTreeService
{
    private $nodes;
    private $childrenKey;

    /**
     * @param array $nodes
     * @param string $childrenKey
     * @return void
     */
    public function __construct($nodes, $childrenKey)
    {
        $this->childrenKey = $childrenKey;
        $this->nodes = $nodes;
    }

    /**
     * @param string $idKey
     * @param null|array $recursiveNodes
     * @return void
     * @throws InvalidStructureException
     */
    public function validateIdField($idKey, $recursiveNodes = null)
    {
        $nodes = $recursiveNodes ? $recursiveNodes : $this->nodes;

        foreach ($nodes as $node) {
            if (!array_key_exists($idKey, $node)) {
                throw new InvalidStructureException(
                    sprintf(
                        "Node %s has no id field",
                        json_encode(array_diff_key($node, [$this->childrenKey => true]))
                    )
                );
            }

            if (isset($node[$this->childrenKey]) && !empty($node[$this->childrenKey])) {
                $this->validateIdField($idKey, $node[$this->childrenKey]);
            }
        }
    }

    /**
     * @param string $idKey
     * @param null|string $recursiveParentNode
     * @return array
     */
    public function identifyNodes($idKey, $recursiveParentNode = null)
    {
        $nodes = $recursiveParentNode ? $recursiveParentNode[$this->childrenKey] : $this->nodes;
        $id = $recursiveParentNode ? $recursiveParentNode[$idKey] + 1 : 1;

        $tree = [];
        foreach ($nodes as $node) {
            $node = array_merge([$idKey => $id], $node);

            if (!empty($node[$this->childrenKey])) {
                $node[$this->childrenKey] = $this->identifyNodes($idKey, $node);
            }

            $tree[] = $node;
            $id++;
        }

        return $tree;
    }

    /**
     * @param string $fieldKey
     * @param null|string $recursiveParentNode
     * @return array
     */
    public function removeTheField($fieldKey, $recursiveParentNode = null)
    {
        $nodes = $recursiveParentNode ? $recursiveParentNode[$this->childrenKey] : $this->nodes;

        $tree = [];
        foreach ($nodes as $node) {
            unset($node[$fieldKey]);

            if (!empty($node[$this->childrenKey])) {
                $node[$this->childrenKey] = $this->removeTheField($fieldKey, $node);
            }

            $tree[] = $node;
        }

        return $tree;
    }

    /**
     * @param string $currentKey
     * @param string $newKey
     * @return array
     */
    public function renameTheKey($currentKey, $newKey)
    {
        $jsonNodes = json_encode($this->nodes);

        return json_decode(
            str_replace(
                sprintf('"%s":', $currentKey),
                sprintf('"%s":', $newKey),
                $jsonNodes
            ),
            true
        );
    }
}
