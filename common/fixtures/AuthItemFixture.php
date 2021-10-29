<?php

namespace common\fixtures;

use common\models\AuthItem;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

/**
 * Class AuthItemFixture
 * @package common\fixtures
 */
class AuthItemFixture extends ActiveFixture
{
    public $modelClass = AuthItem::class;

    public $depends = [
        AuthRuleFixture::class,
    ];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('auth_item.php');
    }
}
