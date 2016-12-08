<?php

namespace app\modules\text_info\models;

use Yii;
use app\components\behaviors\LogBehavior;

/**
 * This is the model class for table "text_info".
 *
 * @property integer $text_info_id
 * @property string $text_info_key
 * @property string $title
 * @property string $value
 */
class TextInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'text_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text_info_key', 'title'], 'required'],
            [['title', 'value'], 'string'],
            [['text_info_key'], 'string', 'max' => 255],

            [['value'], 'default', 'value' => null]
        ];
    }

    public function behaviors()
    {
        return [
            'log' => [
                'class' => LogBehavior::className(),
            ]
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        Yii::$app->cache->delete('text_'.$this->primaryKey);
        Yii::$app->cache->delete('text_'.$this->text_info_key);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            Yii::$app->cache->delete('text_'.$this->primaryKey);
            Yii::$app->cache->delete('text_'.$this->text_info_key);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'text_info_id' => 'Text Info ID',
            'text_info_key' => 'Метка',
            'title' => 'Заголовок',
            'value' => 'Значение',
        ];
    }
}
