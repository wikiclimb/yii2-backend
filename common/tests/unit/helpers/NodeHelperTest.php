<?php

namespace common\tests\unit\models;

use Codeception\Test\Unit;
use common\fixtures\AuthFixture;
use common\fixtures\NodeFixture;
use common\fixtures\NodeRatingFixture;
use common\helpers\NodeHelper;
use common\models\Node;

/**
 * Login form test
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
            NodeRatingFixture::class,
        ];
    }

    public function testBreadcrumbGeneration()
    {
        $node = Node::findOne(4);
        expect($node)->notNull();
        $breadcrumbs = NodeHelper::getNameBreadcrumbs($node);
        expect($breadcrumbs)->equals(['node-1', 'node-2', 'node-3']);
    }

    public function testRatingCalculation()
    {
        $node = Node::findOne(4);
        expect($node)->notNull();
        $rating = NodeHelper::getRating($node);
        $expected = round((4 + 2 + 3 + 5) / 4.0, 1);
        expect($rating)->equals($expected);
    }
}
