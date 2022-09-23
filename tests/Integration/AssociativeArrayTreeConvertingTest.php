<?php

namespace Tests\Integration;

use Tests\_support\IntegrationTestCase;
use Vhood\TreeType\Converter;
use Vhood\TreeType\Type\AssociativeArrayTree;

class AssociativeArrayTreeConvertingTest extends IntegrationTestCase
{
    public function testConvertToTheSame()
    {
        $tree = $this->minimalTree();

        $converter = new Converter(new AssociativeArrayTree($tree));

        $this->assertSame(
            json_encode($tree),
            json_encode($converter->toAssociativeArrayTree())
        );
    }

    public function testNodesIdentification()
    {
        $actual = [
            [
                'name' => 'node1',
                'children' => [
                    [
                        'name' => 'node3',
                        'children' => [
                            [
                                'name' => 'node2',
                                'children' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $expected = [
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

        $converter = new Converter(new AssociativeArrayTree($actual));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toAssociativeArrayTree('children', 'id'))
        );
    }

    public function testRenameIdKey()
    {
        $actual = [
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

        $expected = [
            [
                'identifier' => 1,
                'name' => 'node1',
                'leafs' => [
                    [
                        'identifier' => 3,
                        'name' => 'node3',
                        'leafs' => [
                            [
                                'identifier' => 2,
                                'name' => 'node2',
                                'leafs' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $converter = new Converter(new AssociativeArrayTree($actual, 'children', 'id'));

        $this->assertSame(
            json_encode($expected),
            json_encode($converter->toAssociativeArrayTree('leafs', 'identifier'))
        );
    }

    public function testConvertNotIdentifiedTreeToAL()
    {
        $tree = [
            [
                'name' => 'node1',
                'children' => [
                    [
                        'name' => 'node3',
                        'children' => [
                            [
                                'name' => 'node2',
                                'children' => [],
                            ],
                        ],
                    ],
                ],
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
        ];

        $converter = new Converter(new AssociativeArrayTree($tree));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList())
        );
    }

    public function testConvertNumBasedTreeToAL()
    {
        $tree = $this->numBasedTree();
        $al = $this->numBasedNodes(['id', 'parent_id', 'name']);

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList())
        );
    }

    public function testConvertSlugBasedTreeToAL()
    {
        $tree = $this->slugBasedTree();
        $al = $this->slugBasedAndSlugSortedNodes(['id', 'parent_id', 'name']);

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList())
        );
    }

    public function testConvertToALAndRenameIdKey()
    {
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

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($al),
            json_encode($converter->toAdjacencyList('identifier'))
        );
    }

    public function testConvertNotIdentifiedTreeToMinimalMP()
    {
        $tree = [
            [
                'name' => 'node1',
                'children' => [
                    [
                        'name' => 'node3',
                        'children' => [
                            [
                                'name' => 'node2',
                                'children' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $mp = [
            [
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'name' => 'node3',
                'path' => '/1/2/',
            ],
            [
                'name' => 'node2',
                'path' => '/1/2/3/',
            ],
        ];

        $converter = new Converter(new AssociativeArrayTree($tree));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath())
        );
    }

    public function testConvertNumBasedTreeToMinimalMP()
    {
        $tree = $this->numBasedTree();
        $mp = $this->numBasedNodes(['path', 'name']);

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath())
        );
    }

    public function testConvertSlugBasedTreeToMinimalMP()
    {
        $tree = $this->slugBasedTree();
        $mp = $this->slugBasedAndSlugSortedNodes(['path', 'name']);

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath())
        );
    }

    public function testConvertNotIdentifiedTreeToMPWithLevels()
    {
        $tree = [
            [
                'name' => 'node1',
                'children' => [
                    [
                        'name' => 'node3',
                        'children' => [
                            [
                                'name' => 'node2',
                                'children' => [],
                            ],
                        ],
                    ],
                ],
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

        $converter = new Converter(new AssociativeArrayTree($tree));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', 'level'))
        );
    }

    public function testConvertNumBasedTreeToMPWithLevels()
    {
        $tree = $this->numBasedTree();
        $mp = $this->numBasedNodes(['path', 'name', 'level']);

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', 'level'))
        );
    }

    public function testConvertSlugBasedTreeToMPWithLevels()
    {
        $tree = $this->slugBasedTree();
        $mp = $this->slugBasedAndSlugSortedNodes(['path', 'name', 'level']);

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', 'level'))
        );
    }

    public function testConvertNotIdentifiedTreeToMPWithIds()
    {
        $tree = [
            [
                'name' => 'node1',
                'children' => [
                    [
                        'name' => 'node3',
                        'children' => [
                            [
                                'name' => 'node2',
                                'children' => [],
                            ],
                        ],
                    ],
                ],
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

        $converter = new Converter(new AssociativeArrayTree($tree));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', null, 'id'))
        );
    }

    public function testConvertNumBasedTreeToMPWithIds()
    {
        $tree = $this->numBasedTree();
        $mp = $this->numBasedNodes(['path', 'name', 'id']);

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', null, 'id'))
        );
    }

    public function testConvertSlugBasedTreeToMPWithIds()
    {
        $tree = $this->slugBasedTree();
        $mp = $this->slugBasedAndSlugSortedNodes(['path', 'name', 'id']);

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', null, 'id'))
        );
    }

    public function testConvertToMPAndRenameIdKey()
    {
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

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($mp),
            json_encode($converter->toMaterializedPath('path', '/', null, 'identifier'))
        );
    }

    public function testConvertNotIdentifiedTreeToMinimalNS()
    {
        $tree = [
            [
                'name' => 'node1',
                'children' => [
                    [
                        'name' => 'node3',
                        'children' => [
                            [
                                'name' => 'node2',
                                'children' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $ns = [
            [
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 6,
            ],
            [
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 5,
            ],
            [
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
        ];

        $converter = new Converter(new AssociativeArrayTree($tree));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet())
        );
    }

    public function testConvertNumBasedTreeToMinimalNS()
    {
        $tree = $this->numBasedTree();
        $ns = $this->numBasedNodes(['lft', 'rgt', 'name']);

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet())
        );
    }

    public function testConvertSlugBasedTreeToMinimalNS()
    {
        $tree = $this->slugBasedTree();
        $ns = $this->slugBasedAndSlugSortedNodes(['lft', 'rgt', 'name']);

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet())
        );
    }

    public function testConvertNotIdentifiedTreeToNSWithIds()
    {
        $tree = [
            [
                'name' => 'node1',
                'children' => [
                    [
                        'name' => 'node3',
                        'children' => [
                            [
                                'name' => 'node2',
                                'children' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $ns = [
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

        $converter = new Converter(new AssociativeArrayTree($tree));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet('lft', 'rgt', 'id'))
        );
    }

    public function testConvertNumBasedTreeToNSWithIds()
    {
        $tree = $this->numBasedTree();
        $ns = $this->numBasedNodes(['id', 'lft', 'rgt', 'name']);

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet('lft', 'rgt', 'id'))
        );
    }

    public function testConvertSlugBasedTreeToNSWithIds()
    {
        $tree = $this->slugBasedTree();
        $ns = $this->slugBasedAndSlugSortedNodes(['id', 'lft', 'rgt', 'name']);

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet('lft', 'rgt', 'id'))
        );
    }

    public function testConvertToNSAndRenameIdKey()
    {
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

        $converter = new Converter(new AssociativeArrayTree($tree, 'children', 'id'));

        $this->assertSame(
            json_encode($ns),
            json_encode($converter->toNestedSet('lft', 'rgt', 'identifier'))
        );
    }
}
