<?php

namespace app\modules\sliders\models;

use Yii;
use app\components\behaviors\LogBehavior;
use app\components\behaviors\ItemImageBehavior;
use app\components\behaviors\ItemIconBehavior;

/**
 * This is the model class for table "slider_item".
 *
 * @property integer $slider_item_id
 * @property integer $slider_id
 * @property string $title
 * @property string $description
 * @property string $link
 * @property string $photo
 * @property string $photo_hover
 * @property integer $order_num
 */
class SliderItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slider_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slider_id'], 'required'],
            [['slider_id', 'order_num'], 'integer'],
            [['title', 'description', 'link', 'photo', 'photo_hover'], 'string'],

            [['photo_hover', 'link', 'title', 'description'], 'default', 'value' => null],
            [['order_num'], 'default', 'value' => 0]
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if (!$this->order_num) {
                $this->order_num = $this->parent->count + 1;
            }

            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        Yii::$app->cache->delete('slides_'.$this->slider_id);
        Yii::$app->cache->delete('slides_count_'.$this->slider_id);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            Yii::$app->cache->delete('slides_'.$this->slider_id);
            Yii::$app->cache->delete('slides_count_'.$this->slider_id);

            return true;
        } else {
            return false;
        }
    }

    public function behaviors()
    {
        return [
            'log' => [
                'class' => LogBehavior::className(),
            ],
            'photo' => [
                'class' => ItemImageBehavior::className(),
                'path' => '/img/sliders/',
                'attr_name' => 'photo'
            ],
            'photo_hover' => [
                'class' => ItemIconBehavior::className(),
                'path' => '/img/sliders/hover/',
                'attr_name' => 'photo_hover'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'slider_item_id' => 'Slider Item ID',
            'slider_id' => 'Slider ID',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'link' => 'Ссылка',
            'photo' => 'Photo',
            'photo_hover' => 'Photo Hover',
            'order_num' => 'Порядковый номер',

            'image' => 'Фото',
            'icon_img' => 'Фото при наведении'
        ];
    }

    public function getParent() {
        return Slider::findOne(['slider_id' => $this->slider_id]);
    }
}
