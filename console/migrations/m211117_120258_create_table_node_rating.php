<?php

use console\db\Migration;

/**
 * Class m211117_120258_create_table_node_rating
 */
class m211117_120258_create_table_node_rating extends Migration
{
    private string $tableName = 'node_rating';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        $this->createTable($this->tableName, [
            'user_id' => $this->integer()->unsigned(),
            'node_id' => $this->integer()->unsigned(),
            'rating' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);
        $this->addPrimaryKey('pk_node_rating',
            $this->tableName,
            ['user_id', 'node_id'],
        );
        $this->addTimeAndBlame($this->tableName);
        $this->addForeignKey(
            'fk_node_rating_user_id',
            $this->tableName,
            'user_id',
            'user',
            'id',
            'CASCADE',
        );
        $this->addForeignKey(
            'fk_node_rating_node_id',
            $this->tableName,
            'node_id',
            'node',
            'id',
            'CASCADE',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
