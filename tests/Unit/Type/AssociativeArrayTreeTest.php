<?php

namespace Tests\Unit\Type;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Exception\InvalidStructureException;
use Vhood\TreeType\Type\AssociativeArrayTree;

class AssociativeArrayTreeTest extends TestCase
{
    public function testNotEmptyTreeValidationPassed()
    {
        $tree = [
            [
                'id' => 1,
                'name' => 'node1',
                'children' => [
                    [
                        'id' => 2,
                        'name' => 'node2'
                    ],
                ],
            ],
        ];

        new AssociativeArrayTree($tree, 'children');

        $this->assertTrue(true);
    }

    public function testEmptyTreeValidationFailed()
    {
        $tree = [];

        $this->expectException(InvalidStructureException::class);

        new AssociativeArrayTree($tree, 'children');
    }
}
