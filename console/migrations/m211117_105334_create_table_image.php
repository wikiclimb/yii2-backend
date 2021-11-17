<?php

use console\db\Migration;

/**
 * Class m211117_105334_create_table_image
 */
class m211117_105334_create_table_image extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        $this->createTable('node_image', [
            'node_id' => $this->integer()->unsigned(),
            'image_id' => $this->integer()->unsigned(),
            'is_cover' => $this->boolean()->defaultValue(false),
        ], $tableOptions);
        $this->addTimeAndBlame('node_image');
        $this->addForeignKey(
            'fk_node_image_node_id',
            'node_image',
            'node_id',
            'node',
            'id',
            'CASCADE',
        );
        $this->createTable('image', [
            'id' => $this->primaryKey()->unsigned(),
            'name_id' => $this->integer()->unsigned(),
            'description_id' => $this->integer()->unsigned(),
            'file_name' => $this->string()->notNull(),
            'validated' => $this->boolean()->defaultValue(false),
        ], $tableOptions);
        $this->addTimeAndBlame('image');
        $this->addForeignKey(
            'fk_node_image_image_id',
            'node_image',
            'image_id',
            'image',
            'id',
            'CASCADE',
        );
        $this->addForeignKey(
            'fk_image_name_id',
            'image',
            'name_id',
            'i18n_string',
            'id',
        );
        $this->addForeignKey(
            'fk_image_description_id',
            'image',
            'description_id',
            'i18n_text',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('image');
        $this->dropTable('node_image');
    }
}
