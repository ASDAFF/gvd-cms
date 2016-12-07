<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `news_image`.
 */
class m161118_085501_create_news_image_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('news_image', [
            'news_image_id' => $this->primaryKey(),
            'news_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'image' => Schema::TYPE_STRING . ' NOT NULL',
            'label' => 'ENUM("cover", "main", "extra", "slider") NOT NULL'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('news_image');
    }
}
