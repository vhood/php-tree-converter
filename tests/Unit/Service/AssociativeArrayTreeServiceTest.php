<?php

namespace Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Vhood\TreeType\Exception\InvalidStructureException;
use Vhood\TreeType\Service\AssociativeArrayTreeService;

class AssociativeArrayTreeServiceTest extends TestCase
{
    public function testNumBasedIdFieldValidationPassed()
    {
        $identifiedTree = [
            [
                'id' => 1,
                'name' => 'node1',
                'children' => [
                    [
                        'id' => 3,
                        'name' => 'node3',
                        'children' => [
                            [
                                'id' => 2,
                                'name' => 'node2',
                            ],
                            [
                                'id' => 4,
                                'name' => 'node4',
                                'children' => [
                                    [
                                        'id' => 5,
                                        'name' => 'node5',
                                        'children' => []
                                    ],
                                ],
                            ],
                        ]
                    ]
                ]
            ]
        ];

        $service = new AssociativeArrayTreeService($identifiedTree, 'children');

        $this->assertNull($service->validateIdField('id'));
    }

    public function testNumBasedIdFieldValidationFailed()
    {
        $badIdentifiedTree = [
            [
                'id' => 1,
                'name' => 'node1',
                'children' => [
                    [
                        'name' => 'node3',
                        'children' => []
                    ],
                ]
            ]
        ];

        $this->expectException(InvalidStructureException::class);

        $service = new AssociativeArrayTreeService($badIdentifiedTree, 'children');

        $service->validateIdField('id');
    }

    public function testSlugBasedIdFieldValidationPassed()
    {
        $identifiedTree = [
            [
                'id' => 'one',
                'name' => 'node1',
                'children' => [
                    [
                        'id' => 'three',
                        'name' => 'node3',
                    ]
                ]
            ]
        ];

        $service = new AssociativeArrayTreeService($identifiedTree, 'children');

        $this->assertNull($service->validateIdField('id'));
    }

    public function testSlugBasedIdFieldValidationFailed()
    {
        $badIdentifiedTree = [
            [
                'id' => 'one',
                'name' => 'node1',
                'children' => [
                    [
                        'name' => 'node3',
                        'children' => []
                    ],
                ]
            ]
        ];

        $this->expectException(InvalidStructureException::class);

        $service = new AssociativeArrayTreeService($badIdentifiedTree, 'children');

        $service->validateIdField('id');
    }

    public function testNodesIdentification()
    {
        $actualNodes = [
            [
                'name' => 'node1',
                'children' => [
                    [
                        'name' => 'node3',
                        'children' => [
                            [
                                'name' => 'node2',
                            ],
                            [
                                'name' => 'node4',
                                'children' => [
                                    [
                                        'name' => 'node5',
                                        'children' => []
                                    ],
                                ],
                            ],
                        ]
                    ]
                ]
            ]
        ];

        $expectedNodes = [
            [
                'id' => 1,
                'name' => 'node1',
                'children' => [
                    [
                        'id' => 2,
                        'name' => 'node3',
                        'children' => [
                            [
                                'id' => 3,
                                'name' => 'node2',
                            ],
                            [
                                'id' => 4,
                                'name' => 'node4',
                                'children' => [
                                    [
                                        'id' => 5,
                                        'name' => 'node5',
                                        'children' => []
                                    ],
                                ],
                            ],
                        ]
                    ]
                ]
            ]
        ];

        $service = new AssociativeArrayTreeService($actualNodes, 'children');

        $result = $service->identifyNodes('id');

        $this->assertSame(json_encode($expectedNodes), json_encode($result));
    }

    public function testFieldsRemoval()
    {
        $actualNodes = [
            [
                'id' => 1,
                'name' => 'node1',
                'children' => [
                    [
                        'id' => 3,
                        'name' => 'node3',
                        'children' => [
                            [
                                'id' => 2,
                                'name' => 'node2',
                            ],
                            [
                                'id' => 4,
                                'name' => 'node4',
                                'children' => [
                                    [
                                        'id' => 5,
                                        'name' => 'node5',
                                        'children' => []
                                    ],
                                ],
                            ],
                        ]
                    ]
                ]
            ]
        ];

        $expectedNodes = [
            [
                'id' => 1,
                'children' => [
                    [
                        'id' => 3,
                        'children' => [
                            [
                                'id' => 2,
                            ],
                            [
                                'id' => 4,
                                'children' => [
                                    [
                                        'id' => 5,
                                        'children' => []
                                    ],
                                ],
                            ],
                        ]
                    ]
                ]
            ]
        ];

        $service = new AssociativeArrayTreeService($actualNodes, 'children');

        $result = $service->removeTheField('name');

        $this->assertSame(json_encode($expectedNodes), json_encode($result));
    }

    public function testFieldsRenaming()
    {
        $actualNodes = [
            [
                'id' => 1,
                'name' => 'node1',
                'children' => [
                    [
                        'id' => 3,
                        'name' => 'node3',
                        'children' => [
                            [
                                'id' => 2,
                                'name' => 'node2',
                            ],
                            [
                                'id' => 4,
                                'name' => 'node4',
                                'children' => [
                                    [
                                        'id' => 5,
                                        'name' => 'node5',
                                        'children' => []
                                    ],
                                ],
                            ],
                        ]
                    ]
                ]
            ]
        ];

        $expectedNodes = [
            [
                'id' => 1,
                'newNameKey' => 'node1',
                'children' => [
                    [
                        'id' => 3,
                        'newNameKey' => 'node3',
                        'children' => [
                            [
                                'id' => 2,
                                'newNameKey' => 'node2',
                            ],
                            [
                                'id' => 4,
                                'newNameKey' => 'node4',
                                'children' => [
                                    [
                                        'id' => 5,
                                        'newNameKey' => 'node5',
                                        'children' => []
                                    ],
                                ],
                            ],
                        ]
                    ]
                ]
            ]
        ];

        $service = new AssociativeArrayTreeService($actualNodes, 'children');

        $result = $service->renameTheKey('name', 'newNameKey');

        $this->assertSame(json_encode($expectedNodes), json_encode($result));
    }
}
