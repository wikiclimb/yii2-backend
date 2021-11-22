<?php

namespace common\helpers;

use common\models\I18nText;
use Yii;

/**
 * Class I18nTextHelper
 *
 * Helper methods related to the I18nText model.
 * @package common\helpers
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class I18nTextHelper
{
    /**
     * Try to create a new model.
     * @param string $text
     * @return I18nText|null
     */
    static function parseToModel(string $text): ?I18nText
    {
        $i18Text = new I18nText();
        $i18Text->default = empty($text)
            ? Yii::t('app', 'This descripton needs to be updated') : $text;
        if ($i18Text->save()) {
            return $i18Text;
        }
        Yii::error($i18Text->errors, __METHOD__);
        return null;
    }
}
