<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `slider`.
 */
class m161207_081506_create_slider_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('slider', [
            'slider_id' => Schema::TYPE_PK,
            'slider_key' => Schema::TYPE_STRING . ' NOT NULL',
            'title' => Schema::TYPE_STRING . ' NOT NULL'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('slider');
    }
}
