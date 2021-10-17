<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\rest\Controller;

/**
 * Class BaseController
 * @package api\controllers
 */
abstract class BaseController extends Controller
{
    // Allow all methods by default
    protected array $_verbs = ['GET','POST','PATCH','PUT','OPTIONS','DELETE'];

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => $this->_verbs,
                'Access-Control-Allow-Headers' => ['content-type','authorization'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Expose-Headers' => ['Link']
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        $behaviors['authenticator']['except'] = ['options'];
        return $behaviors;
    }

    /**
     * Send the HTTP options available to this route
     */
    public function actionOptions()
    {
        if (Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
            Yii::$app->getResponse()->setStatusCode(405);
        }
        Yii::$app->getResponse()->getHeaders()->set('Allow', implode(', ', $this->_verbs));
    }
}
