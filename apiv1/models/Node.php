<?php

namespace apiv1\models;

use common\helpers\NodeHelper;
use common\models\NodeImage;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * Class Node
 * @package apiv1\models
 * @author Raul Sauco <sauco.raul@gmail.com>
 *
 * @property-read ActiveQuery $images
 */
class Node extends \common\models\Node
{


    /**
     * Gets query for [[Image]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getImages(): ActiveQuery
    {
        return $this->hasMany(
            Image::class, ['id' => 'image_id'])
            ->viaTable('node_image', ['node_id' => 'id']);
    }

    public function fields(): array
    {
        $fields = parent::fields();
        $fields['name'] = static function (Node $model) {
            return $model->name->toString();
        };
        $fields['type'] = static function (Node $model) {
            return $model->nodeType?->name?->default;
        };
        $fields['description'] = static function (Node $model) {
            return $model->description->toString();
        };
        $fields['breadcrumbs'] = static function (Node $model) {
            return NodeHelper::getNameBreadcrumbs($model);
        };
        $fields['cover_url'] = static function (Node $model) {
            $nodeImage = NodeImage::findOne([
                'node_id' => $model->id,
                'is_cover' => true,
            ]);
            return $nodeImage?->image->file_name;
        };
        $fields['rating'] = static function (Node $model) {
            return NodeHelper::getRating($model);
        };
        $fields['created_by'] = static function (Node $model) {
            return $model->createdBy?->username ?? '';
        };
        $fields['updated_by'] = static function (Node $model) {
            return $model->updatedBy?->username ?? '';
        };
        if (YII_DEBUG) {
            ksort($fields);
        }
        return $fields;
    }
}
