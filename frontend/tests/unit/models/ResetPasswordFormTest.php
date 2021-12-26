<?php

namespace frontend\tests\unit\models;

use common\fixtures\AuthFixture;
use common\fixtures\UserFixture;
use frontend\models\ResetPasswordForm;
use frontend\tests\UnitTester;

class ResetPasswordFormTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            AuthFixture::class,
            'user' => UserFixture::class,
        ]);
    }

    public function testResetWrongToken()
    {
        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function () {
            new ResetPasswordForm('');
        });

        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function () {
            new ResetPasswordForm('notexistingtoken_1391882543');
        });
    }

    public function testResetCorrectToken()
    {
        $user = $this->tester->grabFixture('user', 0);
        $form = new ResetPasswordForm($user['password_reset_token']);
        expect_that($form->resetPassword());
    }

}
