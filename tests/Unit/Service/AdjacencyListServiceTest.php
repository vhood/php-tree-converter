<?php

namespace Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Service\AdjacencyListService;

class AdjacencyListServiceTest extends TestCase
{
    private $numBasedNodes;
    private $slugBasedNodes;

    public function setUp()
    {
        $this->numBasedNodes = [
            'first' => [
                'id' => 1,
                'parent_id' => null,
                'name' => 'node1',
            ],
            [
                'id' => 2,
                'parent_id' => 3,
                'name' => 'node2',
            ],
            'middle' => [
                'id' => 3,
                'parent_id' => 1,
                'name' => 'node3',
            ],
            [
                'id' => 4,
                'parent_id' => 3,
                'name' => 'node4',
            ],
            'last' => [
                'id' => 5,
                'parent_id' => 4,
                'name' => 'node4',
            ],
        ];

        $this->slugBasedNodes = [
            'first' => [
                'id' => 'one',
                'parent_id' => null,
                'name' => 'node1',
            ],
            [
                'id' => 'two',
                'parent_id' => 'three',
                'name' => 'node2',
            ],
            'middle' => [
                'id' => 'three',
                'parent_id' => 'one',
                'name' => 'node3',
            ],
            [
                'id' => 'four',
                'parent_id' => 'three',
                'name' => 'node4',
            ],
            'last' => [
                'id' => 'five',
                'parent_id' => 'four',
                'name' => 'node4',
            ],
        ];
    }

    public function testListNumBasedNodeParents()
    {
        $expected = [
            2 => [
                'id' => 3,
                'parent_id' => 1,
                'name' => 'node3',
            ],
            3 => [
                'id' => 1,
                'parent_id' => null,
                'name' => 'node1',
            ],
            4 => [
                'id' => 3,
                'parent_id' => 1,
                'name' => 'node3',
            ],
            5 => [
                'id' => 4,
                'parent_id' => 3,
                'name' => 'node4',
            ],
        ];

        $service = new AdjacencyListService($this->numBasedNodes, 'id', 'parent_id');

        $this->assertSame(
            json_encode($expected),
            json_encode($service->listParents())
        );
    }

    public function testListSlugBasedNodeParents()
    {
        $expectedMap = [
            'two' => [
                'id' => 'three',
                'parent_id' => 'one',
                'name' => 'node3',
            ],
            'three' => [
                'id' => 'one',
                'parent_id' => null,
                'name' => 'node1',
            ],
            'four' => [
                'id' => 'three',
                'parent_id' => 'one',
                'name' => 'node3',
            ],
            'five' => [
                'id' => 'four',
                'parent_id' => 'three',
                'name' => 'node4',
            ],
        ];

        $service = new AdjacencyListService($this->slugBasedNodes, 'id', 'parent_id');

        $this->assertSame(
            json_encode($expectedMap),
            json_encode($service->listParents())
        );
    }

    public function testPathBuildingBackwardCompatibility()
    {
        $service = new AdjacencyListService($this->numBasedNodes, 'id', 'parent_id');

        $this->assertSame('/1/3/4/5', $service->buildNodePath($this->numBasedNodes['last'], '/'));
    }

    public function testNumBasedParentPathBuilding()
    {
        $service = new AdjacencyListService($this->numBasedNodes, 'id', 'parent_id');
        $parentsMap = $service->listParents();

        $this->assertSame('/1', $service->buildNodePath($this->numBasedNodes['first'], '/', $parentsMap));
    }

    public function testNumBasedChildrenPathBuilding()
    {
        $service = new AdjacencyListService($this->numBasedNodes, 'id', 'parent_id');
        $parentsMap = $service->listParents();

        $this->assertSame('/1/3/4/5', $service->buildNodePath($this->numBasedNodes['last'], '/', $parentsMap));
    }

    public function testSlugBasedParentPathBuilding()
    {
        $service = new AdjacencyListService($this->slugBasedNodes, 'id', 'parent_id');
        $parentsMap = $service->listParents();

        $this->assertSame('/one', $service->buildNodePath($this->slugBasedNodes['first'], '/', $parentsMap));
    }

    public function testSlugBasedChildrenPathBuilding()
    {
        $service = new AdjacencyListService($this->slugBasedNodes, 'id', 'parent_id');
        $parentsMap = $service->listParents();

        $this->assertSame('/one/three/four/five', $service->buildNodePath($this->slugBasedNodes['last'], '/', $parentsMap));
    }

    public function testCalculateNumBasedParentNodeChildren()
    {
        $service = new AdjacencyListService($this->numBasedNodes, 'id', 'parent_id');

        $this->assertSame(4, $service->calculateChildren($this->numBasedNodes['first']));
    }

    public function testCalculateNumBasedMiddleChildNodeChildren()
    {
        $service = new AdjacencyListService($this->numBasedNodes, 'id', 'parent_id');

        $this->assertSame(3, $service->calculateChildren($this->numBasedNodes['middle']));
    }

    public function testCalculateNumBasedLastChildNodeChildren()
    {
        $service = new AdjacencyListService($this->numBasedNodes, 'id', 'parent_id');

        $this->assertSame(0, $service->calculateChildren($this->numBasedNodes['last']));
    }

    public function testCalculateSlugBasedParentNodeChildren()
    {
        $service = new AdjacencyListService($this->slugBasedNodes, 'id', 'parent_id');

        $this->assertSame(4, $service->calculateChildren($this->slugBasedNodes['first']));
    }

    public function testCalculateSlugBasedMiddleChildNodeChildren()
    {
        $service = new AdjacencyListService($this->slugBasedNodes, 'id', 'parent_id');

        $this->assertSame(3, $service->calculateChildren($this->slugBasedNodes['middle']));
    }

    public function testCalculateSlugBasedLastChildNodeChildren()
    {
        $service = new AdjacencyListService($this->slugBasedNodes, 'id', 'parent_id');

        $this->assertSame(0, $service->calculateChildren($this->slugBasedNodes['last']));
    }
}
