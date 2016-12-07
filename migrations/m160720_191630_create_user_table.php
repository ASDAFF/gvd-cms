<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation for table `user_table`.
 */
class m160720_191630_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => Schema::TYPE_PK,
            'email' => Schema::TYPE_TEXT . ' NOT NULL',
            'password_hash' => Schema::TYPE_TEXT . ' NOT NULL',
            'name' => 'VARCHAR(100) NOT NULL',
            'last_name' => 'VARCHAR(100) NOT NULL',
            'avatar' => Schema::TYPE_TEXT . ' NOT NULL',
            'phone' => 'VARCHAR(13) NOT NULL',
            'soc_id' => Schema::TYPE_TEXT . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_TEXT . ' NOT NULL',
            'auth_key' => Schema::TYPE_TEXT . ' NOT NULL',
            'status' => Schema::TYPE_INTEGER . ' NOT NULL',
            'role' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
