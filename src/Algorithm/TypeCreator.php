<?php

namespace Vhood\TreeType\Algorithm;

use Vhood\TreeType\Service\NodeService;
use Vhood\TreeType\Contract\ConvertingAlgorithm;
use Vhood\TreeType\Service\FlatNodesService;

abstract class TypeCreator implements ConvertingAlgorithm
{
    /**
     * @param array $nodes
     * @return FlatNodesService
     */
    public function initNodesService(array $nodes)
    {
        return new FlatNodesService($nodes);
    }

    /**
     * @param array $node
     * @return NodeService
     */
    public function initNodeService(array $node)
    {
        return new NodeService($node);
    }
}
