<?php

namespace app\modules\pages\models;

use Yii;
use app\components\behaviors\SeoBehavior;
use app\components\behaviors\ItemImageBehavior;
use app\components\behaviors\ItemIconBehavior;
use app\components\behaviors\LogBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "page".
 *
 * @property integer $page_id
 * @property string $title
 * @property string $text
 * @property string $cover
 * @property string $photo
 * @property string $data
 */
class Page extends \yii\db\ActiveRecord
{
    public $fields = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $seo = $this->getBehavior('seo')->rules();
        return array_merge($seo, [
            [['title'], 'required'],
            [['text', 'cover', 'photo', 'data'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['root_page_id'], 'integer'],

            ['fields', 'each', 'rule' => ['string']],

            [['root_page_id'], 'default', 'value' => null]
        ]);
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => 'yii\behaviors\SluggableBehavior',
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'ensureUnique' => true,
                'immutable' => true
            ],
            'log' => [
                'class' => LogBehavior::className(),
            ],
            'cover' => [
                'class' => ItemImageBehavior::className(),
                'path' => '/img/pages/covers/',
                'attr_name' => 'cover'
            ],
            'photo' => [
                'class' => ItemIconBehavior::className(),
                'path' => '/img/pages/photos/',
                'attr_name' => 'photo'
            ],
            'seo' => [
                'class' => SeoBehavior::className(),
            ]
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            $d = json_decode($this->data);
            foreach ($this->fields as $key => $field) {
                $d->{$key}->value = $field;
            }
            $this->data = json_encode($d);

            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        Yii::$app->cache->delete('page_'.$this->primaryKey);
        Yii::$app->cache->delete('page_'.$this->slug);

        if (!$this->root_page_id) {
            Yii::$app->cache->delete('root_pages');
        }
        else {
            Yii::$app->cache->delete('pages_'.$this->root_page_id);
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            Yii::$app->cache->delete('page_'.$this->primaryKey);
            Yii::$app->cache->delete('page_'.$this->slug);

            if (!$this->root_page_id) {
                Yii::$app->cache->delete('root_pages');
            }
            else {
                Yii::$app->cache->delete('pages_'.$this->root_page_id);
            }

            foreach ($this->pages as $p) {
                $p->delete();
            }

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
        $seo = $this->getBehavior('seo')->attributeLabels();
        return array_merge($seo, [
            'page_id' => 'Page ID',
            'title' => 'Заголовок',
            'text' => 'Текст',
            'cover' => 'Обложка',
            'photo' => 'Фото',
            'data' => 'Data',

            'image' => 'Обложка',
            'icon_img' => 'Фото'
        ]);
    }

    public function getDataObj() {
        return json_decode($this->data);
    }

    public function getPages() {
        $pages = Yii::$app->cache->get('pages_'.$this->primaryKey);
        if (!$pages) {
            $pages = Page::findAll(['root_page_id' => $this->primaryKey]);
            Yii::$app->cache->set('pages_'.$this->primaryKey, $pages);
        }
        return $pages;
    }
}
