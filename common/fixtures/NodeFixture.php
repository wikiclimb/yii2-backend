<?php

namespace common\fixtures;

use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

class NodeFixture extends ActiveFixture
{
    public $modelClass = NodeFixture::class;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('node.php');
    }
}
