<?php

$roles = ['admin',];

$permissions = [
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
    // Rules
]);
