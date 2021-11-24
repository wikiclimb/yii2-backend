<?php

$adminItems = [
    'user',
    'createUser', 'listUsers', 'viewUser', 'updateUser', 'deleteUser'
];

$userItems = [
    'appUserIsRequestedUser',
    'createNode', 'listNodes', 'viewNode', 'updateNode', 'deleteNode',
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
