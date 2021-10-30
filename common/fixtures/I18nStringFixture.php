<?php

namespace common\fixtures;

use common\models\I18nString;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

class I18nStringFixture extends ActiveFixture
{
    public $modelClass = I18nString::class;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('i18n_string.php');
    }
}
