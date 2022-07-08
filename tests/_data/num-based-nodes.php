<?php

return [
    [
        'name' => 'node1',
        // AL
        'id' => 1,
        'parent_id' => null,
        // MP
        'path' => '/1/',
        'level' => 1,
        // NS
        'lft' => 1,
        'rgt' => 18,
    ],
    [
        'name' => 'node1.1',
        // AL
        'id' => 2,
        'parent_id' => 1,
        // MP
        'path' => '/1/2/',
        'level' => 2,
        // NS
        'lft' => 2,
        'rgt' => 5,
    ],
    [
        'name' => 'node2',
        // AL
        'id' => 3,
        'parent_id' => null,
        // MP
        'path' => '/3/',
        'level' => 1,
        // NS
        'lft' => 19,
        'rgt' => 20,
    ],
    [
        'name' => 'node1.1.1',
        // AL
        'id' => 4,
        'parent_id' => 2,
        // MP
        'path' => '/1/2/4/',
        'level' => 3,
        // NS
        'lft' => 3,
        'rgt' => 4,
    ],
    [
        'name' => 'node4.1',
        // AL
        'id' => 5,
        'parent_id' => 9,
        // MP
        'path' => '/9/5/',
        'level' => 2,
        // NS
        'lft' => 24,
        'rgt' => 37,
    ],
    [
        'name' => 'node4.1.1',
        // AL
        'id' => 6,
        'parent_id' => 5,
        // MP
        'path' => '/9/5/6/',
        'level' => 3,
        // NS
        'lft' => 25,
        'rgt' => 36,
    ],
    [
        'name' => 'node3',
        // AL
        'id' => 7,
        'parent_id' => null,
        // MP
        'path' => '/7/',
        'level' => 1,
        // NS
        'lft' => 21,
        'rgt' => 22,
    ],
    [
        'name' => 'node1.2',
        // AL
        'id' => 8,
        'parent_id' => 1,
        // MP
        'path' => '/1/8/',
        'level' => 2,
        // NS
        'lft' => 6,
        'rgt' => 7,
    ],
    [
        'name' => 'node4',
        // AL
        'id' => 9,
        'parent_id' => null,
        // MP
        'path' => '/9/',
        'level' => 1,
        // NS
        'lft' => 23,
        'rgt' => 38,
    ],
    [
        'name' => 'node1.3',
        // AL
        'id' => 10,
        'parent_id' => 1,
        // MP
        'path' => '/1/10/',
        'level' => 2,
        // NS
        'lft' => 8,
        'rgt' => 9,
    ],
    [
        'name' => 'node1.4',
        // AL
        'id' => 11,
        'parent_id' => 1,
        // MP
        'path' => '/1/11/',
        'level' => 2,
        // NS
        'lft' => 10,
        'rgt' => 15,
    ],
    [
        'name' => 'node4.1.1.1',
        // AL
        'id' => 12,
        'parent_id' => 6,
        // MP
        'path' => '/9/5/6/12/',
        'level' => 4,
        // NS
        'lft' => 26,
        'rgt' => 35,
    ],
    [
        'name' => 'node4.1.1.1.1',
        // AL
        'id' => 13,
        'parent_id' => 12,
        // MP
        'path' => '/9/5/6/12/13/',
        'level' => 5,
        // NS
        'lft' => 27,
        'rgt' => 34,
    ],
    [
        'name' => 'node5',
        // AL
        'id' => 14,
        'parent_id' => null,
        // MP
        'path' => '/14/',
        'level' => 1,
        // NS
        'lft' => 39,
        'rgt' => 68,
    ],
    [
        'name' => 'node5.1',
        // AL
        'id' => 15,
        'parent_id' => 14,
        // MP
        'path' => '/14/15/',
        'level' => 2,
        // NS
        'lft' => 40,
        'rgt' => 67,
    ],
    [
        'name' => 'node4.1.1.1.1.1',
        // AL
        'id' => 16,
        'parent_id' => 13,
        // MP
        'path' => '/9/5/6/12/13/16/',
        'level' => 6,
        // NS
        'lft' => 28,
        'rgt' => 33,
    ],
    [
        'name' => 'node4.1.1.1.1.1.1.1',
        // AL
        'id' => 17,
        'parent_id' => 18,
        // MP
        'path' => '/9/5/6/12/13/16/18/17/',
        'level' => 8,
        // NS
        'lft' => 30,
        'rgt' => 31,
    ],
    [
        'name' => 'node4.1.1.1.1.1.1',
        // AL
        'id' => 18,
        'parent_id' => 16,
        // MP
        'path' => '/9/5/6/12/13/16/18/',
        'level' => 7,
        // NS
        'lft' => 29,
        'rgt' => 32,
    ],
    [
        'name' => 'node5.1.1',
        // AL
        'id' => 19,
        'parent_id' => 15,
        // MP
        'path' => '/14/15/19/',
        'level' => 3,
        // NS
        'lft' => 41,
        'rgt' => 56,
    ],
    [
        'name' => 'node5.1.1.1',
        // AL
        'id' => 20,
        'parent_id' => 19,
        // MP
        'path' => '/14/15/19/20/',
        'level' => 4,
        // NS
        'lft' => 42,
        'rgt' => 55,
    ],
    [
        'name' => 'node5.1.2',
        // AL
        'id' => 21,
        'parent_id' => 15,
        // MP
        'path' => '/14/15/21/',
        'level' => 3,
        // NS
        'lft' => 57,
        'rgt' => 66,
    ],
    [
        'name' => 'node5.1.1.1.1',
        // AL
        'id' => 22,
        'parent_id' => 20,
        // MP
        'path' => '/14/15/19/20/22/',
        'level' => 5,
        // NS
        'lft' => 43,
        'rgt' => 54,
    ],
    [
        'name' => 'node5.1.1.1.1.1',
        // AL
        'id' => 23,
        'parent_id' => 22,
        // MP
        'path' => '/14/15/19/20/22/23/',
        'level' => 6,
        // NS
        'lft' => 44,
        'rgt' => 45,
    ],
    [
        'name' => 'node5.1.1.1.1.2.1',
        // AL
        'id' => 24,
        'parent_id' => 25,
        // MP
        'path' => '/14/15/19/20/22/25/24/',
        'level' => 7,
        // NS
        'lft' => 47,
        'rgt' => 48,
    ],
    [
        'name' => 'node5.1.1.1.1.2',
        // AL
        'id' => 25,
        'parent_id' => 22,
        // MP
        'path' => '/14/15/19/20/22/25/',
        'level' => 6,
        // NS
        'lft' => 46,
        'rgt' => 51,
    ],
    [
        'name' => 'node5.1.1.1.1.2.2',
        // AL
        'id' => 26,
        'parent_id' => 25,
        // MP
        'path' => '/14/15/19/20/22/25/26/',
        'level' => 7,
        // NS
        'lft' => 49,
        'rgt' => 50,
    ],
    [
        'name' => 'node5.1.2.1',
        // AL
        'id' => 27,
        'parent_id' => 21,
        // MP
        'path' => '/14/15/21/27/',
        'level' => 4,
        // NS
        'lft' => 58,
        'rgt' => 59,
    ],
    [
        'name' => 'node5.1.2.2',
        // AL
        'id' => 28,
        'parent_id' => 21,
        // MP
        'path' => '/14/15/21/28/',
        'level' => 4,
        // NS
        'lft' => 60,
        'rgt' => 61,
    ],
    [
        'name' => 'node5.1.2.3',
        // AL
        'id' => 29,
        'parent_id' => 21,
        // MP
        'path' => '/14/15/21/29/',
        'level' => 4,
        // NS
        'lft' => 62,
        'rgt' => 63,
    ],
    [
        'name' => 'node5.1.2.4',
        // AL
        'id' => 30,
        'parent_id' => 21,
        // MP
        'path' => '/14/15/21/30/',
        'level' => 4,
        // NS
        'lft' => 64,
        'rgt' => 65,
    ],
    [
        'name' => 'node5.1.1.1.1.3',
        // AL
        'id' => 31,
        'parent_id' => 22,
        // MP
        'path' => '/14/15/19/20/22/31/',
        'level' => 6,
        // NS
        'lft' => 52,
        'rgt' => 53,
    ],
    [
        'name' => 'node1.4.1',
        // AL
        'id' => 32,
        'parent_id' => 11,
        // MP
        'path' => '/1/11/32/',
        'level' => 3,
        // NS
        'lft' => 11,
        'rgt' => 12,
    ],
    [
        'name' => 'node1.5',
        // AL
        'id' => 33,
        'parent_id' => 1,
        // MP
        'path' => '/1/33/',
        'level' => 2,
        // NS
        'lft' => 16,
        'rgt' => 17,
    ],
    [
        'name' => 'node1.4.2',
        // AL
        'id' => 34,
        'parent_id' => 11,
        // MP
        'path' => '/1/11/34/',
        'level' => 3,
        // NS
        'lft' => 13,
        'rgt' => 14,
    ],
];
