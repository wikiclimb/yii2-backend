<?php

namespace common\fixtures;

use common\models\NodeImage;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

/**
 * Class NodeImageFixture
 * @package common\fixtures
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class NodeImageFixture extends ActiveFixture
{
    public $modelClass = NodeImage::class;

    public $depends = [
        ImageFixture::class,
        NodeFixture::class,
    ];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('node_image.php');
    }
}
