<?php

namespace common\fixtures;

use common\models\AuthRule;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

/**
 * Class AuthRuleFixture
 * @package common\fixtures
 */
class AuthRuleFixture extends ActiveFixture
{
    public $modelClass = AuthRule::class;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('auth_rule.php');
    }
}
