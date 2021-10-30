<?php

namespace api\test\api;

use apiv1\tests\ApiTester;
use common\fixtures\AuthFixture;

class LoginCest
{
    public function _fixtures(): array
    {
        return [
            AuthFixture::class,
        ];
    }

    public function loginSuccess(ApiTester $I)
    {
        $I->sendPost('login', [
            'username' => 'user-1',
            'password' => 'password_0',
        ]);
        $I->expectTo('see a successful response');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'error' => false,
            'message' => 'OK',
            'credentials' => [
                'username' => 'user-1',
                'accessToken' => 'user-1-access-token',
            ],
        ]);
    }

    public function loginError(ApiTester $I)
    {
        $I->sendPost('login', [
            'username' => 'user-1',
            'password' => 'wrong_password',
        ]);
        $I->expectTo('see a successful response');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'error' => true,
            'message' => 'Authentication Failure',
        ]);
    }
}