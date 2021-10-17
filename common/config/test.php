<?php
return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        // Commit test values to git. Used by GitHub actions.
        'db' => [
            'dsn' => 'mysql:host=localhost:32574;dbname=wikiclimb_test',
            'username' => 'root',
            'password' => 'password',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
        ],
    ],
];
