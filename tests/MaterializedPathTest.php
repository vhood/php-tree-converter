<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\MaterializedPath;

class MaterializedPathTest extends TestCase
{
    protected $tree;
    protected $al;
    protected $mp;
    protected $ns;
    protected $converter;

    public function setUp()
    {
        $this->tree = require 'data/tree/exemplar.php';

        $data = require 'data/num-based-nodes.php';

        $this->al = array_map(function($node) {
            $al = array_intersect_key($node, ['id' => '', 'name' => '', 'parent_id' => '']);
            uksort($al, function($k) { return $k !== 'id'; });
            return $al;
        }, $data);

        $this->mp = array_map(function($node) {
            $mp = array_intersect_key($node, ['id' => '', 'name' => '', 'path' => '']);
            uksort($mp, function($k) { return $k !== 'id'; });
            return $mp;
        }, $data);

        $this->ns = array_map(function($node) {
            $ns = array_intersect_key($node, ['id' => '', 'name' => '', 'lft' => '', 'rgt' => '']);
            uksort($ns, function($k) { return $k !== 'id'; });
            return $ns;
        }, $data);

        $this->converter = new MaterializedPath($this->mp);
    }

    public function testConvertToTree()
    {
        $this->assertSame(
            json_encode($this->tree),
            json_encode($this->converter->toTree())
        );
    }

    public function testConvertToAL()
    {
        $this->assertSame(
            json_encode($this->al),
            json_encode($this->converter->toAdjacencyList('id', 'parent_id', true, null))
        );
    }

    public function testConvertToMP()
    {
        $this->assertSame(
            json_encode($this->mp),
            json_encode($this->converter->toMaterializedPath())
        );
    }

    public function testConvertToNS()
    {
        $this->assertSame(
            json_encode($this->ns),
            json_encode($this->converter->toNestedSet())
        );
    }
}
