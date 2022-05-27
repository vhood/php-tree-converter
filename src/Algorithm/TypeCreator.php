<?php

namespace Vhood\TreeType\Algorithm;

use Vhood\TreeType\Contract\ConvertionAlgorithm;
use Vhood\TreeType\Service\DataService;

abstract class TypeCreator implements ConvertionAlgorithm
{
    /**
     * @param mixed $nodes
     * @return DataService
     */
    public function initService($nodes)
    {
        return new DataService($nodes);
    }
}
