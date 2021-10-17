<?php

namespace api\models;

/**
 * Class User
 * @package api\models
 */
class User extends \common\models\User
{
    public function fields() : array
    {
        $fields = parent::fields();
        unset(
            $fields['created_by'],
            $fields['updated_by'],
            $fields['created_at'],
            $fields['updated_at'],
            $fields['password'],
            $fields['password_hash'],
            $fields['password_reset_token'],
            $fields['verification_token'],
            $fields['auth_key'],
            $fields['access_token']
        );
        return $fields;
    }
}
