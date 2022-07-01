<?php

namespace Tests\Unit\Type;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Exception\InvalidStructureException;
use Vhood\TreeType\Type\MaterializedPath;

class MaterializedPathTest extends TestCase
{
    private $fullNodes;

    public function setUp()
    {
        $this->fullNodes = [
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
            [
                'id' => 4,
                'name' => 'node4',
                'path' => '/1/3/4/',
                'level' => 3,
            ],
            [
                'id' => 5,
                'name' => 'node5',
                'path' => '/1/3/4/5/',
                'level' => 4,
            ],
        ];
    }

    public function testPathFieldValidationPassed()
    {
        new MaterializedPath($this->fullNodes, 'path', '/');

        $this->assertTrue(true);
    }

    public function testPathFieldValidationFailed()
    {
        $nodes = [
            [
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'name' => 'node2',
            ],
            [
                'name' => 'node3',
                'path' => '/1/3/',
            ],
        ];

        $this->expectException(InvalidStructureException::class);

        new MaterializedPath($nodes, 'path', '/');
    }

    public function testIncorrectPathFieldValidationFailed()
    {
        $nodes = [
            [
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'name' => 'node2',
                'path' => '/',
            ],
            [
                'name' => 'node3',
                'path' => '/1/3/',
            ],
        ];

        $this->expectException(InvalidStructureException::class);

        new MaterializedPath($nodes, 'path', '/');
    }

    public function testIdFieldValidationPassed()
    {
        new MaterializedPath($this->fullNodes, 'path', '/', null, 'id');

        $this->assertTrue(true);
    }

    public function testIdFieldValidationFailed()
    {
        $nodes = [
            [
                'id' => 1,
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'name' => 'node2',
                'path' => '/1/3/2/',
            ],
            [
                'id' => 3,
                'name' => 'node3',
                'path' => '/1/3/',
            ],
        ];

        $this->expectException(InvalidStructureException::class);

        new MaterializedPath($nodes, 'path', '/', null, 'id');
    }

    public function testLevelFieldValidationPassed()
    {
        new MaterializedPath($this->fullNodes, 'path', '/', 'level');

        $this->assertTrue(true);
    }

    public function testLevelFieldValidationFailed()
    {
        $nodes = [
            [
                'name' => 'node1',
                'path' => '/1/',
                'level' => 1,
            ],
            [
                'name' => 'node2',
                'path' => '/1/3/2/',
            ],
            [
                'name' => 'node3',
                'path' => '/1/3/',
                'level' => 2,
            ],
        ];

        $this->expectException(InvalidStructureException::class);

        new MaterializedPath($nodes, 'path', '/', 'level');
    }
}
