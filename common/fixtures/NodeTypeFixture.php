<?php

namespace common\fixtures;

use common\models\NodeType;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

class NodeTypeFixture extends ActiveFixture
{
    public $modelClass = NodeType::class;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('node_type.php');
    }
}
