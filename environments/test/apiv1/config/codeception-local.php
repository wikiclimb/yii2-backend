<?php

return yii\helpers\ArrayHelper::merge(
    require dirname(dirname(__DIR__)) . '/common/config/codeception-local.php',
    require __DIR__ . '/main.php',
    require __DIR__ . '/main-local.php',
    require __DIR__ . '/test.php',
    require __DIR__ . '/test-local.php',
    [
        'components' => [
            'db' => [
                'dsn' => 'mysql:host=127.0.0.1:33006;dbname=wikiclimb_test',
                'username' => 'root',
                'password' => 'password',
            ],
        ],
    ],
);
