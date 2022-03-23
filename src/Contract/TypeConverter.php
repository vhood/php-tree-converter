<?php

namespace Vhood\TreeType\Contract;

interface TypeConverter
{
    public function toTree();
    public function toAjacencyList();
    public function toMaterializedPath();
    public function toNestedSet();
}
