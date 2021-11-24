<?php

namespace api\test\api;

use apiv1\tests\ApiTester;
use common\fixtures\AuthFixture;
use common\fixtures\NodeFixture;
use common\fixtures\NodeImageFixture;
use common\fixtures\NodeRatingFixture;
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
            NodeRatingFixture::class,
            NodeImageFixture::class,
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
            'name_id' => 101,
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
            'name_id' => 101,
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
            'type' => 'Area',
            'name_id' => 101,
            'cover_url' => null,
        ]);
    }

    public function viewAsGuestShowsCustomFields(ApiTester $I)
    {
        $I->amGoingTo('request a node as a guest user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('nodes/4');
        $I->expect('a 200 response code');
        $I->seeResponseCodeIs(200);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('see node-4 data');
        $I->seeResponseContainsJson([
            'id' => 4,
            'type' => 'Area',
            'name' => 'node-4-name',
            'description' => 'node-4-description',
            'breadcrumbs' => ['area-1-name', 'area-2-name', 'area-3-name',],
            'node_type_id' => Node::TYPE_AREA,
            'name_id' => 104,
            'rating' => 3.5,
            'cover_url' => 'image-2.jpg',
        ]);
    }

    public function createAsGuest(ApiTester $I)
    {
        $I->amGoingTo('try to create a new node as a guest user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('nodes', [
            'node_type_id' => Node::TYPE_AREA,
            'type' => 4,
            'name_id' => 101,
            'description_id' => 101,
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
            'name' => 'created-node-name',
            'type' => 4,
            'description' => 'created-node-description',
        ];
        $I->amGoingTo('try to create a new node as a guest user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendPost('nodes', $nodeData);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('receive a 201 created response');
        $I->seeResponseCodeIs(201);
        $nodeData['type'] = 'Area';
        $I->seeResponseContainsJson($nodeData);
    }

    public function createAsUserWithEmptyName(ApiTester $I)
    {
        $nodeData = [
            'node_type_id' => Node::TYPE_AREA,
//            'name' => 'created-node-name',
            'description' => 'created-node-description',
        ];
        $I->amGoingTo('try to create a new node as a guest user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendPost('nodes', $nodeData);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('receive a 422 response');
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('Name value is not valid.');
    }

    public function createAsUserWithEmptyDescription(ApiTester $I)
    {
        $nodeData = [
            'node_type_id' => Node::TYPE_AREA,
            'name' => 'created-node-name',
//            'description' => 'created-node-description',
        ];
        $I->amGoingTo('try to create a new node as a guest user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendPost('nodes', $nodeData);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('receive a 201 created response');
        $I->seeResponseCodeIs(201);
        $nodeData['description'] = 'This descripton needs to be updated';
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

    public function update404AsUser(ApiTester $I)
    {
        $nodeData = [
            'id' => 1,
            'name' => 'area-1-updated-name',
            'description' => 'area-1-updated-description',
        ];
        $I->amGoingTo('try to update node that does not exist');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendPatch('nodes/99999', $nodeData);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('receive a 404 response');
        $I->seeResponseCodeIs(404);
    }

    public function updateAsUser(ApiTester $I)
    {
        $nodeData = [
            'id' => 1,
            'name' => 'area-1-updated-name',
            'description' => 'area-1-updated-description',
        ];
        $I->amGoingTo('try to update node as a user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-2-access-token');
        $I->sendPatch('nodes/1', $nodeData);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('receive a 200 success response');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson($nodeData);
    }

    public function updateAsAdmin(ApiTester $I)
    {
        $nodeData = [
            'id' => 1,
            'name' => 'area-1-updated-name',
            'description' => 'area-1-updated-description',
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
        $I->sendDelete('nodes/4');
        $I->expectTo('receive a 204 success (empty) response');
        $I->seeResponseCodeIs(204);
    }

    public function deleteAsAdmin(ApiTester $I)
    {
        $I->amGoingTo('try to delete a new node as an admin');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer user-1-access-token');
        $I->sendDelete('nodes/4');
        $I->expectTo('receive a 204 success (empty) response');
        $I->seeResponseCodeIs(204);
    }
}
