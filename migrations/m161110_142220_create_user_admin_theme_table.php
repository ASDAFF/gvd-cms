<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `user_admin_theme`.
 */
class m161110_142220_create_user_admin_theme_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_admin_theme', [
            'user_theme_id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'theme' => 'VARCHAR(64) NOT NULL',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_admin_theme');
    }
}
