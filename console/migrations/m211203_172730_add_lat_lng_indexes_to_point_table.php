<?php

use yii\db\Migration;

/**
 * Class m211203_172730_add_lat_lng_indexes_to_point_table
 */
class m211203_172730_add_lat_lng_indexes_to_point_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx_point_lat', 'point', 'lat');
        $this->createIndex('idx_point_lng', 'point', 'lng');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_point_lng', 'point');
        $this->dropIndex('idx_point_lat', 'point');
    }
}
