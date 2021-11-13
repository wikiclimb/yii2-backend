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
            'id' => 1,
            'username' => 'user-1',
            'token' => 'user-1-access-token',
        ]);
    }

    public function loginError(ApiTester $I)
    {
        $I->sendPost('login', [
            'username' => 'user-1',
            'password' => 'wrong_password',
        ]);
        $I->expectTo('see a 401 response');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContainsJson([
            'message' => 'Authentication Failure',
        ]);
    }
}
