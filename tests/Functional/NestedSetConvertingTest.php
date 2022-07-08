<?php

namespace Tests\Functional;

use Tests\_support\FunctionalTestCase;
use Vhood\TreeType\Converter;
use Vhood\TreeType\Type\NestedSet;

class NestedSetConvertingTest extends FunctionalTestCase
{
    public function testConvertToTheSame()
    {
        $ns = $this->numBasedNodes(['lft', 'rgt', 'name']);

        $converter = new Converter(new NestedSet($ns));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet())
        );
    }

    public function testNodesIdentification()
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

    public function testRenameLeftValueKey()
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
                'name' => 'node1',
                'left' => 1,
                'rgt' => 6,
            ],
            [
                'name' => 'node2',
                'left' => 3,
                'rgt' => 4,
            ],
            [
                'name' => 'node3',
                'left' => 2,
                'rgt' => 5,
            ],
        ];

        $converter = new Converter(new NestedSet($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toNestedSet('left'))
        );
    }

    public function testRenameRightValueKey()
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
                'name' => 'node1',
                'lft' => 1,
                'right' => 6,
            ],
            [
                'name' => 'node2',
                'lft' => 3,
                'right' => 4,
            ],
            [
                'name' => 'node3',
                'lft' => 2,
                'right' => 5,
            ],
        ];

        $converter = new Converter(new NestedSet($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toNestedSet('lft', 'right'))
        );
    }

    public function testRenameIdKey()
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

        $converter = new Converter(new NestedSet($actual, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toNestedSet('lft', 'rgt', 'identifier'))
        );
    }

    public function testRenameKeys()
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

    public function testConvertNotIdentifiedNodesToAL()
    {
        $ns = [
            [
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 10,
            ],
            [
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
            [
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 9,
            ],
            [
                'name' => 'node4',
                'lft' => 5,
                'rgt' => 8,
            ],
            [
                'name' => 'node5',
                'lft' => 6,
                'rgt' => 7,
            ],
        ];

        $al = [
            [
                'id' => 1,
                'name' => 'node1',
                'parent_id' => null,
            ],
            [
                'id' => 2,
                'name' => 'node3',
                'parent_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'node2',
                'parent_id' => 2,
            ],
            [
                'id' => 4,
                'name' => 'node4',
                'parent_id' => 2,
            ],
            [
                'id' => 5,
                'name' => 'node5',
                'parent_id' => 4,
            ],
        ];

        $converter = new Converter(new NestedSet($ns));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList())
        );
    }

    public function testConvertNumBasedNodesToAL()
    {
        $ns = $this->numBasedNodes(['id', 'lft', 'rgt', 'name']);
        $al = $this->numBasedNodes(['id', 'parent_id', 'name']);

        $converter = new Converter(new NestedSet($ns, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList())
        );
    }

    public function testConvertSlugBasedNodesToAL()
    {
        $ns = $this->slugBasedNodes(['id', 'lft', 'rgt', 'name']);
        $al = $this->slugBasedAndSlugSortedNodes(['id', 'parent_id', 'name']);

        $converter = new Converter(new NestedSet($ns, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList())
        );
    }

    public function testConvertToALAndRenameIdKey()
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

        $converter = new Converter(new NestedSet($ns, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList('identifier'))
        );
    }

    public function testConvertNumBasedNodesToMinimalMP()
    {
        $ns = $this->numBasedNodes(['id', 'lft', 'rgt', 'name']);
        $mp = $this->numBasedNodes(['path', 'name']);

        $converter = new Converter(new NestedSet($ns, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath())
        );
    }

    public function testConvertSlugBasedNodesToMinimalMP()
    {
        $ns = $this->slugBasedNodes(['id', 'lft', 'rgt', 'name']);
        $mp = $this->slugBasedNodes(['path', 'name']);

        $converter = new Converter(new NestedSet($ns, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath())
        );
    }

    public function testConvertNumBasedNodesToMPWithIds()
    {
        $ns = $this->numBasedNodes(['id', 'lft', 'rgt', 'name']);
        $mp = $this->numBasedNodes(['id', 'path', 'name']);

        $converter = new Converter(new NestedSet($ns, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($mp),
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

    public function testConvertToMPAndRenameIdKey()
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

        $converter = new Converter(new NestedSet($ns, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', null, 'identifier'))
        );
    }

    public function testConvertNumBasedNodesToMinimalTree()
    {
        $ns = $this->numBasedNodes(['id', 'lft', 'rgt', 'name']);

        $converter = new Converter(new NestedSet($ns, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($this->minimalTree()),
            json_encode($converter->toAssociativeArrayTree())
        );
    }

    public function testConvertSlugBasedNodesToMinimalTree()
    {
        $ns = $this->slugBasedNodes(['id', 'lft', 'rgt', 'name']);

        $converter = new Converter(new NestedSet($ns, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($this->minimalTree()),
            json_encode($converter->toAssociativeArrayTree())
        );
    }

    public function testConvertNumBasedNodesToTreeWithIds()
    {
        $ns = $this->numBasedNodes(['id', 'lft', 'rgt', 'name']);

        $converter = new Converter(new NestedSet($ns, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($this->numBasedTree()),
            json_encode($converter->toAssociativeArrayTree('children', 'id'))
        );
    }

    public function testConvertSlugBasedNodesToTreeWithIds()
    {
        $ns = $this->slugBasedNodes(['id', 'lft', 'rgt', 'name']);

        $converter = new Converter(new NestedSet($ns, 'lft', 'rgt', 'id'));

        $this->assertSame(
            json_encode($this->slugBasedTree()),
            json_encode($converter->toAssociativeArrayTree('children', 'id'))
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
            json_encode($converter->toAssociativeArrayTree('children', 'id'))
        );
    }

    public function testConvertToTreeAndRenameIdKey()
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
            json_encode($converter->toAssociativeArrayTree('children', 'identifier'))
        );
    }
}
