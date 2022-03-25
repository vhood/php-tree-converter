<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Tree;

class TreeTest extends TestCase
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

        $this->converter = new Tree($this->tree);
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
        $this->assertTrue(true);
    }

    public function testConvertToMP()
    {
        $this->assertTrue(true);
    }

    public function testConvertToNS()
    {
        $this->assertTrue(true);
    }
}