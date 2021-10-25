<?php

use console\db\Migration;

/**
 * Class m211024_072707_create_rbac_tables
 */
class m211024_072707_create_rbac_tables extends Migration
{
    private string $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addRulesTable();
        $this->addItemsTable();
        $this->addItemChildrenTable();
        $this->addAuthAssignmentTable();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropAuthAssignmentTable();
        $this->dropItemChildrenTable();
        $this->dropItemsTable();
        $this->dropRulesTable();
    }

    private function addRulesTable()
    {
        $tableName = 'auth_rule';
        $this->createTable($tableName, [
            'name' => $this->string(64)->notNull(),
            'data' => $this->binary(),
            'PRIMARY KEY ([[name]])',
        ], $this->tableOptions);
        $this->addTimeAndBlame($tableName);
    }

    private function dropRulesTable()
    {
        $tableName = 'auth_rule';
        $this->dropTimeAndBlame($tableName);
        $this->dropTable($tableName);
    }

    private function addItemsTable()
    {
        $tableName = 'auth_item';
        $this->createTable($tableName, [
            'name' => $this->string(64)->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->binary(),
            'PRIMARY KEY ([[name]])',
        ], $this->tableOptions);
        $this->addForeignKey(
            'fk_auth_item_auth_rule',
            $tableName,
            'rule_name',
            'auth_rule',
            'name',
            'SET NULL',
            'CASCADE',
        );
        $this->createIndex('idx-auth_item-type', 'auth_item', 'type');
        $this->addTimeAndBlame($tableName);
    }

    private function dropItemsTable()
    {
        $tableName = 'auth_item';
        $this->dropTimeAndBlame($tableName);
        $this->dropTable($tableName);
    }

    private function addItemChildrenTable()
    {
        $tableName= 'auth_item_child';
        $this->createTable($tableName, [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY ([[parent]], [[child]])',
        ], $this->tableOptions);
        $this->addForeignKey(
            'fk_auth_item_child_parent',
            $tableName,
            'parent',
            'auth_item',
            'name',
            'CASCADE',
            'CASCADE',
        );
        $this->addForeignKey(
            'fk_auth_item_child_child',
            $tableName,
            'child',
            'auth_item',
            'name',
            'CASCADE',
            'CASCADE',
        );
        $this->addTimeAndBlame($tableName);
    }

    private function dropItemChildrenTable()
    {
        $tableName= 'auth_item_child';
        $this->dropTimeAndBlame($tableName);
        $this->dropTable($tableName);
    }

    private function addAuthAssignmentTable()
    {
        $tableName= 'auth_assignment';
        $this->createTable($tableName, [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->integer()->unsigned(),
            'PRIMARY KEY ([[item_name]], [[user_id]])',
        ], $this->tableOptions);
        $this->addForeignKey(
            'fk_auth_assignment_auth_item',
            $tableName,
            'item_name',
            'auth_item',
            'name',
            'CASCADE',
            'CASCADE',
        );
        $this->addForeignKey(
            'fk_auth_assignment_user_id',
            $tableName,
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE',
        );
        $this->addTimeAndBlame($tableName);
    }

    private function dropAuthAssignmentTable()
    {
        $tableName= 'auth_assignment';
        $this->dropTimeAndBlame($tableName);
        $this->dropTable($tableName);
    }
}
