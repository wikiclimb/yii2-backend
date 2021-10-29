<?php

namespace common\fixtures;

use common\models\AuthAssignment;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

/**
 * Class AuthAssignmentFixture
 * @package common\fixtures
 */
class AuthAssignmentFixture extends ActiveFixture
{
    public $modelClass = AuthAssignment::class;

    public $depends = [
        AuthItemFixture::class,
        UserFixture::class,
    ];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('auth_assignment.php');
    }
}
