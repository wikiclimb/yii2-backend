<?php

namespace common\fixtures;

use common\models\User;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('user.php');
    }
}
