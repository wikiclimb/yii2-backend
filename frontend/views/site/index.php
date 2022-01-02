<?php

/* @var $this yii\web\View */

use frontend\assets\HomeAsset;
use yii\bootstrap4\Html;

$this->title = Yii::t('app', 'WikiClimb');
HomeAsset::register($this);
?>
<div id="half-dome-background"></div>
<div id="particles-js"></div>
<div id="site-index">
    <div class="main-banner">
        <div class="main-logo">
            <?= Html::img('@imgUrl/wikiclimb-logo.png') ?>
        </div>
        <div class="banner-header">
            <h1><?= Yii::t('app', 'WikiClimb') ?></h1>
            <h2><?= Yii::t('app', 'The Home of Climbing Information') ?></h2>
        </div>
    </div>
    <div class="body-content"></div>
</div>
