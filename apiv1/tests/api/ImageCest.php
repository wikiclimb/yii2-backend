<?php

namespace api\test\api;

use apiv1\tests\ApiTester;
use common\fixtures\NodeImageFixture;

/**
 * Class ImageCest
 * @package api\test\api
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class ImageCest
{
    public function _fixtures(): array
    {
        return [
            NodeImageFixture::class,
        ];
    }

    public function indexAsGuest(ApiTester $I)
    {
        $I->amGoingTo('request a list of images');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('images');
        $I->expect('a 200 response code');
        $I->seeResponseCodeIs(200);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('see image-1');
        $I->seeResponseContainsJson([
            [
                'id' => 1,
                'name_id' => 201,
                'description_id' => 201,
                'file_name' => 'image-1.jpg',
                'name' => 'image-1-name',
                'description' => 'image-1-description',
                'validated' => true,
            ],
            [
                'id' => 2,
                'name' => 'image-2-name',
            ],
            [
                'id' => 3,
                'name' => 'image-3-name',
            ],
        ]);
    }


    public function indexForNodeAsGuest(ApiTester $I)
    {
        $I->amGoingTo('request a list of images for a node');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('images?node-id=4');
        $I->expect('a 200 response code');
        $I->seeResponseCodeIs(200);
        $I->expect('the response to be JSON');
        $I->seeResponseIsJson();
        $I->expectTo('see image-1');
        $I->seeResponseContainsJson([
            [
                'id' => 1,
                'name_id' => 201,
                'description_id' => 201,
                'file_name' => 'image-1.jpg',
                'name' => 'image-1-name',
                'description' => 'image-1-description',
                'validated' => true,
            ],
            [
                'id' => 2,
                'name' => 'image-2-name',
                'validated' => true,
            ],
        ]);
        $I->expect('not see images not linked to the node');
        $I->dontSeeResponseContainsJson([
            'id' => 3,
            'name' => 'image-3-name',
        ]);
    }
}
