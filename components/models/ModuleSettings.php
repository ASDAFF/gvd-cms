<?php

namespace app\components\models;

use Yii;

/**
 * This is the model class for table "module_settings".
 *
 * @property integer $module_settings_id
 * @property string $module_class
 * @property string $name
 * @property integer $value
 */
class ModuleSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'module_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_class', 'name', 'value'], 'required'],
            [['module_class'], 'string'],
            [['value'], 'integer'],
            [['key'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'module_settings_id' => 'Module Settings ID',
            'module_class' => 'Module Class',
            'key' => 'Key',
            'name' => 'Name',
            'value' => 'Value',
        ];
    }
}
