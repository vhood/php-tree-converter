<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\AjacencyList;

class AjacencyListTest extends TestCase
{
    protected $tree;
    protected $al;
    protected $mp;
    protected $ns;
    protected $converter;

    public function setUp()
    {
        $this->tree = require 'data/tree.php';
        $this->al = require 'data/adjacency-list.php';
        $this->mp = require 'data/materialized-path.php';
        $this->ns = require 'data/nested-set.php';

        $this->converter = new AjacencyList($this->al);
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
            json_encode($this->converter->toAjacencyList())
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
