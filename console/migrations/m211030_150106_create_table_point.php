<?php

use console\db\Migration;

/**
 * Class m211030_150106_create_table_point
 */
class m211030_150106_create_table_point extends Migration
{
    private string $tableName = 'point';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        $this->createTable($this->tableName, [
            'lat' => $this->decimal(10, 8)->notNull(),
            'lng' => $this->decimal(11, 8)->notNull(),
            'elevation' => $this->decimal(7, 2),
            'timestamp' => $this->integer(11)->unsigned(),
        ], $tableOptions);
        $this->addTimeAndBlame($this->tableName);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
