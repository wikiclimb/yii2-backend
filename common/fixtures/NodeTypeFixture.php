<?php

namespace common\fixtures;

use common\models\NodeType;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

/**
 * Class NodeTypeFixture
 * @package common\fixtures
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class NodeTypeFixture extends ActiveFixture
{
    public $modelClass = NodeType::class;

    public $depends = [
        I18nStringFixture::class,
        I18nTextFixture::class,
        AuthFixture::class,
    ];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('node_type.php');
    }
}
