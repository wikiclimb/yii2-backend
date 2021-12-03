<?php

namespace apiv1\tests\unit\helpers;

use apiv1\helpers\NodeHelper;
use apiv1\models\Node;
use Codeception\Test\Unit;
use common\fixtures\AuthFixture;
use common\fixtures\NodeFixture;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class NodeHelperTest
 * @package apiv1\tests\unit\helpers
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class NodeHelperTest extends Unit
{
    /**
     * @return array
     */
    public function _fixtures(): array
    {
        return [
            AuthFixture::class,
            NodeFixture::class,
        ];
    }

    public function testSearchWithinBounds()
    {
        $params = [
            'type' => 1,
            'bounded' => true,
            'north' => 23.14,
            'east' => 135.19,
            'south' => 22.05,
            'west' => 133.03,
        ];
        $dataProvider = NodeHelper::searchWithinBounds($params);
        $result = $dataProvider->keys;
        // 8 fits within bounds.
        expect($result)->contains(8);
        // 1 does not have geo info.
        expect($result)->notContains(1);
        // 6 falls outside the given bounds.
        expect($result)->notContains(6);
    }

    public function testLoadNewEmptyNode()
    {
        $lat = 24.024;
        $lng = 125.404;
        $params = [
            'node_type_id' => 2,
            'name' => 'test-name',
            'description' => 'test-description',
            'lat' => $lat,
            'lng' => $lng,
        ];
        $node = new Node();
        expect($node->id)->isEmpty();
        expect(NodeHelper::load($node, $params));
        expect($node->id)->notEmpty();
        expect($node->node_type_id)->equals(2);
        expect($node->name_id)->notEmpty();
        expect($node->name)->notNull();
        expect($node->name->default)->equals($params['name']);
        expect($node->description_id)->notEmpty();
        expect($node->description)->notNull();
        expect($node->description->default)->equals($params['description']);
        $point = $node->point;
        expect($point)->notNull();
        expect($point->lat)->equals($lat);
        expect($point->lng)->equals($lng);
    }

    /**
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function testLoadExistingNodeNewStrings()
    {
        $params = [
            'name' => 'test-name',
            'description' => 'test-description',
        ];
        $node = Node::findOne(4);
        expect($node)->notNull();
        expect(NodeHelper::load($node, $params));
        expect($node->name_id)->notEmpty();
        expect($node->name)->notNull();
        expect($node->name->default)->equals($params['name']);
        expect($node->description_id)->notEmpty();
        expect($node->description)->notNull();
        expect($node->description->default)->equals($params['description']);
    }

    /**
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function testLoadExistingNode()
    {
        $params = [
            'name' => 'test-name',
            'description' => 'test-description',
        ];
        $node = Node::findOne(4);
        expect($node)->notNull();
        expect(NodeHelper::load($node, $params));
        expect($node->name_id)->notEmpty();
        expect($node->name)->notNull();
        expect($node->name->default)->equals($params['name']);
        expect($node->description_id)->notEmpty();
        expect($node->description)->notNull();
        expect($node->description->default)->equals($params['description']);
    }


    /**
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function testLoadExistingNodeWithExistingGpsData()
    {
        $lat = 24.024;
        $lng = 125.404;
        $params = [
            'name' => 'test-name',
            'description' => 'test-description',
            'lat' => $lat,
            'lng' => $lng,
        ];
        $node = Node::findOne(6);
        expect($node)->notNull();
        expect(NodeHelper::load($node, $params));
        expect($node->name_id)->notEmpty();
        expect($node->name)->notNull();
        expect($node->name->default)->equals($params['name']);
        expect($node->description_id)->notEmpty();
        expect($node->description)->notNull();
        expect($node->description->default)->equals($params['description']);
        $point = $node->point;
        expect($point)->notNull();
        expect($point->lat)->equals($lat);
        expect($point->lng)->equals($lng);
    }

    /**
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function testLoadI18NStringsNewNode()
    {
        $params = [
            'name' => 'test-name',
            'description' => 'test-description',
        ];
        $node = new Node();
        expect($node)->notNull();
        expect(NodeHelper::loadI18NStrings($node, $params));
        expect($node->name_id)->notEmpty();
        expect($node->name)->notNull();
        expect($node->name->default)->equals($params['name']);
        expect($node->description_id)->notEmpty();
        expect($node->description)->notNull();
        expect($node->description->default)->equals($params['description']);
    }

    /**
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function testLoadI18NStringsExistingNode()
    {
        $params = [
            'name' => 'test-name',
            'description' => 'test-description',
        ];
        $node = Node::findOne(4);
        expect($node)->notNull();
        expect(NodeHelper::loadI18NStrings($node, $params));
        expect($node->name_id)->notEmpty();
        expect($node->name)->notNull();
        expect($node->name->default)->equals($params['name']);
        expect($node->description_id)->notEmpty();
        expect($node->description)->notNull();
        expect($node->description->default)->equals($params['description']);
    }

    /**
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function testLoadNameNewNode()
    {
        $name = 'test-name';
        $node = new Node();
        expect($node)->notNull();
        expect(NodeHelper::loadName($node, ['name' => $name]));
        expect($node->name_id)->notEmpty();
        expect($node->name)->notNull();
        expect($node->name->default)->equals($name);
    }

    /**
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function testLoadNameExistingNode()
    {
        $name = 'loaded-test-name';
        $node = Node::findOne(4);
        expect($node)->notNull();
        expect(NodeHelper::loadName($node, ['name' => $name]));
        expect($node->name_id)->notEmpty();
        expect($node->name)->notNull();
        expect($node->name->default)->equals($name);
    }

    /**
     * @depends testLoadNameNewNode
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function testLoadDescriptionNewNode()
    {
        $description = 'test-description';
        $node = new Node();
        NodeHelper::loadName($node, ['name' => 'test-name']);
        expect($node)->notNull();
        expect(
            NodeHelper::loadDescription($node, ['description' => $description])
        );
        expect($node->description_id)->notEmpty();
        expect($node->description)->notNull();
        expect($node->description->default)->equals($description);
    }

    /**
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function testLoadDescriptionExistingNode()
    {
        $description = 'loaded-test-description';
        $node = Node::findOne(4);
        expect($node)->notNull();
        expect(
            NodeHelper::loadDescription($node, ['description' => $description])
        );
        expect($node->description_id)->notEmpty();
        expect($node->description)->notNull();
        expect($node->description->default)->equals($description);
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function testRequestWithGeolocationWhenEmpty()
    {
        $lat = 24.024;
        $lng = 125.404;
        $node = Node::findOne(5);
        expect($node)->notNull();
        expect(NodeHelper::loadGeolocationData($node, ['lat' => $lat, 'lng' => $lng]));
        $point = $node->point;
        expect($point)->notNull();
        expect($point->lat)->equals($lat);
        expect($point->lng)->equals($lng);
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function testRequestWithGeolocationWhenNotEmpty()
    {
        $lat = '24.024';
        $lng = '125.404';
        $node = Node::findOne(6);
        expect($node)->notNull();
        expect(($point = $node->point))->notNull();
        expect($point->lat, 22.0);
        expect($point->lng, (float)33);
        expect(NodeHelper::loadGeolocationData($node, ['lat' => $lat, 'lng' => $lng]));
        $point = $node->point;
        expect($point)->notNull();
        expect($point->lat)->equals($lat);
        expect($point->lng)->equals($lng);
    }
}
