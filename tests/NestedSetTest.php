<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class NestedSetTest extends TestCase
{
    protected $data;
    protected $tree;

    public function setUp()
    {
        $this->data = require_once 'data/nested-set.php';
        $this->tree = require_once 'data/tree.php';
    }

    public function testConvertFromTree()
    {
        return true;
    }

    public function testConvertToTree()
    {
        return true;
    }

    public function testConvertToAL()
    {
        return true;
    }

    public function testConvertToMP()
    {
        return true;
    }

    public function testConvertToNS()
    {
        return true;
    }
}
