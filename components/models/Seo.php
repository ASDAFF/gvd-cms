<?php

namespace app\components\models;

use Yii;

/**
 * This is the model class for table "seo".
 *
 * @property integer $seo_id
 * @property string $item_class
 * @property integer $item_id
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $robots
 */
class Seo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_class', 'item_id'], 'required'],
            [['item_class', 'robots'], 'string'],
            [['item_id'], 'integer'],
            [['title', 'keywords', 'description'], 'string', 'max' => 255],

            [['robots'], 'default', 'value' => 'noindex, nofollow'],
            [['title', 'description', 'keywords'], 'default', 'value' => null]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'seo_id' => 'Seo ID',
            'item_class' => 'Item Class',
            'item_id' => 'Item ID',
            'title' => 'Title (Заголовок страницы)',
            'keywords' => 'Meta Keywords (Ключевые слова)',
            'description' => 'Meta Description (Описание страницы)',
            'robots' => 'Meta Robots (Индексирование страницы)',
        ];
    }
}
