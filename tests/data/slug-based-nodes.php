<?php

return [
    [
        'name' => 'node1',
        // AL
        'id' => 'one',
        'parent_id' => null,
        // MP
        'path' => '/one/',
        'level' => 1,
        // NS
        'lft' => 1,
        'rgt' => 18,
    ],
    [
        'name' => 'node1.1',
        // AL
        'id' => 'two',
        'parent_id' => 'one',
        // MP
        'path' => '/one/two/',
        'level' => 2,
        // NS
        'lft' => 2,
        'rgt' => 5,
    ],
    [
        'name' => 'node2',
        // AL
        'id' => 'three',
        'parent_id' => null,
        // MP
        'path' => '/three/',
        'level' => 1,
        // NS
        'lft' => 19,
        'rgt' => 20,
    ],
    [
        'name' => 'node1.1.1',
        // AL
        'id' => 'four',
        'parent_id' => 'two',
        // MP
        'path' => '/one/two/four/',
        'level' => 3,
        // NS
        'lft' => 3,
        'rgt' => 4,
    ],
    [
        'name' => 'node4.1',
        // AL
        'id' => 'five',
        'parent_id' => 'nine',
        // MP
        'path' => '/nine/five/',
        'level' => 2,
        // NS
        'lft' => 24,
        'rgt' => 37,
    ],
    [
        'name' => 'node4.1.1',
        // AL
        'id' => 'six',
        'parent_id' => 'five',
        // MP
        'path' => '/nine/five/six/',
        'level' => 3,
        // NS
        'lft' => 25,
        'rgt' => 36,
    ],
    [
        'name' => 'node3',
        // AL
        'id' => 'seven',
        'parent_id' => null,
        // MP
        'path' => '/seven/',
        'level' => 1,
        // NS
        'lft' => 21,
        'rgt' => 22,
    ],
    [
        'name' => 'node1.2',
        // AL
        'id' => 'eight',
        'parent_id' => 'one',
        // MP
        'path' => '/one/eight/',
        'level' => 2,
        // NS
        'lft' => 6,
        'rgt' => 7,
    ],
    [
        'name' => 'node4',
        // AL
        'id' => 'nine',
        'parent_id' => null,
        // MP
        'path' => '/nine/',
        'level' => 1,
        // NS
        'lft' => 23,
        'rgt' => 38,
    ],
    [
        'name' => 'node1.3',
        // AL
        'id' => 'ten',
        'parent_id' => 'one',
        // MP
        'path' => '/one/ten/',
        'level' => 2,
        // NS
        'lft' => 8,
        'rgt' => 9,
    ],
    [
        'name' => 'node1.4',
        // AL
        'id' => 'eleven',
        'parent_id' => 'one',
        // MP
        'path' => '/one/eleven/',
        'level' => 2,
        // NS
        'lft' => 10,
        'rgt' => 15,
    ],
    [
        'name' => 'node4.1.1.1',
        // AL
        'id' => 'twelve',
        'parent_id' => 'six',
        // MP
        'path' => '/nine/five/six/twelve/',
        'level' => 4,
        // NS
        'lft' => 26,
        'rgt' => 35,
    ],
    [
        'name' => 'node4.1.1.1.1',
        // AL
        'id' => 'thirteen',
        'parent_id' => 'twelve',
        // MP
        'path' => '/nine/five/six/twelve/thirteen/',
        'level' => 5,
        // NS
        'lft' => 27,
        'rgt' => 34,
    ],
    [
        'name' => 'node5',
        // AL
        'id' => 'fourteen',
        'parent_id' => null,
        // MP
        'path' => '/fourteen/',
        'level' => 1,
        // NS
        'lft' => 39,
        'rgt' => 68,
    ],
    [
        'name' => 'node5.1',
        // AL
        'id' => 'fifteen',
        'parent_id' => 'fourteen',
        // MP
        'path' => '/fourteen/fifteen/',
        'level' => 2,
        // NS
        'lft' => 40,
        'rgt' => 67,
    ],
    [
        'name' => 'node4.1.1.1.1.1',
        // AL
        'id' => 'sixteen',
        'parent_id' => 'thirteen',
        // MP
        'path' => '/nine/five/six/twelve/thirteen/sixteen/',
        'level' => 6,
        // NS
        'lft' => 28,
        'rgt' => 33,
    ],
    [
        'name' => 'node4.1.1.1.1.1.1.1',
        // AL
        'id' => 'seventeen',
        'parent_id' => 'eighteen',
        // MP
        'path' => '/nine/five/six/twelve/thirteen/sixteen/eighteen/seventeen/',
        'level' => 8,
        // NS
        'lft' => 30,
        'rgt' => 31,
    ],
    [
        'name' => 'node4.1.1.1.1.1.1',
        // AL
        'id' => 'eighteen',
        'parent_id' => 'sixteen',
        // MP
        'path' => '/nine/five/six/twelve/thirteen/sixteen/eighteen/',
        'level' => 7,
        // NS
        'lft' => 29,
        'rgt' => 32,
    ],
    [
        'name' => 'node5.1.1',
        // AL
        'id' => 'nineteen',
        'parent_id' => 'fifteen',
        // MP
        'path' => '/fourteen/fifteen/nineteen/',
        'level' => 3,
        // NS
        'lft' => 41,
        'rgt' => 56,
    ],
    [
        'name' => 'node5.1.1.1',
        // AL
        'id' => 'twenty',
        'parent_id' => 'nineteen',
        // MP
        'path' => '/fourteen/fifteen/nineteen/twenty/',
        'level' => 4,
        // NS
        'lft' => 42,
        'rgt' => 55,
    ],
    [
        'name' => 'node5.1.2',
        // AL
        'id' => 'twenty-one',
        'parent_id' => 'fifteen',
        // MP
        'path' => '/fourteen/fifteen/twenty-one/',
        'level' => 3,
        // NS
        'lft' => 57,
        'rgt' => 66,
    ],
    [
        'name' => 'node5.1.1.1.1',
        // AL
        'id' => 'twenty-two',
        'parent_id' => 'twenty',
        // MP
        'path' => '/fourteen/fifteen/nineteen/twenty/twenty-two/',
        'level' => 5,
        // NS
        'lft' => 43,
        'rgt' => 54,
    ],
    [
        'name' => 'node5.1.1.1.1.1',
        // AL
        'id' => 'twenty-three',
        'parent_id' => 'twenty-two',
        // MP
        'path' => '/fourteen/fifteen/nineteen/twenty/twenty-two/twenty-three/',
        'level' => 6,
        // NS
        'lft' => 44,
        'rgt' => 45,
    ],
    [
        'name' => 'node5.1.1.1.1.2.1',
        // AL
        'id' => 'twenty-four',
        'parent_id' => 'twenty-five',
        // MP
        'path' => '/fourteen/fifteen/nineteen/twenty/twenty-two/twenty-five/twenty-four/',
        'level' => 7,
        // NS
        'lft' => 47,
        'rgt' => 48,
    ],
    [
        'name' => 'node5.1.1.1.1.2',
        // AL
        'id' => 'twenty-five',
        'parent_id' => 'twenty-two',
        // MP
        'path' => '/fourteen/fifteen/nineteen/twenty/twenty-two/twenty-five/',
        'level' => 6,
        // NS
        'lft' => 46,
        'rgt' => 51,
    ],
    [
        'name' => 'node5.1.1.1.1.2.2',
        // AL
        'id' => 'twenty-six',
        'parent_id' => 'twenty-five',
        // MP
        'path' => '/fourteen/fifteen/nineteen/twenty/twenty-two/twenty-five/twenty-six/',
        'level' => 7,
        // NS
        'lft' => 49,
        'rgt' => 50,
    ],
    [
        'name' => 'node5.1.2.1',
        // AL
        'id' => 'twenty-seven',
        'parent_id' => 'twenty-one',
        // MP
        'path' => '/fourteen/fifteen/twenty-one/twenty-seven/',
        'level' => 4,
        // NS
        'lft' => 58,
        'rgt' => 59,
    ],
    [
        'name' => 'node5.1.2.2',
        // AL
        'id' => 'twenty-eight',
        'parent_id' => 'twenty-one',
        // MP
        'path' => '/fourteen/fifteen/twenty-one/twenty-eight/',
        'level' => 4,
        // NS
        'lft' => 60,
        'rgt' => 61,
    ],
    [
        'name' => 'node5.1.2.3',
        // AL
        'id' => 'twenty-nine',
        'parent_id' => 'twenty-one',
        // MP
        'path' => '/fourteen/fifteen/twenty-one/twenty-nine/',
        'level' => 4,
        // NS
        'lft' => 62,
        'rgt' => 63,
    ],
    [
        'name' => 'node5.1.2.4',
        // AL
        'id' => 'thirty',
        'parent_id' => 'twenty-one',
        // MP
        'path' => '/fourteen/fifteen/twenty-one/thirty/',
        'level' => 4,
        // NS
        'lft' => 64,
        'rgt' => 65,
    ],
    [
        'name' => 'node5.1.1.1.1.3',
        // AL
        'id' => 'thirty-one',
        'parent_id' => 'twenty-two',
        // MP
        'path' => '/fourteen/fifteen/nineteen/twenty/twenty-two/thirty-one/',
        'level' => 6,
        // NS
        'lft' => 52,
        'rgt' => 53,
    ],
    [
        'name' => 'node1.4.1',
        // AL
        'id' => 'thirty-two',
        'parent_id' => 'eleven',
        // MP
        'path' => '/one/eleven/thirty-two/',
        'level' => 3,
        // NS
        'lft' => 11,
        'rgt' => 12,
    ],
    [
        'name' => 'node1.5',
        // AL
        'id' => 'thirty-three',
        'parent_id' => 'one',
        // MP
        'path' => '/one/thirty-three/',
        'level' => 2,
        // NS
        'lft' => 16,
        'rgt' => 17,
    ],
    [
        'name' => 'node1.4.2',
        // AL
        'id' => 'thirty-four',
        'parent_id' => 'eleven',
        // MP
        'path' => '/one/eleven/thirty-four/',
        'level' => 3,
        // NS
        'lft' => 13,
        'rgt' => 14,
    ],
];
