<?php
$params = array_merge(
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        // Commit test values to git. Used by GitHub actions.
        'db' => [
            'dsn' => 'mysql:host=127.0.0.1:33006;dbname=wikiclimb_test',
            'username' => 'root',
            'password' => 'password',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
        ],
    ],
    'params' => $params,
];
