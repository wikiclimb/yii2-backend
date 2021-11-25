<?php

namespace apiv1\controllers;

use api\controllers\ActiveBaseController;
use apiv1\models\Image;
use apiv1\models\Node;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class ImageController
 * @package apiv1\controllers
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class ImageController extends ActiveBaseController
{
    public $modelClass = Image::class;

    public function actions(): array
    {
        $actions = parent::actions();
        unset(
            $actions['create'],
            $actions['update'],
            $actions['delete'],
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
        if (($action === 'index' || $action === 'view')
            || Yii::$app->user->can($action . "Image",
                ['model_id' => $model?->id])) {
            return true;
        }
        throw new ForbiddenHttpException(
            Yii::t('yii',
                'You are not allowed to perform this action.')
        );
    }

    /**
     * @return ActiveDataProvider
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function prepareDataProvider(): ActiveDataProvider
    {
        $query = $this->modelClass::find();
        if (($nodeId = Yii::$app->request->get('node-id')) !== null) {
            if (($node = Node::findOne($nodeId)) === null) {
                throw new NotFoundHttpException(
                    Yii::t('app', 'Resource not found.')
                );
            }
            $query = $node->getImages()->where(['validated' => true]);
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
