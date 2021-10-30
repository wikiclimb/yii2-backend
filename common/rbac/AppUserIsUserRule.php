<?php

namespace common\rbac;

use Yii;
use yii\rbac\Rule;
use common\models\User;

/**
 * Rule checks if the user requested corresponds to user making the request.
 */
class AppUserIsUserRule extends Rule
{
    public $name = 'appUserIsRequestedUser';
    
    /**
     * {@inheritDoc}
     * @see \yii\rbac\Rule::execute()
     */
    public function execute($user, $item, $params): bool
    {
        if(!isset($params['user_id'])) {
            Yii::warning('User_id parameter needs to be set', __METHOD__);
            return false;
        }
        if (($userModel = User::findOne($user)) === null) {
            Yii::warning(
                "User id'$user' doesn't correspond to a valid User" ,
                __METHOD__);
            return false;
        }
        if ($userModel->id !== (int)$params['user_id']) {
            Yii::info('Users are not the same user, denying access.',
                __METHOD__);
            return false;
        }
        return true;
    }
}
