<?php

namespace Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Service\FlatNodesService;

class FlatNodesServiceTest extends TestCase
{
    public function testFieldsRenaming()
    {
        $actualNodes = [
            [
                'id' => 1,
                'parent_id' => 0,
            ],
            [
                'id' => 2,
                'parent_id' => 1,
            ],
        ];

        $expectedNodes = [
            [
                'identifier' => 1,
                'parentId' => 0,
            ],
            [
                'identifier' => 2,
                'parentId' => 1,
            ],
        ];

        $service = new FlatNodesService($actualNodes);

        $result = $service->renameKeys([
            'id' => 'identifier',
            'parent_id' => 'parentId'
        ]);

        $this->assertSame(json_encode($expectedNodes), json_encode($result));
    }

    public function testFieldsRemoving()
    {
        $actualNodes = [
            [
                'id' => 1,
                'name' => 'node1',
                'parent_id' => 0,
            ],
            [
                'id' => 2,
                'name' => 'node2',
                'parent_id' => 1,
            ],
        ];

        $expectedNodes = [
            [
                'id' => 1,
            ],
            [
                'id' => 2,
            ],
        ];

        $service = new FlatNodesService($actualNodes);

        $result = $service->removeKeys(['name', 'parent_id']);

        $this->assertSame(json_encode($expectedNodes), json_encode($result));
    }
}
