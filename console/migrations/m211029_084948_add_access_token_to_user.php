<?php

use yii\db\Migration;

/**
 * Class m211029_084948_add_access_token_to_user
 */
class m211029_084948_add_access_token_to_user extends Migration
{
    private string $tableName = 'user';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            $this->tableName,
            'access_token',
            $this->string(32)->after('auth_key')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'access_token');
    }
}
