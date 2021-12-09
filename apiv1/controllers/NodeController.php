<?php

namespace apiv1\controllers;

use api\controllers\ActiveBaseController;
use apiv1\helpers\NodeHelper;
use apiv1\models\Node;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
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
        $actions['index']['prepareDataProvider'] = [
            $this, 'prepareDataProvider',
        ];
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
     * Prepare a DataProvider that will manage access to node data.
     * @return ActiveDataProvider
     */
    public function prepareDataProvider(): ActiveDataProvider
    {
        if ((bool)Yii::$app->request->get('bounded') === true
            && Yii::$app->request->get('north') !== null
            && Yii::$app->request->get('east') !== null
            && Yii::$app->request->get('south') !== null
            && Yii::$app->request->get('west') !== null) {
            return NodeHelper::searchWithinBounds(Yii::$app->request->get());
        } else {
            /** @var ActiveQuery $query */
            $query = $this->modelClass::find();
            /** @var int $type */
            $type = Yii::$app->request->get('type');
            if ($type !== null) {
                $query->where(['node_type_id' => $type]);
            }
            $q = Yii::$app->request->get('q');
            if ($q !== null) {
                $query->joinWith(['name as name', 'description as description'])
                    ->andWhere(['or',
                        ['like', 'name.default', $q],
                        ['like', 'name.en', $q],
                        ['like', 'description.default', $q],
                        ['like', 'description.en', $q],
                    ]);
            }
            return new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSizeLimit' => [1, 1000],
                    'defaultPageSize' => 50
                ],
                'sort' => [
                    'defaultOrder' => ['id' => SORT_DESC]
                ]
            ]);
        }
    }

    /**
     * @return Node
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \Throwable
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
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (NodeHelper::load($model, Yii::$app->getRequest()->getBodyParams())) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(201);
                $response->getHeaders()->set('Location',
                    Url::toRoute(['node/view', 'id' => $model->id], true));
            } elseif (!$model->hasErrors()) {
                $transaction->rollBack();
                throw new ServerErrorHttpException(
                    'Failed to create the Node for unknown reason.');
            }
            $transaction->commit();
            return $model;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
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
