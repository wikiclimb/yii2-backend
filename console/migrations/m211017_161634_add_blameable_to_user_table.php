<?php

use yii\db\Migration;

/**
 * Class m211017_161634_add_blameable_to_user_table
 */
class m211017_161634_add_blameable_to_user_table extends Migration
{
    private string $tableName = 'user';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'created_by', $this->integer()->after('status'));
        $this->addColumn($this->tableName, 'updated_by', $this->integer()->after('created_by'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'updated_by');
        $this->dropColumn($this->tableName, 'created_by');
    }
}
