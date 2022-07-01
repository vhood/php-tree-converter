<?php

namespace Vhood\TreeType\Algorithm;

use Vhood\TreeType\Contract\ConvertionAlgorithm;
use Vhood\TreeType\Service\FlatNodesService;

abstract class TypeCreator implements ConvertionAlgorithm
{
    /**
     * @param mixed $nodes
     * @return FlatNodesService
     */
    public function initService($nodes)
    {
        return new FlatNodesService($nodes);
    }
}
