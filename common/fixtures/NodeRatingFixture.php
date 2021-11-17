<?php

namespace common\fixtures;

use common\models\NodeRating;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

/**
 * Class NodeRatingFixture
 * @package common\fixtures
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class NodeRatingFixture extends ActiveFixture
{
    public $modelClass = NodeRating::class;

    public $depends = [
        NodeFixture::class,
        UserFixture::class,
    ];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('node_rating.php');
    }
}
