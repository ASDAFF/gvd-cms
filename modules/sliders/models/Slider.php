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
}
