<?php

namespace Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Service\NodeService;

class NodeServiceTest extends TestCase
{
    public function testFindParentsPathWithoutThePathKey()
    {
        $node = [
            'id' => 1,
            'name' => 'node1',
        ];

        $service = new NodeService($node);

        $this->assertSame('', $service->findParentsPath('path', '/'));
    }

    public function testFindParentsPathForARootNode()
    {
        $node = [
            'name' => 'node1',
            'path' => '/1/'
        ];

        $service = new NodeService($node);

        $this->assertSame('', $service->findParentsPath('path', '/'));
    }

    public function testFindParentsPathForAChild()
    {
        $node = [
            'name' => 'node1',
            'path' => '/1/2/3/'
        ];

        $service = new NodeService($node);

        $this->assertSame('/1/2/', $service->findParentsPath('path', '/'));
    }
}
