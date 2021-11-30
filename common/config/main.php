<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@staticPath' => dirname(__DIR__, 2) . '/static',
        '@imgPath' => '@staticPath/img',
    ],
    'name' => 'WikiClimb',
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
//            'cache' => 'cache',
        ],
    ],
];
