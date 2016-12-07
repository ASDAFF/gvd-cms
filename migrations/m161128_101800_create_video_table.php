<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `video`.
 */
class m161128_101800_create_video_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('video', [
            'video_id' => Schema::TYPE_PK,
            'category_id' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_TEXT,
            'cover' => Schema::TYPE_TEXT,
            'time' => Schema::TYPE_DATETIME . ' NOT NULL',
            'url' => Schema::TYPE_TEXT . ' NOT NULL',
            'text' => Schema::TYPE_TEXT
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('video');
    }
}
