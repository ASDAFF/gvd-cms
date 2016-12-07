<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `module_settings`.
 */
class m161127_165613_create_module_settings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('module_settings', [
            'module_settings_id' => Schema::TYPE_PK,
            'module_class' => Schema::TYPE_TEXT . ' NOT NULL',
            'key' => 'VARCHAR(100) NOT NULL',
            'name' => 'VARCHAR(200) NOT NULL',
            'value' => Schema::TYPE_BOOLEAN . ' NOT NULL'
        ]);

        $this->insert('module_settings', [
            'module_class' => 'app\modules\news\News',
            'key' => 'status',
            'name' => 'Статус',
            'value' => true
        ]);

        $this->insert('module_settings', [
            'module_class' => 'app\modules\news\News',
            'key' => 'indexImages',
            'name' => 'Картинки для главной',
            'value' => false
        ]);

        $this->insert('module_settings', [
            'module_class' => 'app\modules\video\Video',
            'key' => 'status',
            'name' => 'Статус',
            'value' => true
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('module_settings');
    }
}
