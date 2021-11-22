<?php

namespace common\tests\unit\helpers;

use Codeception\Test\Unit;
use common\helpers\I18nStringHelper;
use common\models\I18nString;

/**
 * Class I18nStringHelperTest
 * @package common\tests\unit\helpers
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class I18nStringHelperTest extends Unit
{
    public function testParseToModel()
    {
        $t = 'Hello world';
        $result = I18nStringHelper::parseToModel($t);
        expect($result)->notNull();
        expect($result->default)->equals($t);
        expect($result)->isInstanceOf(I18nString::class);
    }
}
