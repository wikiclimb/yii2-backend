<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Class ParticlesAsset
 * @package backend\assets
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class ParticlesAsset extends AssetBundle
{
    public $sourcePath = '@npm/particles.js/';
    public $js = [
        'particles.js',
//        'https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js',
//        'https://threejs.org/examples/js/libs/stats.min.js',
    ];
}
