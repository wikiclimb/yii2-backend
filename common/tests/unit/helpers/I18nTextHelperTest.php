<?php

namespace common\tests\unit\helpers;

use Codeception\Test\Unit;
use common\helpers\I18nTextHelper;
use common\models\I18nText;

/**
 * Class I18nTextHelperTest
 * @package common\tests\unit\helpers
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class I18nTextHelperTest extends Unit
{
    public function testParseToModel()
    {
        $s = 'Hello world';
        $result = I18nTextHelper::parseToModel($s);
        expect($result)->notNull();
        expect($result->default)->equals($s);
        expect($result)->isInstanceOf(I18nText::class);
    }
}
