<?php

return yii\helpers\ArrayHelper::merge(
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
            'request' => [
                // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
                'cookieValidationKey' => 'test-cookie-validation-key',
            ],
        ],
    ],
);
