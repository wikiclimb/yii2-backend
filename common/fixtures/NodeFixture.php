<?php

namespace common\fixtures;

use common\models\Node;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

/**
 * Class NodeFixture
 * @package common\fixtures
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class NodeFixture extends ActiveFixture
{
    public $modelClass = Node::class;

    public $depends = [
        AuthFixture::class,
        I18nStringFixture::class,
        I18nTextFixture::class,
        NodeTypeFixture::class,
        PointFixture::class,
    ];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('node.php');
    }
}
