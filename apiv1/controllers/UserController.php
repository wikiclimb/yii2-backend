<?php

namespace apiv1\controllers;

use api\controllers\ActiveBaseController;
use api\models\User;
use Yii;
use yii\base\Exception;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class UserController
 * @package apiv1\controllers
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class UserController extends ActiveBaseController
{
    public $modelClass = User::class;

    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update'],);
        return $actions;
    }

    /**
     * @param string $action
     * @param null $model
     * @param array $params
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = []): bool
    {
        if ($action === 'index'
            && !Yii::$app->user->can('listUsers')) {
            throw new ForbiddenHttpException(
                Yii::t('yii', 'You are not allowed to perform this action.')
            );
        }
        if ($action === 'create'
            && !Yii::$app->user->can('createUser')) {
            throw new ForbiddenHttpException(
                Yii::t('yii', 'You are not allowed to perform this action.')
            );
        }
        if ($action === 'update'
            && !Yii::$app->user->can('updateUser', ['user_id' => $model->id])) {
            throw new ForbiddenHttpException(
                Yii::t('yii', 'You are not allowed to perform this action.')
            );
        }
        if ($action === 'view'
            && !Yii::$app->user->can('viewUser', ['user_id' => $model->id])) {
            throw new ForbiddenHttpException(
                Yii::t('yii', 'You are not allowed to perform this action.')
            );
        }
        if ($action === 'delete'
            && !Yii::$app->user->can('deleteUser', ['user_id' => $model->id])) {
            throw new ForbiddenHttpException(
                Yii::t('yii', 'You are not allowed to perform this action.')
            );
        }
        return true;
    }

    /**
     * Custom create action.
     * @return User
     * @throws ServerErrorHttpException
     * @throws Exception
     */
    public function actionCreate(): User
    {
        if (!Yii::$app->user->can('createUser')) {
            throw new ForbiddenHttpException(
                Yii::t('yii', 'You are not allowed to perform this action.')
            );
        }
        $model = new User();
        $model->username = $this->request->post('username');
        $model->email = $this->request->post('email');
        $model->setPassword($this->request->post('password'));
        $model->generateAuthKey();
        $model->generateEmailVerificationToken();
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location',
                Url::toRoute(['view', 'id' => $id], true)
            );
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException(
                'Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * Custom update action.
     * @param $id
     * @return User
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionUpdate($id): User
    {
        if (!Yii::$app->user->can('updateUser', ['user_id' => $id])) {
            throw new ForbiddenHttpException(
                Yii::t('yii', 'You are not allowed to perform this action.')
            );
        }
        if (($model = User::findOne($id)) === null) {
            throw new NotFoundHttpException(
                Yii::t('yii', 'No results found.')
            );
        }
        if ($this->request->post('email') !== null) {
            $model->email = $this->request->post('email');
        }
        if ($this->request->post('password') !== null) {
            $model->setPassword($this->request->post('password'));
        }
        if ($model->save()) {
            $this->response->setStatusCode(200);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $this->response->getHeaders()->set('Location',
                Url::toRoute(['view', 'id' => $id], true)
            );
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException(
                'Failed to update the object for unknown reason.');
        }
        return $model;
    }
}
