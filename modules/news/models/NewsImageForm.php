<?php

namespace app\modules\news\models;

use Yii;
use yii\base\Model;

class NewsImageForm extends Model
{
    public $news_id;
    public $label;
    public $images;
    public $image;

    public function rules()
    {
        return [
            [['news_id', 'label'], 'required'],
            [['label'], 'in', 'range' => ['cover', 'main', 'extra', 'slider'], 'strict' => false],
            [['news_id'], 'integer'],

            [['images'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'checkExtensionByMimeType' => false, 'maxFiles' => 20],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'checkExtensionByMimeType' => false],

        ];
    }

    public function attributeLabels()
    {
        return [

        ];
    }
}
