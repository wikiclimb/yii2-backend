<?php

namespace common\fixtures;

use common\models\Image;
use yii\base\InvalidConfigException;
use yii\test\ActiveFixture;

/**
 * Class ImageFixture
 * @package common\fixtures
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class ImageFixture extends ActiveFixture
{
    public $modelClass = Image::class;

    public $depends = [
        I18nStringFixture::class,
        I18nTextFixture::class,
    ];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir('image.php');
    }
}
