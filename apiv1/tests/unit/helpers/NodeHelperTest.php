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

    /**
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function testLoadNewNode()
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
}
