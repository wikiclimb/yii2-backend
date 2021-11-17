<?php

namespace apiv1\models;

use common\helpers\NodeHelper;

/**
 * Class Node
 * @package apiv1\models
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class Node extends \common\models\Node
{
    public function fields(): array
    {
        $fields = parent::fields();
        $fields['name'] = static function (Node $model) {
            return $model->name->toString();
        };
        $fields['description'] = static function (Node $model) {
            return $model->description->toString();
        };
        $fields['breadcrumbs'] = static function (Node $model) {
            return NodeHelper::getNameBreadcrumbs($model);
        };
        $fields['cover_url'] = static function (Node $model) {
            return '';
        };
        $fields['rating'] = static function (Node $model) {
            return '';
        };
        $fields['created_by'] = static function (Node $model) {
            return $model->createdBy?->username ?? '';
        };
        $fields['updated_by'] = static function (Node $model) {
            return $model->updatedBy?->username ?? '';
        };
        return $fields;
    }
}
