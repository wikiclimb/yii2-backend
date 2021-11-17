<?php

namespace apiv1\controllers;

use api\controllers\ActiveBaseController;
use apiv1\models\Node;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class NodeController
 * @package apiv1\controllers
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class NodeController extends ActiveBaseController
{
    public $modelClass = Node::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = ['index', 'view',];
        return $behaviors;
    }

    /**
     * @param string $action
     * @param null $model
     * @param array $params
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = []): bool
    {
        // Fully open access to index and view
        // Check access for others, for example updateNode
        if (($action === 'index' || $action === 'view')
            || Yii::$app->user->can($action . "Node", ['model_id' => $model?->id])) {
            return true;
        }
        throw new ForbiddenHttpException(
            Yii::t('yii', 'You are not allowed to perform this action.')
        );
    }
}
