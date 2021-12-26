<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = 'https://wikiclimb.org/site/reset-password?token=' .
    $user->password_reset_token;
//$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Hello <?= $user->username ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
