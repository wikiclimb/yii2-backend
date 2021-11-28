<?php

namespace apiv1\controllers;

use api\controllers\ActiveBaseController;
use apiv1\helpers\NodeHelper;
use apiv1\models\Node;
use common\helpers\I18nStringHelper;
use common\helpers\I18nTextHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class NodeController
 * @package apiv1\controllers
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class NodeController extends ActiveBaseController
{
    public $modelClass = Node::class;

    public function actions(): array
    {
        $actions = parent::actions();
        unset(
            $actions['create'],
            $actions['update'],
        );
        return $actions;
    }

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

    /**
     * @return Node
     * @throws ForbiddenHttpException
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function actionCreate(): Node
    {
        if (!Yii::$app->user->can('createNode')) {
            throw new ForbiddenHttpException(
                Yii::t('yii', 'You are not allowed to perform this action.')
            );
        }
        $params = Yii::$app->getRequest()->getBodyParams();
        // Default scenario.
        $model = new $this->modelClass;
        if (($model->name_id = I18nStringHelper::parseToModel(
                $params['name'] ?? '')?->id) === null) {
            throw new UnprocessableEntityHttpException(
                'Name value is not valid.'
            );
        }
        if (($model->description_id = I18nTextHelper::parseToModel(
                $params['description'] ?? '')?->id) === null) {
            throw new UnprocessableEntityHttpException(
                'Description value is not valid.'
            );
        }
        // Mass load some parameters from the request.
        $model->load($params, '');
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $response->getHeaders()->set('Location', Url::toRoute(['node/view', 'id' => $model->id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * @param int $id
     * @return Node
     * @throws ForbiddenHttpException
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \Throwable
     * @throws Exception
     */
    public function actionUpdate(int $id): Node
    {
        if (!Yii::$app->user->can('updateNode')) {
            throw new ForbiddenHttpException(
                Yii::t('yii', 'You are not allowed to perform this action.')
            );
        }
        /** @var Node $model */
        if (($model = $this->modelClass::findOne($id)) === null) {
            throw new NotFoundHttpException(
                Yii::t('app', 'Resource not found.')
            );
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (NodeHelper::load($model, Yii::$app->getRequest()->getBodyParams())) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(200);
                $response->getHeaders()->set('Location',
                    Url::toRoute(['node/view', 'id' => $model->id], true));
            } elseif (!$model->hasErrors()) {
                $transaction->rollBack();
                throw new ServerErrorHttpException(
                    'Failed to update the object for unknown reason.');
            }
            $transaction->commit();
            return $model;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
