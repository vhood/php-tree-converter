<?php

namespace Tests\Functional;

use Tests\_support\FunctionalTestCase;
use Vhood\TreeType\Converter;
use Vhood\TreeType\Type\AdjacencyList;

class AdjacencyListConvertionTest extends FunctionalTestCase
{
    public function testConvertToTheSame()
    {
        $al = $this->numBasedNodes(['id', 'parent_id', 'name']);

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList('id', 'parent_id'))
        );
    }

    public function testRenameIdKey()
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
                'parent_id' => null,
            ],
            [
                'identifier' => 2,
                'name' => 'node2',
                'parent_id' => 1,
            ],
        ];

        $converter = new Converter(new AdjacencyList($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toAdjacencyList('identifier', 'parent_id'))
        );
    }

    public function testRenameParentIdKey()
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
                'id' => 1,
                'name' => 'node1',
                'parentId' => null,
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'parentId' => 1,
            ],
        ];

        $converter = new Converter(new AdjacencyList($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toAdjacencyList('id', 'parentId'))
        );
    }

    public function testRenameIdAndParentIdKeys()
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

    public function testConvertNumBasedNodesToMinimalMP()
    {
        $al = $this->numBasedNodes(['id', 'parent_id', 'name']);
        $mp = $this->numBasedNodes(['path', 'name']);

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath())
        );
    }

    public function testConvertSlugBasedNodesToMinimalMP()
    {
        $al = $this->slugBasedNodes(['id', 'parent_id', 'name']);
        $mp = $this->slugBasedNodes(['path', 'name']);

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath())
        );
    }

    public function testConvertNumBasedNodesToMPWithIds()
    {
        $al = $this->numBasedNodes(['id', 'parent_id', 'name']);
        $mp = $this->numBasedNodes(['id', 'path', 'name']);

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', null, 'id'))
        );
    }

    public function testConvertSlugBasedNodesToMPWithIds()
    {
        $al = $this->slugBasedNodes(['id', 'parent_id', 'name']);
        $mp = $this->slugBasedNodes(['id', 'path', 'name']);

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', null, 'id'))
        );
    }

    public function testConvertNumBasedNodesToMPWithLevels()
    {
        $al = $this->numBasedNodes(['id', 'parent_id', 'name']);
        $mp = $this->numBasedNodes(['path', 'name', 'level']);

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', 'level'))
        );
    }

    public function testConvertSlugBasedNodesToMPWithLevels()
    {
        $al = $this->slugBasedNodes(['id', 'parent_id', 'name']);
        $mp = $this->slugBasedNodes(['path', 'name', 'level']);

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', 'level'))
        );
    }

    public function testConvertToMPAndRenameIdKey()
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

    public function testConvertNumBasedNodesToMinimalNS()
    {
        $al = $this->numBasedNodes(['id', 'parent_id', 'name']);
        $ns = $this->numBasedNodes(['lft', 'rgt', 'name']);

        $converter = new Converter(new AdjacencyList($al));


        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet())
        );
    }

    public function testConvertSlugBasedNodesToMinimalNS()
    {
        $al = $this->slugBasedNodes(['id', 'parent_id', 'name']);
        $ns = $this->slugBasedAndSlugSortedNodes(['lft', 'rgt', 'name']);

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet())
        );
    }

    public function testConvertToNSAndRenameIdKey()
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

    public function testConvertNumBasedNodesToMinimalTree()
    {
        $al = $this->numBasedNodes(['id', 'parent_id', 'name']);

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($this->minimalTree()),
            json_encode($converter->toTree())
        );
    }

    public function testConvertSlugBasedNodesToMinimalTree()
    {
        $al = $this->slugBasedNodes(['id', 'parent_id', 'name']);

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($this->minimalTree()),
            json_encode($converter->toTree())
        );
    }

    public function testConvertNumBasedNodesToTreeWithIds()
    {
        $al = $this->numBasedNodes(['id', 'parent_id', 'name']);

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($this->numBasedTree()),
            json_encode($converter->toTree('children', 'id'))
        );
    }

    public function testConvertSlugBasedNodesToTreeWithIds()
    {
        $al = $this->slugBasedNodes(['id', 'parent_id', 'name']);

        $converter = new Converter(new AdjacencyList($al));

        $this->assertSame(
            json_encode($this->slugBasedTree()),
            json_encode($converter->toTree('children', 'id'))
        );
    }

    public function testConvertToTreeAndRenameIdKey()
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


}
