<?php

use yii\db\Migration;

/**
 * Class m210206_082351_add_table_logs
 */
class m210206_082351_add_table_logs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('logs', [
            'id'        => 'pk',
            'ip'  => 'varchar(15) NOT NULL',
            'date' => 'date  NOT NULL',
            'url'     => 'text',
            'os'     => 'varchar(15)',
            'architecture' => 'varchar(5)',
            'browser' => 'varchar(30)',
        ]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('logs');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210206_082351_add_table_logs cannot be reverted.\n";

        return false;
    }
    */
}
