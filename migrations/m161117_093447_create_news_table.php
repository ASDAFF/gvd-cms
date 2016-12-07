<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `news`.
 */
class m161117_093447_create_news_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('news', [
            'news_id' => $this->primaryKey(),
            'published' => Schema::TYPE_BOOLEAN,
            'title' => Schema::TYPE_TEXT.' NOT NULL',
            'description' => Schema::TYPE_TEXT.' NOT NULL',
            'slug' => Schema::TYPE_TEXT.' NOT NULL',
            'epigraph' => Schema::TYPE_TEXT.' NOT NULL',
            'views' => Schema::TYPE_INTEGER.' NOT NULL',
            'date' => Schema::TYPE_DATETIME.' NOT NULL',
            'slider' => Schema::TYPE_BOOLEAN,
            'text' => Schema::TYPE_TEXT.' NOT NULL',
            'popular' => Schema::TYPE_BOOLEAN
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('news');
    }
}
