<?php

namespace console\db;

class Migration extends \yii\db\Migration
{
    /**
     * Add both blameable and timestamp behavior attributes to a table.
     * @param string $tableName
     * @param string $delete
     * @param string $update
     */
    protected function addTimeAndBlame(string $tableName, string $delete = 'RESTRICT', string $update = 'CASCADE')
    {
        $this->addTimestamp($tableName);
        $this->addBlameable($tableName, $delete, $update);
    }

    /**
     * Drop both blameable and timestamp behavior attributes from the given table.
     * @param string $tableName
     */
    protected function dropTimeAndBlame(string $tableName)
    {
        $this->dropTimestamp($tableName);
        $this->dropBlameable($tableName);
    }

    /**
     * Add 'created_by' and 'updated_by' fields to a table and a foreign key
     * that makes them reference user.id.
     * @param string $tableName the name of the table.
     * @param string $delete the foreign key policy on delete, by default 'RESTRICT'.
     * @param string $update the foreign key policy on update, by default 'CASCADE'.
     */
    protected function addBlameable(string $tableName, string $delete = 'RESTRICT', string $update = 'CASCADE')
    {
        $this->addColumn($tableName, 'created_by', $this->integer()->unsigned());
        $this->addForeignKey(
            'fk_' . $tableName . '_created_by',
            $tableName,
            'created_by',
            'user',
            'id',
            $delete,
            $update,
        );
        $this->addColumn($tableName, 'updated_by', $this->integer()->unsigned());
        $this->addForeignKey(
            'fk_' . $tableName . '_updated_by',
            $tableName,
            'updated_by',
            'user',
            'id',
            $delete,
            $update,
        );
    }

    /**
     * Drop the blameable behavior attributes 'created_by' and 'updated_by' and
     * their related foreign keys for the given table.
     * @param string $tableName the name of the table.
     */
    protected function dropBlameable(string $tableName)
    {
        $this->dropForeignKey('fk_' . $tableName . '_updated_by', $tableName);
        $this->dropColumn($tableName, 'updated_by');
        $this->dropForeignKey('fk_' . $tableName . '_updated_by', $tableName);
        $this->dropColumn($tableName, 'updated_by');
    }

    /**
     * Add timestamp behaviour related attributes 'created_at' and 'updated_at'
     * to the given table.
     * @param string $tableName the table to add the attributes to.
     */
    protected function addTimestamp(string $tableName)
    {
        $this->addColumn($tableName, 'created_at', $this->integer()->unsigned());
        $this->addColumn($tableName, 'updated_at', $this->integer()->unsigned());
    }

    /**
     * Remove timestamp behaviour related attributes 'created_at' and 'updated_at'
     * from the given table.
     * @param string $tableName the table to remove the attributes from.
     */
    protected function dropTimestamp(string $tableName)
    {
        $this->dropColumn($tableName, 'updated_at');
        $this->dropColumn($tableName, 'created_at');
    }
}