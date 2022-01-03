<?php

return [
    'aliases' => [
        // Host configuration used when launched via default docker-compose.yml
        '@staticUrl' => 'http://localhost:23080',
        '@imgUrl' => '@staticUrl/img',
        '@cssUrl' => '@staticUrl/css',
        '@jsUrl' => '@staticUrl/js',
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            // DB Host configuration used when launched via default docker-compose.yml
            'dsn' => 'mysql:host=127.0.0.1:33007;dbname=wikiclimb',
            'username' => 'wikiclimb_user',
            'password' => 'password',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
