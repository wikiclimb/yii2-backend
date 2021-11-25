<?php


namespace apiv1\models;

/**
 * Class Image
 * @package apiv1\models
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class Image extends \common\models\Image
{
    public function fields(): array
    {
        $fields = parent::fields();
        $fields['name'] = static function (Image $model) {
            return $model->name->toString();
        };
        $fields['description'] = static function (Image $model) {
            return $model->description?->toString() ?? '';
        };
        $fields['created_by'] = static function (Image $model) {
            return $model->createdBy?->username ?? '';
        };
        $fields['updated_by'] = static function (Image $model) {
            return $model->updatedBy?->username ?? '';
        };
        return $fields;
    }
}
