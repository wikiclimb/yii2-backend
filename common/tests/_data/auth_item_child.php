<?php

$adminItems = [
    'createUser', 'listUsers', 'viewUser', 'updateUser', 'deleteUser'
];

$items = [];

foreach ($adminItems as $adminItem) {
    $items[] = [
        'parent' => 'admin',
        'child' => $adminItem
    ];
}

return array_merge($items, [
    // Rules
]);
