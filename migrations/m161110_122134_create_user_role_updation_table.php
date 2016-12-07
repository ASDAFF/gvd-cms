<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `user_role_updation`.
 */
class m161110_122134_create_user_role_updation_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_role_updation', [
            'user_role_upd_id' => Schema::TYPE_PK,
            'role_name' => 'VARCHAR(64) NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'action' => 'VARCHAR(64) NOT NULL',
            'time' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_role_updation');
    }
}
