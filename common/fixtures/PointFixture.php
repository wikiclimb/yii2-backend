<?php

namespace common\fixtures;

use common\models\Point;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

class PointFixture extends ActiveFixture
{
    public $modelClass = Point::class;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('point.php');
    }
}
