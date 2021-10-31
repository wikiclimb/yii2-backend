<?php

namespace api\test\api;

use apiv1\tests\ApiTester;
use common\fixtures\AuthFixture;
use common\fixtures\NodeFixture;
use common\models\Node;

/**
 * Class NodeCest
 * @package api\test\api
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class NodeCest
{
    public function _fixtures(): array
    {
        return [
            NodeFixture::class,
            AuthFixture::class,
        ];
    }

    public function indexAsGuest(ApiTester $I)
    {
        $I->amGoingTo('request a list of nodes as a guest user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('nodes');
        $I->expect('a 200 response code');
        $I->seeResponseCodeIs(200);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('see node-1');
        $I->seeResponseContainsJson([
            'id' => 1,
            'node_type_id' => 1,
            'name_id' => 100,
        ]);
    }

    public function indexAsUser(ApiTester $I)
    {
        $I->amGoingTo('request a list of nodes as user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendGet('nodes');
        $I->expect('a 200 response code');
        $I->seeResponseCodeIs(200);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('see node-1');
        $I->seeResponseContainsJson([
            'id' => 1,
            'node_type_id' => 1,
            'name_id' => 100,
        ]);
    }

    public function viewAsGuest(ApiTester $I)
    {
        $I->amGoingTo('request a node as a guest user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('nodes/1');
        $I->expect('a 200 response code');
        $I->seeResponseCodeIs(200);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('see node-1');
        $I->seeResponseContainsJson([
            'id' => 1,
            'node_type_id' => 1,
            'name_id' => 100,
        ]);
    }

    public function createAsGuest(ApiTester $I)
    {
        $I->amGoingTo('try to create a new node as a guest user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('nodes', [
            'node_type_id' => Node::TYPE_AREA,
            'name_id' => 100,
        ]);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('receive a 401 unauthorized response');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('Unauthorized');
    }

    public function createAsUser(ApiTester $I)
    {
        $nodeData = [
            'node_type_id' => Node::TYPE_AREA,
            'name_id' => 100,
        ];
        $I->amGoingTo('try to create a new node as a guest user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendPost('nodes', $nodeData);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('receive a 201 created response');
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson($nodeData);
    }

    public function updateAsGuest(ApiTester $I)
    {
        $I->amGoingTo('try to create a new node as a guest user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPatch('nodes/1', [
            'name_id' => 101,
        ]);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('receive a 401 unauthorized response');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('Unauthorized');
    }

    public function updateAsUser(ApiTester $I)
    {
        $nodeData = [
            'node_type_id' => Node::TYPE_ROUTE,
            'name_id' => 101,
        ];
        $I->amGoingTo('try to create a new node as a user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendPatch('nodes/1', $nodeData);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('receive a 403 forbidden response');
        $I->seeResponseCodeIs(403);
        $I->seeResponseContains('Forbidden');
    }

    public function updateAsAdmin(ApiTester $I)
    {
        $nodeData = [
            'node_type_id' => Node::TYPE_ROUTE,
            'name_id' => 101,
        ];
        $I->amGoingTo('try to create a new node as an admin');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-1-access-token');
        $I->sendPatch('nodes/1', $nodeData);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('receive a 200 success response');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson($nodeData);
    }

    public function deleteAsGuest(ApiTester $I)
    {
        $I->amGoingTo('try to delete a node as a guest user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDelete('nodes/1');
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('receive a 401 unauthorized response');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('Unauthorized');
    }

    public function deleteAsUser(ApiTester $I)
    {
        $I->amGoingTo('try to delete a new node as a user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendDelete('nodes/1');
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('receive a 403 forbidden response');
        $I->seeResponseCodeIs(403);
        $I->seeResponseContains('Forbidden');
    }

    public function deleteAsAdmin(ApiTester $I)
    {
        $I->amGoingTo('try to delete a new node as an admin');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-1-access-token');
        $I->sendDelete('nodes/1');
        $I->expectTo('receive a 204 success (empty) response');
        $I->seeResponseCodeIs(204);
    }
}