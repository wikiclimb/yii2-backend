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

    public function indexAsNonUser (ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer fake-user-access-token');
        $I->sendGET('users');

        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();


        $I->expectTo('receive a 401 unauthorized response');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('Unauthorized');
    }

    public function indexAsNonAuthorizedUser (ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendGET('users');

        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();

        $I->expectTo('receive a 403 forbidden response');
        $I->seeResponseCodeIs(403);
        $I->seeResponseContains('Forbidden');
    }

    public function viewAsAdmin (ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-1-access-token');
        $I->sendGET('users/2');

        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();

        $I->expectTo('see user-2');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('"username": "user-2"');
    }

    public function viewSelfDetails (ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendGET('users/2');

        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();

        $I->expectTo('see my own details');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('"username": "user-2"');
    }

    public function createAsAdmin (ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-1-access-token');
        $I->sendPOST('users', [
            'username' => 'user-four',
            'password' => 'password_0',
            'email' => 'user-4@wkc.com',
        ]);

        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();

        $I->expectTo('receive a 201 created response');
        $I->seeResponseCodeIs(201);
        $I->seeResponseContains('user-four');
    }

    public function createAsNonAdmin (ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendPOST('users', [
            'username' => 'user-four',
            'password' => 'password_0',
            'email' => 'user-4@wkc.com',
        ]);

        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();

        $I->expectTo('receive a 403 forbidden response');
        $I->seeResponseCodeIs(403);
        $I->seeResponseContains('Forbidden');
    }

    public function updateAsNonAdmin (ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendPATCH('users/3',[
            'username' => 'new-fancy-name-user-three'
        ]);

        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();

        $I->expectTo('receive a 403 forbidden response');
        $I->seeResponseCodeIs(403);
        $I->seeResponseContains('Forbidden');
    }

    public function updateSelf (ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendPATCH('users/2',[
            'username' => 'new-fancy-name-user-three',
            'email' => 'new-email@example.com',
            'status' => 9,
        ]);

        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();

        $I->expectTo('receive a 200 response');
        $I->seeResponseCodeIs(200);
        $I->expectTo('only see the email updated receive a 200 response');
        $I->seeResponseContainsJson([
            'id' => 2,
            'username' => 'user-2',
            'email' => 'new-email@example.com',
            'status' => 10,
        ]);
    }

    public function deleteAsAdmin (ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-1-access-token');
        $I->sendDELETE('users/2');
        $I->expectTo('receive a 204 empty response');
        $I->seeResponseCodeIs(204);
    }

    public function deleteAsNonAdmin (ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendDELETE('users/3');
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('receive a 403 forbidden response');
        $I->seeResponseCodeIs(403);
        $I->seeResponseContains('Forbidden');
    }
}
