<?php

namespace apiv1\controllers;

use api\controllers\BaseController;
use common\models\SignupForm;
use Yii;

/**
 * Class SignUpController
 * @package apiv1\controllers
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class SignUpController extends BaseController
{
    protected array $_verbs = ['POST', 'OPTIONS'];

    public function actions(): array
    {
        $actions = parent::actions();
        unset(
            $actions['index'],
            $actions['view'],
            $actions['update'],
            $actions['delete']
        );
        return $actions;
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['options', 'create'];
        return $behaviors;
    }

    /**
     * Process the request and try to create a new inactive user.
     * The method will automatically send a verification email if successful.
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate(): array
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->getBodyParams(), '') && $model->signup()) {
            return [
                'error' => false,
                'message' => Yii::t(
                    'app',
                    'Thank you for registration. Please check your inbox for verification email.'),
            ];
        }
        return [
            'error' => true,
            'message' => Yii::t('app', 'Failed validation'),
            'errors' => $model->errors,
        ];
    }
}
