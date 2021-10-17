<?php

namespace apiv1\controllers;

use api\controllers\ActiveBaseController;
use api\models\User;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class UserController
 *
 * @package api\controllers
 * @author Raul Sauco
 */
class UserController extends ActiveBaseController
{
    public $modelClass = User::class;
    protected array $_verbs = ['GET'];

    /**
     * @param string $action
     * @param null $model
     * @param array $params
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = []): bool
    {
        return true;    // TODO this line is for testing without RBAC remove later
        if ($action === 'delete' || $action === 'create' || $action === 'update') {
            throw new ForbiddenHttpException(
                Yii::t('yii', 'You are not allowed to perform this action.')
            );
        }
        if ($action === 'index' && !Yii::$app->user->can('listUsers')) {
            throw new ForbiddenHttpException(
                Yii::t('yii', 'You are not allowed to perform this action.')
            );
        }
        if ($action === 'view' && !Yii::$app->user->can('viewUser')) {
            throw new ForbiddenHttpException(
                Yii::t('yii', 'You are not allowed to perform this action.')
            );
        }
        return true;
    }
}
