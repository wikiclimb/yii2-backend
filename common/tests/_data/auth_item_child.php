<?php

$adminItems = [
    'updateNode', 'deleteNode',
    'createUser', 'listUsers', 'viewUser', 'updateUser', 'deleteUser'
];

$userItems = [
    'appUserIsRequestedUser',
    'createNode', 'listNodes', 'viewNode',
];

$items = [];

foreach ($adminItems as $adminItem) {
    $items[] = [
        'parent' => 'admin',
        'child' => $adminItem,
    ];
}

foreach ($userItems as $userItem) {
    $items[] = [
        'parent' => 'user',
        'child' => $userItem,
    ];
}

return array_merge($items, [
    [
        'parent' => 'appUserIsRequestedUser',
        'child' => 'viewUser',
    ],
    [
        'parent' => 'appUserIsRequestedUser',
        'child' => 'updateUser',
    ],
    [
        'parent' => 'appUserIsRequestedUser',
        'child' => 'deleteUser',
    ],
]);
