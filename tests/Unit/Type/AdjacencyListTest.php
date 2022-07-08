<?php

namespace Tests\Unit\Type;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Exception\InvalidStructureException;
use Vhood\TreeType\Type\AdjacencyList;

class AdjacencyListTest extends TestCase
{
    public function testIdFieldValidationPassed()
    {
        $identifiedNodes = [
            [
                'id' => 'one',
                'name' => 'node1',
            ],
            [
                'id' => 'two',
                'parent_id' => 'three',
                'name' => 'node2',
            ],
            [
                'id' => 'three',
                'parent_id' => 'one',
                'name' => 'node3',
            ],
            [
                'id' => 'four',
                'parent_id' => 'three',
                'name' => 'node4',
            ],
            [
                'id' => 'five',
                'parent_id' => 'four',
                'name' => 'node4',
            ],
        ];

        new AdjacencyList($identifiedNodes, 'id', 'parent_id');

        $this->assertTrue(true);
    }

    public function testIdFieldValidationFailed()
    {
        $badIdentifiedNodes = [
            [
                'id' => 'one',
                'name' => 'node1',
            ],
            [
                'id' => 'two',
                'parent_id' => 'three',
                'name' => 'node2',
            ],
            [
                'id' => 'three',
                'parent_id' => 'one',
                'name' => 'node3',
            ],
            [
                'parent_id' => 'three',
                'name' => 'node4',
            ],
            [
                'id' => 'five',
                'parent_id' => 'four',
                'name' => 'node4',
            ],
        ];

        $this->expectException(InvalidStructureException::class);

        new AdjacencyList($badIdentifiedNodes, 'id', 'parent_id');
    }
}
