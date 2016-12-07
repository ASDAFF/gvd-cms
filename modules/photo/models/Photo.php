<?php

namespace app\modules\photo\models;

use Yii;
use app\components\behaviors\LogBehavior;
use app\components\behaviors\ItemImageBehavior;

/**
 * This is the model class for table "photo".
 *
 * @property integer $photo_id
 * @property integer $category_id
 * @property string $title
 * @property string $photo
 * @property string $time
 * @property integer $views
 * @property string $text
 */
class Photo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'views'], 'integer'],
            [['title', 'photo', 'text'], 'string'],
            [['time'], 'safe'],

            [['photo', 'title', 'category_id', 'text'], 'default', 'value' => null],
            [['views'], 'default', 'value' => 0],
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        Yii::$app->cache->delete('photos_'.$this->category_id);
        Yii::$app->cache->delete('photos_count_'.$this->category_id);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            Yii::$app->cache->delete('photos_'.$this->category_id);
            Yii::$app->cache->delete('photos_count_'.$this->category_id);
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
            'photo_id' => 'Photo ID',
            'category_id' => 'Category ID',
            'title' => 'Название',
            'photo' => 'Фото',
            'time' => 'Дата',
            'views' => 'Просмотры',
            'text' => 'Описание',

            'image' => 'Фото'
        ];
    }

    public function behaviors()
    {
        return [
            'log' => [
                'class' => LogBehavior::className(),
            ],
            'photo' => [
                'class' => ItemImageBehavior::className(),
                'path' => '/img/photo/',
                'attr_name' => 'photo'
            ],
        ];
    }
}
