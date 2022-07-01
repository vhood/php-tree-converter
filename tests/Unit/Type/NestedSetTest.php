<?php

namespace Tests\Unit\Type;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Exception\InvalidStructureException;
use Vhood\TreeType\Type\NestedSet;

class NestedSetTest extends TestCase
{
    public function testDefaultFieldsValidationPassed()
    {
        $nodes = [
            [
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 10,
            ],
            [
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 9,
            ],
            [
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
        ];

        new NestedSet($nodes, 'lft', 'rgt');

        $this->assertTrue(true);
    }

    public function testLeftValueFieldValidationFailed()
    {
        $nodes = [
            [
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 10,
            ],
            [
                'name' => 'node3',
                'rgt' => 9,
            ],
            [
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
        ];

        $this->expectException(InvalidStructureException::class);

        new NestedSet($nodes, 'lft', 'rgt');
    }

    public function testIncorrectLeftValueFieldValidationFailed()
    {
        $nodes = [
            [
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 10,
            ],
            [
                'name' => 'node3',
                'lft' => 'slug',
                'rgt' => 9,
            ],
            [
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
        ];

        $this->expectException(InvalidStructureException::class);

        new NestedSet($nodes, 'lft', 'rgt');
    }

    public function testRightValueFieldValidationFailed()
    {
        $nodes = [
            [
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 10,
            ],
            [
                'name' => 'node3',
                'lft' => 2,
            ],
            [
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
        ];

        $this->expectException(InvalidStructureException::class);

        new NestedSet($nodes, 'lft', 'rgt');
    }

    public function testIncorrectRightValueFieldValidationFailed()
    {
        $nodes = [
            [
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 10,
            ],
            [
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 'slug',
            ],
            [
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
        ];

        $this->expectException(InvalidStructureException::class);

        new NestedSet($nodes, 'lft', 'rgt');
    }

    public function testIdFieldValidationPassed()
    {
        $nodes = [
            [
                'id' => 1,
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 10,
            ],
            [
                'id' => 2,
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 9,
            ],
            [
                'id' => 3,
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
        ];

        new NestedSet($nodes, 'lft', 'rgt', 'id');

        $this->assertTrue(true);
    }

    public function testIdFieldValidationFailed()
    {
        $nodes = [
            [
                'id' => 1,
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 10,
            ],
            [
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 9,
            ],
            [
                'id' => 3,
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
        ];

        $this->expectException(InvalidStructureException::class);

        new NestedSet($nodes, 'lft', 'rgt', 'id');
    }
}
