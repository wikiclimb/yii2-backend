<?php

namespace apiv1\controllers;

use api\controllers\BaseController;
use common\models\User;
use Yii;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class LoginController
 * @package api\controllers
 */
class LoginController extends BaseController
{
    protected array $_verbs = ['POST','OPTIONS'];

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['options','create'];
        return $behaviors;
    }

    /**
     * @return array
     * @throws UnprocessableEntityHttpException
     */
    public function actionCreate(): array
    {
        $p = $this->request->post('password');
        $u = $this->request->post('username');
        Yii::debug($this->request->post('username'), __METHOD__);
        if (empty($u) || empty($p)) {
            throw new UnprocessableEntityHttpException(
                Yii::t('app', 'Username and Password are required')
            );
        }
        if (($user = User::findByUserName($u)) !== null && $user->validatePassword($p)) {
            return [
                'error' => false,
                'message' => 'OK',
                'credentials' => [
                    'username' => $user->username,
                    'accessToken' => $user->access_token
                ],
            ];
        }
        // Unsuccessful login. Do not offer details
        return [
            'error' => true,
            'message' => Yii::t('app', 'Authentication Failure'),
        ];
    }
}
