<?php

namespace common\fixtures;

use common\models\I18nText;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

class I18nTextFixture extends ActiveFixture
{
    public $modelClass = I18nText::class;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('i18n_text.php');
    }
}
