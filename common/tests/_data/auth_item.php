<?php

$roles = ['admin', 'user', 'guest',];

$permissions = [
    'createNode', 'listNodes', 'viewNode', 'updateNode', 'deleteNode',
    'createUser', 'listUsers', 'viewUser', 'updateUser', 'deleteUser',
];

$items = [];

foreach ($roles as $role) {
    $items[] = [
        'name' => $role,
        'type' => 1,
        'description' => '',
        'data' => null,
        'created_at' => 1577161056,
        'updated_at' => 1577161056,
    ];
}

foreach ($permissions as $permission) {
    $items[] = [
        'name' => $permission,
        'type' => 2,
        'description' => '',
        'data' => null,
        'created_at' => 1577161056,
        'updated_at' => 1577161056,
    ];
}

return array_merge($items, [
    [
        'name' => 'appUserIsRequestedUser',
        'type' => 2,
        'description' => 'Check if requested user is requesting user',
        'rule_name' => 'appUserIsRequestedUser',
        'data' => null,
        'created_at' => 1635576338,
        'updated_at' => 1635576338
    ],
]);
