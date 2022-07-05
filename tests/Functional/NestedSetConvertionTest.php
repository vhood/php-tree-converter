<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Converter;
use Vhood\TreeType\Type\NestedSet;

class NestedSetConvertionTest extends TestCase
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

        $this->converter = new Converter(new NestedSet($this->ns));
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
        $ns = [
            [
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 6,
            ],
            [
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
            [
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 5,
            ],
        ];

        $tree = [
            [
                'id' => 1,
                'name' => 'node1',
                'children' => [
                    [
                        'id' => 2,
                        'name' => 'node3',
                        'children' => [
                            [
                                'id' => 3,
                                'name' => 'node2',
                                'children' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $converter = new Converter(new NestedSet($ns, 'lft', 'rgt'));

        $this->assertSame(
            json_encode($tree),
            json_encode($converter->toTree('children', 'id'))
        );
    }

    public function testConvertToTreeAndRenameExistedIdField()
    {
        $ns = [
            [
                'id' => 1,
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 6,
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
            [
                'id' => 3,
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 5,
            ],
        ];

        $tree = [
            [
                'identifier' => 1,
                'name' => 'node1',
                'children' => [
                    [
                        'identifier' => 3,
                        'name' => 'node3',
                        'children' => [
                            [
                                'identifier' => 2,
                                'name' => 'node2',
                                'children' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $converter = new Converter(new NestedSet($ns, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($tree),
            json_encode($converter->toTree('children', 'identifier'))
        );
    }

    public function testConvertToMP()
    {
        $converter = new Converter(new NestedSet($this->ns, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($this->mp),
            json_encode($converter->toMaterializedPath('path', '/', null, 'id'))
        );
    }

    public function testConvertToMPAndCalculateLevels()
    {
        $ns = [
            [
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 6,
            ],
            [
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
            [
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 5,
            ],
        ];

        $mp = [
            [
                'name' => 'node1',
                'path' => '/1/',
                'level' => 1,
            ],
            [
                'name' => 'node3',
                'path' => '/1/2/',
                'level' => 2,
            ],
            [
                'name' => 'node2',
                'path' => '/1/2/3/',
                'level' => 3,
            ],
        ];

        $converter = new Converter(new NestedSet($ns));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', 'level'))
        );
    }

    public function testConvertToMPAndIdentifyNodes()
    {
        $ns = [
            [
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 6,
            ],
            [
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
            [
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 5,
            ],
        ];

        $mp = [
            [
                'id' => 1,
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'id' => 2,
                'name' => 'node3',
                'path' => '/1/2/',
            ],
            [
                'id' => 3,
                'name' => 'node2',
                'path' => '/1/2/3/',
            ],
        ];

        $converter = new Converter(new NestedSet($ns));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', null, 'id'))
        );
    }

    public function testConvertToNS()
    {
        $this->assertSame(
            json_encode($this->ns),
            json_encode($this->converter->toNestedSet())
        );
    }

    public function testConvertToNSAndIdentifyNodes()
    {
        $actual = [
            [
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 6,
            ],
            [
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
            [
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 5,
            ],
        ];

        $expected = [
            [
                'id' => 1,
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 6,
            ],
            [
                'id' => 2,
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 5,
            ],
            [
                'id' => 3,
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
        ];

        $converter = new Converter(new NestedSet($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toNestedSet('lft', 'rgt', 'id'))
        );
    }

    public function testConvertToNSAndRenameFields()
    {
        $actual = [
            [
                'id' => 1,
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 6,
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
            [
                'id' => 3,
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 5,
            ],
        ];

        $expected = [
            [
                'identifier' => 1,
                'name' => 'node1',
                'left' => 1,
                'right' => 6,
            ],
            [
                'identifier' => 2,
                'name' => 'node2',
                'left' => 3,
                'right' => 4,
            ],
            [
                'identifier' => 3,
                'name' => 'node3',
                'left' => 2,
                'right' => 5,
            ],
        ];

        $converter = new Converter(new NestedSet($actual, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toNestedSet('left', 'right', 'identifier'))
        );
    }
}
