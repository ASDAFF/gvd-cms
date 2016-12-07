<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `log`.
 */
class m161124_103918_create_log_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('log', [
            'log_id' => Schema::TYPE_PK,
            'item_class' => Schema::TYPE_TEXT,
            'item_id' => Schema::TYPE_INTEGER,
            'item_name' => Schema::TYPE_TEXT,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'action' => 'ENUM("create", "update", "delete", "enter") NOT NULL',
            'time' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'user_ip' => Schema::TYPE_STRING . ' NOT NULL',
            'user_agent' => Schema::TYPE_TEXT . ' NOT NULL'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('log');
    }
}
