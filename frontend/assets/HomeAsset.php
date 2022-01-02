<?php

namespace frontend\assets;

use common\assets\ParticlesAsset;
use yii\web\AssetBundle;

/**
 * Class HomeAsset
 * @package frontend\assets
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class HomeAsset extends AssetBundle
{
    public $baseUrl = '@staticUrl';
    public $css = [
        'css/home.css',
    ];
    public $js = [
        'js/frontend-home.js',
    ];
    public $depends = [
//        AppAsset::class,
        ParticlesAsset::class,
    ];
}
