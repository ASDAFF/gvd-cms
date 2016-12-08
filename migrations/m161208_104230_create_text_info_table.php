<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `text_info`.
 */
class m161208_104230_create_text_info_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('text_info', [
            'text_info_id' => Schema::TYPE_PK,
            'text_info_key' => Schema::TYPE_STRING . ' NOT NULL',
            'title' => Schema::TYPE_TEXT . ' NOT NULL',
            'value' => Schema::TYPE_TEXT
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('text_info');
    }
}
