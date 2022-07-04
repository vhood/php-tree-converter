<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Converter;
use Vhood\TreeType\Type\MaterializedPath;

class MaterializedPathConvertionTest extends TestCase
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

        $this->converter = new Converter(new MaterializedPath($this->mp));
    }

    public function testConvertToTree()
    {
        $this->assertSame(
            json_encode($this->tree),
            json_encode($this->converter->toTree())
        );
    }

    public function testConvertToTreeAndIdentifyNodes()
    {
        $mp = [
            [
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'name' => 'node2',
                'path' => '/1/3/2/',
            ],
            [
                'name' => 'node3',
                'path' => '/1/3/',
            ],
        ];

        $tree = [
            [
                'id' => 1,
                'name' => 'node1',
                'children' => [
                    [
                        'id' => 3,
                        'name' => 'node3',
                        'children' => [
                            [
                                'id' => 2,
                                'name' => 'node2',
                                'children' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $converter = new Converter(new MaterializedPath($mp));

        $this->assertSame(
            json_encode($tree),
            json_encode($converter->toTree('children', 'id'))
        );
    }

    public function testConvertToAL()
    {
        $this->assertSame(
            json_encode($this->al),
            json_encode($this->converter->toAdjacencyList())
        );
    }

    public function testConvertToALAndRenameExistedIdField()
    {
        $mp = [
            [
                'id' => 1,
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'path' => '/1/3/2/',
            ],
            [
                'id' => 3,
                'name' => 'node3',
                'path' => '/1/3/',
            ],
        ];

        $al = [
            [
                'identifier' => 1,
                'name' => 'node1',
                'parent_id' => null,
            ],
            [
                'identifier' => 2,
                'name' => 'node2',
                'parent_id' => 3,
            ],
            [
                'identifier' => 3,
                'name' => 'node3',
                'parent_id' => 1,
            ],
        ];

        $converter = new Converter(new MaterializedPath($mp, 'path', '/', null, 'id'));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList('identifier'))
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
        $actual = [
            [
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'name' => 'node2',
                'path' => '/1/3/2/',
            ],
            [
                'name' => 'node3',
                'path' => '/1/3/',
            ],
        ];

        $expected = [
            [
                'name' => 'node1',
                'path' => '/1/',
                'level' => 1,
            ],
            [
                'name' => 'node2',
                'path' => '/1/3/2/',
                'level' => 3,
            ],
            [
                'name' => 'node3',
                'path' => '/1/3/',
                'level' => 2,
            ],
        ];

        $converter = new Converter(new MaterializedPath($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toMaterializedPath('path', '/', 'level'))
        );
    }

    public function testConvertToMPAndIdentifyNumBasedNodes()
    {
        $actual = [
            [
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'name' => 'node2',
                'path' => '/1/3/2/',
            ],
            [
                'name' => 'node3',
                'path' => '/1/3/',
            ],
        ];

        $expected = [
            [
                'id' => 1,
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'path' => '/1/3/2/',
            ],
            [
                'id' => 3,
                'name' => 'node3',
                'path' => '/1/3/',
            ],
        ];

        $converter = new Converter(new MaterializedPath($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toMaterializedPath('path', '/', null, 'id'))
        );
    }

    public function testConvertToMPAndIdentifySlugBasedNodes()
    {
        $actual = [
            [
                'name' => 'node1',
                'path' => '/one/',
            ],
            [
                'name' => 'node2',
                'path' => '/one/three/two/',
            ],
            [
                'name' => 'node3',
                'path' => '/one/three/',
            ],
        ];

        $expected = [
            [
                'id' => 'one',
                'name' => 'node1',
                'path' => '/one/',
            ],
            [
                'id' => 'two',
                'name' => 'node2',
                'path' => '/one/three/two/',
            ],
            [
                'id' => 'three',
                'name' => 'node3',
                'path' => '/one/three/',
            ],
        ];

        $converter = new Converter(new MaterializedPath($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toMaterializedPath('path', '/', null, 'id'))
        );
    }

    public function testConvertToNS()
    {
        $notIdentifiedNS = array_map(function($node) {
            unset($node['id']);
            return $node;
        }, $this->ns);

        $this->assertSame(
            json_encode($notIdentifiedNS),
            json_encode($this->converter->toNestedSet())
        );
    }

    public function testConvertToNSAndIdentifyNodes()
    {
        $this->assertSame(
            json_encode($this->ns),
            json_encode($this->converter->toNestedSet('lft', 'rgt', 'id'))
        );
    }

    public function testConvertToNSAndRenameExistedIdField()
    {
        $mp = [
            [
                'id' => 1,
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'path' => '/1/3/2/',
            ],
            [
                'id' => 3,
                'name' => 'node3',
                'path' => '/1/3/',
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

        $converter = new Converter(new MaterializedPath($mp, 'path', '/', null, 'id'));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet('lft', 'rgt', 'identifier'))
        );
    }
}
