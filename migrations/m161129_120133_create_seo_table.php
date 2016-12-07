<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `seo`.
 */
class m161129_120133_create_seo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('seo', [
            'seo_id' => Schema::TYPE_PK,
            'item_class' => Schema::TYPE_TEXT . ' NOT NULL',
            'item_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title' => Schema::TYPE_STRING,
            'keywords' => Schema::TYPE_STRING,
            'description' => Schema::TYPE_STRING,
            'robots' => 'ENUM("noindex, nofollow", "index, nofollow", "noindex, follow", "index, follow") NOT NULL'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('seo');
    }
}
