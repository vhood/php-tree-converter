<?php

namespace Vhood\TreeType\Contract;

interface TypeConverter
{
    public function toTree();
    public function toAdjacencyList();
    public function toMaterializedPath();
    public function toNestedSet();
}
