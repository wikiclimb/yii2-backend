<?php

namespace apiv1\controllers;

use api\controllers\BaseController;
use common\models\User;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class LoginController
 * @package api\controllers
 */
class LoginController extends BaseController
{
    protected array $_verbs = ['POST', 'OPTIONS'];

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['options', 'create'];
        return $behaviors;
    }

    /**
     * @return array
     * @throws UnprocessableEntityHttpException
     * @throws UnauthorizedHttpException
     */
    #[ArrayShape(['id' => "int", 'username' => "string", 'token' => "string"])] public function actionCreate(): array
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
                'id' => $user->id,
                'username' => $user->username,
                'token' => $user->access_token

            ];
        }
        // Unsuccessful login. Do not offer details
        throw new UnauthorizedHttpException(
            Yii::t('app', 'Authentication Failure')
        );
    }
}
