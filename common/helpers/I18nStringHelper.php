<?php

namespace common\helpers;

use common\models\I18nString;
use Yii;

/**
 * Class I18nStringHelper
 *
 * Helper methods related to the I18nString model.
 * @package common\helpers
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class I18nStringHelper
{
    /**
     * Try to create a new I18nString given a string value.
     * @param string $string
     * @return I18nString|null
     */
    static function parseToModel(string $string): ?I18nString
    {
        $i18String = new I18nString();
        $i18String->default = $string;
        if ($i18String->save()) {
            return $i18String;
        }
        Yii::error($i18String->errors, __METHOD__);
        return null;
    }
}
