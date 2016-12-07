<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `photo`.
 */
class m161201_112558_create_photo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('photo', [
            'photo_id' => Schema::TYPE_PK,
            'category_id' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_TEXT,
            'photo' => Schema::TYPE_TEXT,
            'time' => Schema::TYPE_DATETIME . ' NOT NULL',
            'views' => Schema::TYPE_INTEGER . ' NOT NULL',
            'text' => Schema::TYPE_TEXT
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('photo');
    }
}
