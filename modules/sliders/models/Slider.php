<?php

namespace app\modules\sliders\models;

use Yii;
use app\components\behaviors\LogBehavior;

/**
 * This is the model class for table "slider".
 *
 * @property integer $slider_id
 * @property string $slider_key
 * @property string $title
 */
class Slider extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slider_key', 'title'], 'required'],
            [['slider_key', 'title'], 'string', 'max' => 255],
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

        Yii::$app->cache->delete('slider_'.$this->slider_id);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            Yii::$app->cache->delete('slider_'.$this->slider_id);

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
            'slider_id' => 'Slider ID',
            'slider_key' => 'Метка',
            'title' => 'Название',
        ];
    }

    public function getSlides() {
        $slides = Yii::$app->cache->get('slides_'.$this->primaryKey);
        if (!$slides) {
            $slides = SliderItem::find()->where(['slider_id' => $this->primaryKey])->orderBy(['order_num' => SORT_ASC, 'slider_item_id' => SORT_ASC])->all();
            Yii::$app->cache->set('slides_'.$this->primaryKey, $slides);
        }
        return $slides;
    }

    public function getCount() {
        $count = Yii::$app->cache->get('slides_count_'.$this->primaryKey);
        if (!$count) {
            $count = SliderItem::find()->where(['slider_id' => $this->primaryKey])->count();
            Yii::$app->cache->set('slides_count_'.$this->primaryKey, $count);
        }
        return $count;
    }

    public function getSlideByNum($num) {
        return SliderItem::find()->where(['slider_id' => $this->primaryKey, 'order_num' =>$num])->one();
    }
}
