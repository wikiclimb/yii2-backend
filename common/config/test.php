<?php

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
    'params' => [
        'supportEmail' => 'support@wikiclimb.org',
        'registrationEmail' => 'registration@wikiclimb.org',
        'user.passwordMinLength' => 8,
    ],
];
