<?php

namespace Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Service\NestedSetService;

class NestedSetServiceTest extends TestCase
{
    public function testNodesIdentification()
    {
        $actualNodes = [
            [
                'name' => 'node1',
                'lft' => 1,
                'rgt' => 10,
            ],
            [
                'name' => 'node2',
                'lft' => 3,
                'rgt' => 4,
            ],
            [
                'name' => 'node3',
                'lft' => 2,
                'rgt' => 9,
            ],
            [
                'name' => 'node4',
                'lft' => 5,
                'rgt' => 8,
            ],
            [
                'name' => 'node5',
                'lft' => 6,
                'rgt' => 7,
            ],
        ];

        $expectedNodes = [
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
            [
                'id' => 4,
                'name' => 'node4',
                'lft' => 5,
                'rgt' => 8,
            ],
            [
                'id' => 5,
                'name' => 'node5',
                'lft' => 6,
                'rgt' => 7,
            ],
        ];

        $service = new NestedSetService($actualNodes, 'lft', 'rgt');

        $result = $service->identifyNodes('id');

        $this->assertSame(json_encode($expectedNodes), json_encode($result));
    }
}
