<?php

namespace Tests\Functional;

use Tests\_support\FunctionalTestCase;
use Vhood\TreeType\Converter;
use Vhood\TreeType\Type\MaterializedPath;

class MaterializedPathConvertingTest extends FunctionalTestCase
{
    public function testConvertToTheSame()
    {
        $mp = $this->numBasedNodes(['path', 'name']);

        $converter = new Converter(new MaterializedPath($mp));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath())
        );
    }

    public function testCalculateNumBasedNodeLevels()
    {
        $actual = $this->numBasedNodes(['path', 'name']);
        $expected = $this->numBasedNodes(['path', 'name', 'level']);

        $converter = new Converter(new MaterializedPath($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toMaterializedPath('path', '/', 'level'))
        );
    }

    public function testCalculateSlugBasedNodeLevels()
    {
        $actual = $this->slugBasedNodes(['path', 'name']);
        $expected = $this->slugBasedNodes(['path', 'name', 'level']);

        $converter = new Converter(new MaterializedPath($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toMaterializedPath('path', '/', 'level'))
        );
    }

    public function testNumBasedNodesIdentification()
    {
        $actual = $this->numBasedNodes(['path', 'name']);
        $expected = $this->numBasedNodes(['path', 'name', 'id']);

        $converter = new Converter(new MaterializedPath($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toMaterializedPath('path', '/', null, 'id'))
        );
    }

    public function testSlugBasedNodesIdentification()
    {
        $actual = $this->slugBasedNodes(['path', 'name']);
        $expected = $this->slugBasedNodes(['path', 'name', 'id']);

        $converter = new Converter(new MaterializedPath($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toMaterializedPath('path', '/', null, 'id'))
        );
    }

    public function testRenamePathKey()
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
                'newPath' => '/1/',
            ],
            [
                'name' => 'node2',
                'newPath' => '/1/3/2/',
            ],
            [
                'name' => 'node3',
                'newPath' => '/1/3/',
            ],
        ];

        $converter = new Converter(new MaterializedPath($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toMaterializedPath('newPath', '/'))
        );
    }

    public function testChangePathSeparator()
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
                'path' => '.1.',
            ],
            [
                'name' => 'node2',
                'path' => '.1.3.2.',
            ],
            [
                'name' => 'node3',
                'path' => '.1.3.',
            ],
        ];

        $converter = new Converter(new MaterializedPath($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toMaterializedPath('path', '.'))
        );
    }

    public function testRenamePathKeyAndChangePathSeparator()
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
                'newPath' => '.1.',
            ],
            [
                'name' => 'node2',
                'newPath' => '.1.3.2.',
            ],
            [
                'name' => 'node3',
                'newPath' => '.1.3.',
            ],
        ];

        $converter = new Converter(new MaterializedPath($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toMaterializedPath('newPath', '.'))
        );
    }

    public function testConvertNumBasedButNotIdentifiedNodesToAL()
    {
        $mp = $this->numBasedNodes(['path', 'name']);
        $al = $this->numBasedNodes(['id', 'parent_id', 'name']);

        $converter = new Converter(new MaterializedPath($mp));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList())
        );
    }

    public function testConvertSlugBasedButNotIdentifiedNodesToAL()
    {
        $mp = $this->slugBasedNodes(['path', 'name']);
        $al = $this->slugBasedNodes(['id', 'parent_id', 'name']);

        $converter = new Converter(new MaterializedPath($mp));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList())
        );
    }

    public function testConvertNumBasedNodesToAL()
    {
        $mp = $this->numBasedNodes(['id', 'path', 'name']);
        $al = $this->numBasedNodes(['id', 'parent_id', 'name']);

        $converter = new Converter(new MaterializedPath($mp, 'path', '/', null, 'id'));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList())
        );
    }

    public function testConvertSlugBasedNodesToAL()
    {
        $mp = $this->slugBasedNodes(['id', 'path', 'name']);
        $al = $this->slugBasedNodes(['id', 'parent_id', 'name']);

        $converter = new Converter(new MaterializedPath($mp, 'path', '/', null, 'id'));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList())
        );
    }

    public function testConvertToALAndRenameIdKey()
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

    public function testNumBasedButNotIdentifiedNodesToNS()
    {
        $mp = $this->numBasedNodes(['path', 'name']);
        $ns = $this->numBasedNodes(['lft', 'rgt', 'name']);

        $converter = new Converter(new MaterializedPath($mp));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet())
        );
    }

    public function testSlugBasedButNotIdentifiedNodesToNS()
    {
        $mp = $this->slugBasedNodes(['path', 'name']);
        $ns = $this->slugBasedAndSlugSortedNodes(['lft', 'rgt', 'name']);

        $converter = new Converter(new MaterializedPath($mp));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet())
        );
    }

    public function testNumBasedNodesToMinimalNS()
    {
        $mp = $this->numBasedNodes(['id', 'path', 'name']);
        $ns = $this->numBasedNodes(['lft', 'rgt', 'name']);

        $converter = new Converter(new MaterializedPath($mp, 'path', '/', null, 'id'));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet('lft', 'rgt'))
        );
    }

    public function testSlugBasedNodesToMinimalNS()
    {
        $mp = $this->slugBasedNodes(['id', 'path', 'name']);
        $ns = $this->slugBasedAndSlugSortedNodes(['lft', 'rgt', 'name']);

        $converter = new Converter(new MaterializedPath($mp, 'path', '/', null, 'id'));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet('lft', 'rgt'))
        );
    }

    public function testNumBasedNodesToNS()
    {
        $mp = $this->numBasedNodes(['id', 'path', 'name']);
        $ns = $this->numBasedNodes(['id', 'lft', 'rgt', 'name']);

        $converter = new Converter(new MaterializedPath($mp, 'path', '/', null, 'id'));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet('lft', 'rgt', 'id'))
        );
    }

    public function testSlugBasedNodesToNS()
    {
        $mp = $this->slugBasedNodes(['id', 'path', 'name']);
        $ns = $this->slugBasedAndSlugSortedNodes(['id', 'lft', 'rgt', 'name']);

        $converter = new Converter(new MaterializedPath($mp, 'path', '/', null, 'id'));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet('lft', 'rgt', 'id'))
        );
    }

    public function testConvertNumBasedButNotIdentifiedNodesToNSAndIdentifyThem()
    {
        $mp = $this->numBasedNodes(['path', 'name']);
        $ns = $this->numBasedNodes(['id', 'lft', 'rgt', 'name']);

        $converter = new Converter(new MaterializedPath($mp));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet('lft', 'rgt', 'id'))
        );
    }

    public function testConvertSlugBasedButNotIdentifiedNodesToNSAndIdentifyThem()
    {
        $mp = $this->slugBasedNodes(['path', 'name']);
        $ns = $this->slugBasedAndSlugSortedNodes(['id', 'lft', 'rgt', 'name']);

        $converter = new Converter(new MaterializedPath($mp));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet('lft', 'rgt', 'id'))
        );
    }

    public function testConvertToNSAndRenameIdKey()
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

    public function testConvertNumBasedButNotIdentifiedNodesToTree()
    {
        $mp = $this->numBasedNodes(['path', 'name']);

        $converter = new Converter(new MaterializedPath($mp));

        $this->assertSame(
            json_encode($this->minimalTree()),
            json_encode($converter->toAssociativeArrayTree())
        );
    }

    public function testConvertSlugBasedButNotIdentifiedNodesToTree()
    {
        $mp = $this->slugBasedNodes(['path', 'name']);

        $converter = new Converter(new MaterializedPath($mp));

        $this->assertSame(
            json_encode($this->minimalTree()),
            json_encode($converter->toAssociativeArrayTree())
        );
    }

    public function testConvertNumBasedNodesToMinimalTree()
    {
        $mp = $this->numBasedNodes(['id', 'path', 'name']);

        $converter = new Converter(new MaterializedPath($mp, 'path', '/', null, 'id'));

        $this->assertSame(
            json_encode($this->minimalTree()),
            json_encode($converter->toAssociativeArrayTree('children'))
        );
    }

    public function testConvertSlugBasedNodesToMinimalTree()
    {
        $mp = $this->slugBasedNodes(['id', 'path', 'name']);

        $converter = new Converter(new MaterializedPath($mp, 'path', '/', null, 'id'));

        $this->assertSame(
            json_encode($this->minimalTree()),
            json_encode($converter->toAssociativeArrayTree('children'))
        );
    }

    public function testConvertNumBasedNodesToTree()
    {
        $mp = $this->numBasedNodes(['id', 'path', 'name']);

        $converter = new Converter(new MaterializedPath($mp, 'path', '/', null, 'id'));

        $this->assertSame(
            json_encode($this->numBasedTree()),
            json_encode($converter->toAssociativeArrayTree('children', 'id'))
        );
    }

    public function testConvertSlugBasedNodesToTree()
    {
        $mp = $this->slugBasedNodes(['id', 'path', 'name']);

        $converter = new Converter(new MaterializedPath($mp, 'path', '/', null, 'id'));

        $this->assertSame(
            json_encode($this->slugBasedTree()),
            json_encode($converter->toAssociativeArrayTree('children', 'id'))
        );
    }

    public function testConvertNumBasedButNotIdentifiedNodesToTreeAndIdentifyThem()
    {
        $mp = $this->numBasedNodes(['path', 'name']);

        $converter = new Converter(new MaterializedPath($mp));

        $this->assertSame(
            json_encode($this->numBasedTree()),
            json_encode($converter->toAssociativeArrayTree('children', 'id'))
        );
    }

    public function testConvertSlugBasedButNotIdentifiedNodesToTreeAndIdentifyThem()
    {
        $mp = $this->slugBasedNodes(['path', 'name']);

        $converter = new Converter(new MaterializedPath($mp));

        $this->assertSame(
            json_encode($this->slugBasedTree()),
            json_encode($converter->toAssociativeArrayTree('children', 'id'))
        );
    }

    public function testConvertToTreeAndRenameIdKey()
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

        $converter = new Converter(new MaterializedPath($mp, 'path', '/', null, 'id'));

        $this->assertSame(
            json_encode($tree),
            json_encode($converter->toAssociativeArrayTree('children', 'identifier'))
        );
    }
}
