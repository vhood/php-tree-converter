<?php

namespace Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Service\MaterializedPathService;

class MaterializedPathServiceTest extends TestCase
{
    private $actualNodes;

    public function setUp()
    {
        $this->actualNodes = [
            'first' => [
                'name' => 'node1',
                'path' => '/1/',
            ],
            'middle' => [
                'name' => 'node3',
                'path' => '/1/3/',
            ],
            [
                'name' => 'node2',
                'path' => '/1/3/2/',
            ],
            [
                'name' => 'node4',
                'path' => '/1/3/4/',
            ],
            'last' => [
                'name' => 'node5',
                'path' => '/1/3/4/5/',
            ],
        ];
    }

    public function testNodesIdentification()
    {
        $expectedNodes = [
            [
                'id' => '1',
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'id' => '3',
                'name' => 'node3',
                'path' => '/1/3/',
            ],
            [
                'id' => '2',
                'name' => 'node2',
                'path' => '/1/3/2/',
            ],
            [
                'id' => '4',
                'name' => 'node4',
                'path' => '/1/3/4/',
            ],
            [
                'id' => '5',
                'name' => 'node5',
                'path' => '/1/3/4/5/',
            ],
        ];

        $service = new MaterializedPathService($this->actualNodes, 'path', '/');

        $result = array_values($service->identifyNodes('id'));

        $this->assertSame(json_encode($expectedNodes), json_encode($result));
    }

    public function testPathsRebuilding()
    {
        $expectedNodes = [
            [
                'name' => 'node1',
                'dotPath' => '.1.',
            ],
            [
                'name' => 'node3',
                'dotPath' => '.1.3.',
            ],
            [
                'name' => 'node2',
                'dotPath' => '.1.3.2.',
            ],
            [
                'name' => 'node4',
                'dotPath' => '.1.3.4.',
            ],
            [
                'name' => 'node5',
                'dotPath' => '.1.3.4.5.',
            ],
        ];

        $service = new MaterializedPathService($this->actualNodes, 'path', '/');

        $result = array_values($service->rebuildPath('dotPath', '.'));

        $this->assertSame(json_encode($expectedNodes), json_encode($result));
    }

    public function testCalculateFirstNodeChildren()
    {
        $service = new MaterializedPathService($this->actualNodes, 'path', '/');

        $this->assertSame(4, $service->calculateChildren($this->actualNodes['first']));
    }

    public function testCalculateMiddleNodeChildren()
    {
        $service = new MaterializedPathService($this->actualNodes, 'path', '/');

        $this->assertSame(3, $service->calculateChildren($this->actualNodes['middle']));
    }

    public function testCalculateLastNodeChildren()
    {
        $service = new MaterializedPathService($this->actualNodes, 'path', '/');

        $this->assertSame(0, $service->calculateChildren($this->actualNodes['last']));
    }

    public function testLevelsCalculation()
    {
        $expectedNodes = [
            [
                'name' => 'node1',
                'path' => '/1/',
                'level' => 1,
            ],
            [
                'name' => 'node3',
                'path' => '/1/3/',
                'level' => 2,
            ],
            [
                'name' => 'node2',
                'path' => '/1/3/2/',
                'level' => 3,
            ],
            [
                'name' => 'node4',
                'path' => '/1/3/4/',
                'level' => 3,
            ],
            [
                'name' => 'node5',
                'path' => '/1/3/4/5/',
                'level' => 4,
            ],
        ];

        $service = new MaterializedPathService($this->actualNodes, 'path', '/');

        $result = array_values($service->calculateLevels('level'));

        $this->assertSame(json_encode($expectedNodes), json_encode($result));
    }
}
