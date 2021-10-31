<?php

use yii\rest\UrlRule;

return [
    ['class' => UrlRule::class, 'controller' => 'login', 'pluralize' => false,],
    ['class' => UrlRule::class, 'controller' => 'node'],
    ['class' => UrlRule::class, 'controller' => 'user'],
];
