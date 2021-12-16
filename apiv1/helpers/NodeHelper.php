<?php

namespace apiv1\helpers;

use apiv1\models\Image;
use apiv1\models\Node;
use common\helpers\I18nStringHelper;
use common\helpers\I18nTextHelper;
use common\models\NodeImage;
use common\models\Point;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class NodeHelper
 * @package apiv1\helpers
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class NodeHelper
{
    /**
     * Fetch Nodes that are link with a point that falls between the LatLng bounds given.
     * @param $params
     * @return ActiveDataProvider
     */
    public static function searchWithinBounds($params): ActiveDataProvider
    {
        $query = Node::find()->joinWith('point')
            ->where([
                    'between',
                    'point.lat',
                    (float)$params['south'],
                    (float)$params['north']]
            )->andWhere([
                'between',
                'point.lng',
                (float)$params['west'],
                (float)$params['east']
            ]);
        if (isset($params['exclude']) && strlen($params['exclude']) > 0) {
            $query->andWhere(['not in', 'node.id', explode(',', $params['exclude'])]);
        }
        if (($type = Yii::$app->request->get('type')) !== null) {
            $query->andWhere(['node_type_id' => $type]);
        }
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [1, 1000],
                'defaultPageSize' => 1000,
                'params' => $params,
            ],
        ]);
    }

    /**
     * Load all values from parameters into node instance.
     * @param Node $node
     * @param array $params
     * @return bool
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws BadRequestHttpException
     */
    public static function load(Node $node, array $params): bool
    {
        self::loadDirectAttributes($node, $params);
        self::loadParentId($node, $params);
        return self::loadI18NStrings($node, $params)
            && self::loadCoverImage($node, $params)
            && self::loadGeolocationData($node, $params)
            && $node->save();
    }

    /**
     * Load all values not linked to any relation.
     * @param Node $node
     * @param array $params
     */
    public static function loadDirectAttributes(Node $node, array $params): void
    {
        $attrs = ['node_type_id'];
        foreach ($attrs as $attr) {
            if (isset($params[$attr]) && !empty($params[$attr])) {
                $node->setAttribute($attr, $params[$attr]);
            }
        }
    }

    /**
     * Load a parent ID if the parameters contain a valid value and the node is a new node,
     * ignore the call otherwise.
     */
    public static function loadParentId(Node $node, array $params): void
    {
        if (isset($params['parent_id']) && !empty($params['parent_id'])
            && ($parent = Node::findOne($params['parent_id'])) !== null
            && $node->isNewRecord) {
            $node->parent_id = $parent->id;
        }
    }

    /**
     * Let users update the cover image simply sending the file name of an existing
     * node image linked to this node.
     * @param Node $node
     * @param array $params
     * @return bool
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     */
    public static function loadCoverImage(Node $node, array $params): bool
    {
        // check if update is needed
        if (!isset($params['cover_url']) || !is_string($params['cover_url'])
            || ($newFileName = trim($params['cover_url'])) === '') {
            return true;
        }
        $newCover = $params['cover_url'];
        $oldCover = NodeImage::findOne([
            'node_id' => $node->id, 'is_cover' => true,
        ]);
        if ($oldCover !== null && $oldCover->image->file_name === $newFileName) {
            return true;
        }
        if (($image = Image::findOne(['file_name' => trim($newCover)])) === null) {
            throw new BadRequestHttpException(
                Yii::t('app',
                    'Could not find image for file name {{fileName}}',
                    ['fileName' => trim($newCover)])
            );
        }
        if (($nodeImage = NodeImage::findOne([
                'node_id' => $node->id, 'image_id' => $image->id,
            ])) === null) {
            throw new BadRequestHttpException(
                Yii::t('app',
                    'Could not find image for file name {{fileName}}',
                    ['fileName' => trim($newCover)])
            );
        }
        if ($oldCover !== null) {
            $oldCover->is_cover = 0;
            if (!$oldCover->save()) {
                throw new ServerErrorHttpException(
                    Yii::t('app', 'Error updating node cover information')
                );
            }
        }
        $nodeImage->is_cover = 1;
        return $nodeImage->save();
    }

    /**
     * Load name and description from params into the node model.
     * @param Node $node
     * @param array $params
     * @return bool
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public static function loadI18NStrings(Node $node, array $params): bool
    {
        return self::loadName($node, $params) && self::loadDescription($node, $params);
    }

    /**
     * Update a node's name value based on the parameters given to the method.
     * @param Node $node
     * @param array $params
     * @return bool
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public static function loadName(Node $node, array $params): bool
    {
        if (($name = $node->name) !== null) {
            if (isset($params['name'])
                && !empty($params['name'])
                && $params['name'] !== $name->default) {
                $name->default = $params['name'];
                if (!$name->save()) {
                    throw new ServerErrorHttpException(
                        'Failed to update Node name'
                    );
                }
            }
        } else {
            if (($node->name_id = I18nStringHelper::parseToModel(
                    $params['name'] ?? '')?->id) === null) {
                throw new UnprocessableEntityHttpException(
                    'Name value is not valid.'
                );
            }
            if (!$node->save()) {
                throw new ServerErrorHttpException(
                    'Failed to set Node name'
                );
            }
        }
        return true;
    }

    /**
     * Update a node's description value based on the parameters given to
     * the method.
     * @param Node $node
     * @param array $params
     * @return bool
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public static function loadDescription(Node $node, array $params): bool
    {
        if (($description = $node->description) !== null) {
            if (isset($params['description'])
                && !empty($params['description'])
                && $params['description'] !== $description->default) {
                $description->default = $params['description'];
                if (!$description->save()) {
                    throw new ServerErrorHttpException(
                        'Failed to update Node description'
                    );
                }
            }
        } else {
            if (($node->description_id = I18nTextHelper::parseToModel(
                    $params['description'] ?? '')?->id) === null) {
                throw new UnprocessableEntityHttpException(
                    'Description value is not valid.'
                );
            }
            if (!$node->save()) {
                throw new ServerErrorHttpException(
                    'Failed to set Node description'
                );
            }
        }
        return true;
    }

    /**
     * Update a Node instance with geolocation information contained on the given parameter array.
     * @param Node $node
     * @param array $params
     * @return bool
     * @throws ServerErrorHttpException
     */
    public static function loadGeolocationData(Node $node, array $params): bool
    {
        if (isset($params['lat']) && isset($params['lng'])
            && !empty($params['lat']) && !empty($params['lng'])) {
            if (($point = $node->point) !== null) {
                $touched = false;
                if ($point->lat !== $params['lat']) {
                    $point->lat = $params['lat'];
                    $touched = true;
                }
                if ($point->lng !== $params['lng']) {
                    $point->lng = $params['lng'];
                    $touched = true;
                }
                if ($touched && !$point->save()) {
                    throw new ServerErrorHttpException(
                        Yii::t('app',
                            'Failed to update point {{point}} with lat {{lat}} and lng {{lng}}',
                            ['point' => $point->id, 'lat' => $params['lat'], 'lng' => $params['lng']])
                    );
                }
            } else {
                $point = new Point();
                $point->lat = $params['lat'];
                $point->lng = $params['lng'];
                if (!$point->save()) {
                    $message = Yii::t('app',
                        'Failed to update point {{point}} with lat {{lat}} and lng {{lng}}',
                        ['point' => $point->id, 'lat' => $params['lat'], 'lng' => $params['lng']]
                    );
                    Yii::error($message, __METHOD__);
                    throw new ServerErrorHttpException($message);
                }
                $node->point_id = $point->id;
                if (!$node->save()) {
                    $message = Yii::t('app',
                        'Failed to update node point to {{point}} with lat {{lat}} and lng {{lng}}',
                        ['point' => $point->id, 'lat' => $params['lat'], 'lng' => $params['lng']]
                    );
                    Yii::error($message, __METHOD__);
                    throw new ServerErrorHttpException($message);
                }
            }
            return true;
        }
        // If the parameters are not set, just ignore the request.
        return true;
    }
}
