<?php

namespace common\fixtures;

use yii\test\Fixture;

/**
 * Helper class to load all Authentication related fixtures with
 * one line.
 */
class AuthFixture extends Fixture
{

    public $depends = [
        AuthItemChildFixture::class,
        AuthAssignmentFixture::class,
    ];
}
