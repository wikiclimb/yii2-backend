<?php

use common\models\I18nString;
use common\models\Node;
use common\models\NodeType;
use console\db\Migration;

/**
 * Class m211030_172230_create_table_node
 */
class m211030_172230_create_table_node extends Migration
{
    private string $tableName = 'node';

    /**
     * {@inheritdoc}
     * @throws \yii\console\Exception
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        $this->createTable('node_type', [
            'id' => $this->primaryKey()->unsigned(),
            'name_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        $this->addTimeAndBlame('node_type');
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'node_type_id' => $this->integer()->unsigned(),
            'parent_id' => $this->integer()->unsigned(),
            'name_id' => $this->integer()->unsigned()->notNull(),
            'description_id' => $this->integer()->unsigned(),
            'point_id' => $this->integer()->unsigned(),
        ], $tableOptions);
        $this->addTimeAndBlame($this->tableName);
        $this->addForeignKey(
            'fk_node_node_type_id',
            $this->tableName,
            'node_type_id',
            'node_type',
            'id',
        );
        $this->addForeignKey(
            'fk_node_parent_id',
            $this->tableName,
            'parent_id',
            'node',
            'id',
        );
        $this->addForeignKey(
            'fk_node_name_id',
            $this->tableName,
            'name_id',
            'i18n_string',
            'id',
        );
        $this->addForeignKey(
            'fk_node_description_id',
            $this->tableName,
            'description_id',
            'i18n_text',
            'id',
        );
        $this->addForeignKey(
            'fk_node_point_id',
            $this->tableName,
            'point_id',
            'point',
            'id',
        );
        if (YII_ENV !== 'test') {
            $this->insertNodeTypes();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
        $this->dropTable('node_type');
    }

    /**
     * @throws \yii\console\Exception
     */
    private function insertNodeTypes()
    {
        $types = [
            Node::TYPE_AREA => 'Area',
            Node::TYPE_LOCATION => 'Location',
            Node::TYPE_ROUTE => 'Route',
            Node::TYPE_PITCH => 'Pitch',
            ];
        foreach ($types as $key => $type) {
            $string = new I18nString();
            $string->default = $type;
            $string->en = $type;
            if (!$string->save()) {
                throw new \yii\console\Exception(
                    "Failed to save I18nString $type");
            }
            $nodeType = new NodeType();
            $nodeType->id = $key;
            $nodeType->name_id = $string->id;
            if (!$nodeType->save()) {
                throw new \yii\console\Exception(
                    "Failed to save nodetype $type");
            }
        }
    }
}
