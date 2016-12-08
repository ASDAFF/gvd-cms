<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `page`.
 */
class m161206_085136_create_page_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('page', [
            'page_id' => Schema::TYPE_PK,
            'root_page_id' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'slug' => Schema::TYPE_TEXT,
            'text' => Schema::TYPE_TEXT,
            'cover' => Schema::TYPE_TEXT,
            'photo' => Schema::TYPE_TEXT,
            'data' => Schema::TYPE_TEXT
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('page');
    }
}
