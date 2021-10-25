<?php

use console\db\Migration;

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
        $this->addBlameable('user');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropBlameable('user');
    }
}
