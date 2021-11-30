<?php

/* @var $this yii\web\View */
/* @var $success bool */

$this->title = Yii::t('app', 'Email verification');
$banner = $success
    ? Yii::t('app', 'Congratulations!')
    : Yii::t('app', 'Verification failed!');
$lead = $success
    ? Yii::t('app', 'Your email has been verified.')
    : Yii::t('app', 'There was an error verifying your email address..');
$body = $success
    ? Yii::t('app', 'Return to the application and log in using your credentials.')
    : Yii::t('app', 'Return to the application and request another token.');
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4"><?= $banner ?></h1>

        <p class="lead"><?= $lead ?></p>

        <p><?= $body ?></p>
    </div>

</div>
