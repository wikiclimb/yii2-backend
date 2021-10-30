<?php

use console\db\Migration;

/**
 * Class m211030_123844_create_i18n_tables
 */
class m211030_123844_create_i18n_tables extends Migration
{
    private string $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
    private array $tableNames = ['i18n_string', 'i18n_text'];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach ($this->tableNames as $tableName) {
            $type = $tableName === 'i18n_string' ? $this->string() : $this->text();
            $this->createTable(
                $tableName, [
                'id' => $this->primaryKey()->unsigned(),
                'default' => $type->notNull(),
            ], $this->tableOptions);
            foreach (Yii::$app->params['i18n_languages'] as $language) {
                $this->addColumn($tableName, $language,
                    ($tableName === 'i18n_string' ? $this->string() : $this->text())->defaultValue(null));
            }
            $this->addTimeAndBlame($tableName);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        foreach ($this->tableNames as $tableName) {
            $this->dropTable($tableName);
        }
    }
}
