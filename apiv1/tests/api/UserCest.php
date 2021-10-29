<?php

namespace api\test\api;

use apiv1\tests\ApiTester;
use common\fixtures\AuthFixture;

class UserCest
{
    public function _fixtures(): array
    {
        return [
            AuthFixture::class,
        ];
    }

    public function indexAsAdmin (ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-1-access-token');
        $I->sendGET('users');
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('see user-1');
        $I->seeResponseContains('"username": "user-1"');
    }

//    public function indexAsNonUser (ApiTester $I)
//    {
//        $I->haveHttpHeader('Content-Type', 'application/json');
//        $I->haveHttpHeader('Authorization', 'Bearer fake-user-access-token');
//        $I->sendGET('users');
//
//        $I->expect('the response to be JSON');
//        $I->seeResponseIsJson();
//
//
//        $I->expectTo('receive a 401 unauthorized response');
//        $I->seeResponseCodeIs(401);
//        $I->seeResponseContains('Unauthorized');
//    }

//    public function indexAsNonAuthorizedUser (ApiTester $I)
//    {
//        $I->haveHttpHeader('Content-Type', 'application/json');
//        $I->haveHttpHeader('Authorization', 'Bearer user-three-access-token');
//        $I->sendGET('users');
//
//        $I->expect('the response to be JSON');
//        $I->seeResponseIsJson();
//
//        $I->expectTo('receive a 403 forbidden response');
//        $I->seeResponseCodeIs(403);
//        $I->seeResponseContains('Forbidden');
//    }

//    public function viewAsAdmin (ApiTester $I)
//    {
//        $I->haveHttpHeader('Content-Type', 'application/json');
//        $I->haveHttpHeader('Authorization', 'Bearer user-one-access-token');
//        $I->sendGET('users/3');
//
//        $I->expect('the response to be JSON');
//        $I->seeResponseIsJson();
//
//        $I->expectTo('see user-three');
//        $I->seeResponseCodeIs(200);
//        $I->seeResponseContains('"username": "user-three"');
//    }

    // TODO add userIsThisUserRule and allow users to see their own details

//    public function createAsAdmin (ApiTester $I)
//    {
//        $I->haveHttpHeader('Content-Type', 'application/json');
//        $I->haveHttpHeader('Authorization', 'Bearer user-three-access-token');
//        $I->sendPOST('users', [
//            'username' => 'user-four',
//            'password' => 'password_0',
//            'password_repeat' => 'password_0'
//        ]);
//
//        $I->expect('the response to be JSON');
//        $I->seeResponseIsJson();
//
//        $I->expectTo('receive a 403 forbidden response');
//        $I->seeResponseCodeIs(403);
//        $I->seeResponseContains('Forbidden');
//    }

//    public function updateAsAdmin (ApiTester $I)
//    {
//        $I->haveHttpHeader('Content-Type', 'application/json');
//        $I->haveHttpHeader('Authorization', 'Bearer user-three-access-token');
//        $I->sendPATCH('users/3',[
//            'username' => 'new-fancy-name-user-three'
//        ]);
//
//        $I->expect('the response to be JSON');
//        $I->seeResponseIsJson();
//
//        $I->expectTo('receive a 403 forbidden response');
//        $I->seeResponseCodeIs(403);
//        $I->seeResponseContains('Forbidden');
//    }

//    public function deleteAsAdmin (ApiTester $I)
//    {
//        $I->haveHttpHeader('Content-Type', 'application/json');
//        $I->haveHttpHeader('Authorization', 'Bearer user-three-access-token');
//        $I->sendDELETE('users/3');
//
//        $I->expect('the response to be JSON');
//        $I->seeResponseIsJson();
//
//        $I->expectTo('receive a 403 forbidden response');
//        $I->seeResponseCodeIs(403);
//        $I->seeResponseContains('Forbidden');
//    }
}
