<?php

namespace common\fixtures;

use common\models\AuthItemChild;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

/**
 * Class AuthItemChildFixture
 * @package common\fixtures
 */
class AuthItemChildFixture extends ActiveFixture
{
    public $modelClass = AuthItemChild::class;

    public $depends = [
        AuthItemFixture::class,
    ];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('auth_item_child.php');
    }
}
