<?php

namespace apiv1\helpers;

use apiv1\models\Node;
use common\helpers\I18nStringHelper;
use common\helpers\I18nTextHelper;
use common\models\Point;
use Yii;
use yii\data\ActiveDataProvider;
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
     */
    public static function load(Node $node, array $params): bool
    {
        self::loadDirectAttributes($node, $params);
        return self::loadI18NStrings($node, $params)
            && self::loadGeolocationData($node, $params)
            && $node->save();
    }

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
