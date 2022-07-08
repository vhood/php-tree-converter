<?php

namespace Tests\Unit\Specification;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Specification\MaterializedPathSpecification;

class MaterializedPathSpecificationTest extends TestCase
{
    public function testAreIdentifiersNumericWithNumBasedNodes()
    {
        $mp = [
            [
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'name' => 'node2',
                'path' => '/1/3/2/',
            ],
            [
                'name' => 'node3',
                'path' => '/1/3/',
            ],
        ];

        $specification = new MaterializedPathSpecification($mp, 'path', '/');

        $this->assertTrue($specification->areIdentifiersNumeric());
    }

    public function testAreIdentifiersNumericWithSlugBasedNodes()
    {
        $mp = [
            [
                'name' => 'node1',
                'path' => '/one/',
            ],
            [
                'name' => 'node2',
                'path' => '/one/three/two/',
            ],
            [
                'name' => 'node3',
                'path' => '/one/three/',
            ],
        ];

        $specification = new MaterializedPathSpecification($mp, 'path', '/');

        $this->assertFalse($specification->areIdentifiersNumeric());
    }

    public function testAreIdentifiersNumericWithMixedNodes()
    {
        $mp = [
            [
                'name' => 'node1',
                'path' => '/1/',
            ],
            [
                'name' => 'node2',
                'path' => '/1/3/two/',
            ],
            [
                'name' => 'node3',
                'path' => '/1/3/',
            ],
        ];

        $specification = new MaterializedPathSpecification($mp, 'path', '/');

        $this->assertFalse($specification->areIdentifiersNumeric());
    }
}
