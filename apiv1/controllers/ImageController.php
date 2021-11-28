<?php

namespace apiv1\controllers;

use api\controllers\ActiveBaseController;
use apiv1\models\Image;
use apiv1\models\Node;
use common\helpers\ImageHelper;
use common\models\NodeImage;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

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

    /**
     * @return array
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionCreate(): array
    {
        if (!Yii::$app->user->can('user')) {
            throw new ForbiddenHttpException(
                Yii::t('yii', 'You are not allowed to perform this action.')
            );
        }
        $uploadedFiles = UploadedFile::getInstancesByName('files');
        $images = [];
        $nodeId = Yii::$app->request->post('node-id');
        if ($nodeId !== null) {
            if (($node = Node::findOne($nodeId)) === null) {
                // If the user sent a node id it will expect the images to be linked to that node.
                throw new NotFoundHttpException(
                    Yii::t('app', 'Resource not found.')
                );
            }
        }
        foreach ($uploadedFiles as $uploadedFile) {
            if (empty($uploadedFile)) {
                Yii::error('No image uploaded', __METHOD__);
                throw new BadRequestHttpException(
                    Yii::t('app', 'No image file found')
                );
            }
            if (($image = ImageHelper::createNewImage($uploadedFile,
                    Yii::getAlias('@imgPath'), $this->modelClass)) === null) {
                $message = 'Error creating new Image instance';
                Yii::error($message, __METHOD__);
                throw new ServerErrorHttpException($message);
            }
            if ($image instanceof $this->modelClass) {
                Yii::debug("Created Image: $image->id", __METHOD__);
                // Consider any images created by users, not guests, validated.
                $image->validated = 1;
                $image->save();
                $images[] = $image;
                if (isset($node)) {
                    $nodeImage = new NodeImage();
                    $nodeImage->node_id = $node->id;
                    $nodeImage->image_id = $image->id;
                    if (!$nodeImage->save()) {
                        Yii::error(
                            "Error linking image $image->id with node $nodeId",
                            __METHOD__);
                    } else {
                        Yii::debug("Created NodeImage($nodeId,$image->id)", __METHOD__);
                    }
                }
            }
        }
        $response = Yii::$app->getResponse();
        $response->setStatusCode(201);
        if (isset($node)) {
            $response->getHeaders()->set('Location',
                Url::toRoute(['images/index', 'node-id' => $node->id], true));
        } else {
            $response->getHeaders()->set('Location', Url::toRoute(['images/index'], true));
        }
        return $images;
    }
}
