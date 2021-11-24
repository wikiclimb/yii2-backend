<?php

namespace apiv1\helpers;

use common\helpers\I18nStringHelper;
use common\helpers\I18nTextHelper;
use common\models\Node;
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
     * Load all values from parameters into node instance.
     * @param Node $node
     * @param array $params
     * @return bool
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public static function load(Node $node, array $params): bool
    {
        // load 'description' and 'name' parameters.
        if (!self::loadI18NStrings($node, $params)) {
            return false;
        }
        return $node->save();
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
        if (!self::loadName($node, $params)) {
            return false;
        }
        if (!self::loadDescription($node, $params)) {
            return false;
        }
        return true;
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
}
