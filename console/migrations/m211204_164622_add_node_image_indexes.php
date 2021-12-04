<?php

use yii\db\Migration;

/**
 * Class m211204_164622_add_node_image_indexes
 */
class m211204_164622_add_node_image_indexes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx_image_file_name', 'image', 'file_name');
        $this->addPrimaryKey('pk_node_image', 'node_image', ['image_id', 'node_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('pk_node_image', 'node_image');
        $this->dropIndex('idx_image_file_name', 'image');
    }
}
