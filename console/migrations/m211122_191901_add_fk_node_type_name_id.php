<?php

use yii\db\Migration;

/**
 * Class m211122_191901_add_fk_node_type_name_id
 */
class m211122_191901_add_fk_node_type_name_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk_node_type_name_id',
            'node_type',
            'name_id',
            'i18n_string',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_node_name_id', 'node_type');
    }
}
