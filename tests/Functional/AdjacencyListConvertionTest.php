<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Converter;
use Vhood\TreeType\Type\AdjacencyList;

class AdjacencyListConvertionTest extends TestCase
{
    protected $tree;
    protected $al;
    protected $mp;
    protected $ns;

    /**
     * @var Converter
     */
    protected $converter;

    public function setUp()
    {
        $data = require __DIR__ . '/../data/num-based-nodes.php';

        $this->tree = require __DIR__ . '/../data/tree/exemplar.php';

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

        $this->converter = new Converter(new AdjacencyList($this->al));
    }

    public function testConvertToTree()
    {
        $this->assertSame(
            json_encode($this->tree),
            json_encode($this->converter->toTree())
        );
    }

    public function testConvertToTreeAndRenameIdField()
    {
        $al = [
            [
                'id' => 1,
                'name' => 'node1',
                'parent_id' => null,
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'parent_id' => 1,
            ],
        ];

        $tree = [
            [
                'identifier' => 1,
                'name' => 'node1',
                'children' => [
                    [
                        'identifier' => 2,
                        'name' => 'node2',
                        'children' => [],
                    ],
                ],
            ],
        ];

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($tree),
            json_encode($converter->toTree('children', 'identifier'))
        );
    }

    public function testConvertToAL()
    {
        $this->assertSame(
            json_encode($this->al),
            json_encode($this->converter->toAdjacencyList())
        );
    }

    public function testRenameALFields()
    {
        $actual = [
            [
                'id' => 1,
                'name' => 'node1',
                'parent_id' => null,
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'parent_id' => 1,
            ],
        ];

        $expected = [
            [
                'identifier' => 1,
                'name' => 'node1',
                'parentId' => null,
            ],
            [
                'identifier' => 2,
                'name' => 'node2',
                'parentId' => 1,
            ],
        ];

        $converter = new Converter(new AdjacencyList($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toAdjacencyList('identifier', 'parentId'))
        );
    }

    public function testConvertToMP()
    {
        $this->assertSame(
            json_encode($this->mp),
            json_encode($this->converter->toMaterializedPath())
        );
    }

    public function testConvertToMPAndCalculateLevels()
    {
        $al = [
            [
                'id' => 1,
                'name' => 'node1',
                'parent_id' => null,
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'parent_id' => 3,
            ],
            [
                'id' => 3,
                'name' => 'node3',
                'parent_id' => 1,
            ],
        ];

        $mp = [
            [
                'id' => 1,
                'name' => 'node1',
                'path' => '/1/',
                'level' => 1,
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'path' => '/1/3/2/',
                'level' => 3,
            ],
            [
                'id' => 3,
                'name' => 'node3',
                'path' => '/1/3/',
                'level' => 2,
            ],
        ];

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', 'level'))
        );
    }

    public function testConvertToMPAndRenameIdField()
    {
        $al = [
            [
                'id' => 1,
                'name' => 'node1',
                'parent_id' => null,
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'parent_id' => 3,
            ],
            [
                'id' => 3,
                'name' => 'node3',
                'parent_id' => 1,
            ],
        ];

        $mp = [
            [
                'identifier' => 1,
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'identifier' => 2,
                'name' => 'node2',
                'path' => '/1/3/2/',
            ],
            [
                'identifier' => 3,
                'name' => 'node3',
                'path' => '/1/3/',
            ],
        ];

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', null, 'identifier'))
        );
    }

    public function testConvertToNS()
    {
        $this->assertSame(
            json_encode($this->ns),
            json_encode($this->converter->toNestedSet())
        );
    }

    public function testConvertToNSAndRenameIdField()
    {
        $al = [
            [
                'id' => 1,
                'name' => 'node1',
                'parent_id' => null,
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'parent_id' => 3,
            ],
            [
                'id' => 3,
                'name' => 'node3',
                'parent_id' => 1,
            ],
        ];

        $ns = [
            [
                'identifier' => 1,
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 6,
            ],
            [
                'identifier' => 2,
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
            [
                'identifier' => 3,
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 5,
            ],
        ];

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet('lft', 'rgt', 'identifier'))
        );
    }
}
