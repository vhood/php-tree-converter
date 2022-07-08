<?php

namespace Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Service\MaterializedPathService;

class MaterializedPathServiceTest extends TestCase
{
    private $actual;

    public function setUp()
    {
        $this->actual = [
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

    public function testNumBasedNodesIdentification()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'id' => 3,
                'name' => 'node3',
                'path' => '/1/3/',
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'path' => '/1/3/2/',
            ],
            [
                'id' => 4,
                'name' => 'node4',
                'path' => '/1/3/4/',
            ],
            [
                'id' => 5,
                'name' => 'node5',
                'path' => '/1/3/4/5/',
            ],
        ];

        $service = new MaterializedPathService($this->actual, 'path', '/');

        $result = array_values($service->identifyNodes('id'));

        $this->assertSame(json_encode($expected), json_encode($result));
    }

    public function testSlugBasedNodesIdentification()
    {
        $actual = [
            [
                'name' => 'node1',
                'path' => '/one/',
            ],
            [
                'name' => 'node3',
                'path' => '/one/three/',
            ],
            [
                'name' => 'node2',
                'path' => '/one/three/two/',
            ],
        ];

        $expected = [
            [
                'id' => 'one',
                'name' => 'node1',
                'path' => '/one/',
            ],
            [
                'id' => 'three',
                'name' => 'node3',
                'path' => '/one/three/',
            ],
            [
                'id' => 'two',
                'name' => 'node2',
                'path' => '/one/three/two/',
            ],
        ];

        $service = new MaterializedPathService($actual, 'path', '/');

        $result = array_values($service->identifyNodes('id'));

        $this->assertSame(json_encode($expected), json_encode($result));
    }

    public function testRebuildPathKeyOnly()
    {
        $expected = [
            [
                'name' => 'node1',
                'newPath' => '/1/',
            ],
            [
                'name' => 'node3',
                'newPath' => '/1/3/',
            ],
            [
                'name' => 'node2',
                'newPath' => '/1/3/2/',
            ],
            [
                'name' => 'node4',
                'newPath' => '/1/3/4/',
            ],
            [
                'name' => 'node5',
                'newPath' => '/1/3/4/5/',
            ],
        ];

        $service = new MaterializedPathService($this->actual, 'path', '/');

        $result = array_values($service->rebuildPath('newPath', '/'));

        $this->assertSame(json_encode($expected), json_encode($result));
    }

    public function testRebuildPathSeparatorOnly()
    {
        $expected = [
            [
                'name' => 'node1',
                'path' => '.1.',
            ],
            [
                'name' => 'node3',
                'path' => '.1.3.',
            ],
            [
                'name' => 'node2',
                'path' => '.1.3.2.',
            ],
            [
                'name' => 'node4',
                'path' => '.1.3.4.',
            ],
            [
                'name' => 'node5',
                'path' => '.1.3.4.5.',
            ],
        ];

        $service = new MaterializedPathService($this->actual, 'path', '/');

        $result = array_values($service->rebuildPath('path', '.'));

        $this->assertSame(json_encode($expected), json_encode($result));
    }

    public function testRebuildPath()
    {
        $expected = [
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

        $service = new MaterializedPathService($this->actual, 'path', '/');

        $result = array_values($service->rebuildPath('dotPath', '.'));

        $this->assertSame(json_encode($expected), json_encode($result));
    }

    public function testCalculateFirstNodeChildren()
    {
        $service = new MaterializedPathService($this->actual, 'path', '/');

        $this->assertSame(4, $service->calculateChildren($this->actual['first']));
    }

    public function testCalculateMiddleNodeChildren()
    {
        $service = new MaterializedPathService($this->actual, 'path', '/');

        $this->assertSame(3, $service->calculateChildren($this->actual['middle']));
    }

    public function testCalculateLastNodeChildren()
    {
        $service = new MaterializedPathService($this->actual, 'path', '/');

        $this->assertSame(0, $service->calculateChildren($this->actual['last']));
    }

    public function testLevelsCalculation()
    {
        $expected = [
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

        $service = new MaterializedPathService($this->actual, 'path', '/');

        $result = array_values($service->calculateLevels('level'));

        $this->assertSame(json_encode($expected), json_encode($result));
    }
}
