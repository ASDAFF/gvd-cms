<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `slider_item`.
 */
class m161207_085634_create_slider_item_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('slider_item', [
            'slider_item_id' => Schema::TYPE_PK,
            'slider_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title' => Schema::TYPE_TEXT,
            'description' => Schema::TYPE_TEXT,
            'link' => Schema::TYPE_TEXT,
            'photo' => Schema::TYPE_TEXT,
            'photo_hover' => Schema::TYPE_TEXT,
            'order_num' => Schema::TYPE_INTEGER . ' NOT NULL'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('slider_item');
    }
}
