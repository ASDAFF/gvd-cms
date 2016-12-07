<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `video_category`.
 */
class m161125_101913_create_video_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('video_category', [
            'video_category_id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_TEXT . ' NOT NULL',
            'slug' => Schema::TYPE_TEXT . ' NOT NULL',
            'parent_id' => Schema::TYPE_INTEGER,
            'cover' => Schema::TYPE_TEXT,
            'description' => Schema::TYPE_TEXT,
            'text' => Schema::TYPE_TEXT,
            'date' => Schema::TYPE_DATETIME . ' NOT NULL',
            'icon' => Schema::TYPE_TEXT
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('video_category');
    }
}
