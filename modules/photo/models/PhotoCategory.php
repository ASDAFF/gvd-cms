<?php

namespace app\modules\photo\models;

use app\components\behaviors\ItemIconBehavior;
use Yii;
use app\modules\admin\models\Log;
use app\components\behaviors\LogBehavior;
use app\components\behaviors\ItemImageBehavior;
use app\components\behaviors\SeoBehavior;

/**
 * This is the model class for table "photo_category".
 *
 * @property integer $photo_category_id
 * @property string $title
 * @property integer $parent_id
 * @property string $cover
 * @property string $description
 * @property string $text
 * @property string $date
 * @property string $icon
 */
class PhotoCategory extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $seo = $this->getBehavior('seo')->rules();
        return array_merge($seo, [
            [['title', 'date'], 'required'],
            [['title', 'cover', 'description', 'text', 'icon'], 'string'],
            [['parent_id'], 'integer'],
            [['date'], 'safe'],

            [['cover', 'description', 'text', 'icon'], 'default', 'value' => null],
        ]);
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        if ($this->parent_id == 0) {
            Yii::$app->cache->delete('root_photo_cats');
        }
        else {
            Yii::$app->cache->delete('photo_category_is_subcategories_'.$this->parent_id);
        }
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
                'path' => '/img/photo/category-covers/',
                'attr_name' => 'cover'
            ],
            'icon' => [
                'class' => ItemIconBehavior::className(),
                'path' => '/img/photo/category-icons/',
                'attr_name' => 'icon'
            ],
            'seo' => [
                'class' => SeoBehavior::className(),
            ]
        ];
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            Yii::$app->cache->delete('photo_category_subcategories_'.$this->parent_id);
            Yii::$app->cache->delete('photo_category_is_subcategories_'.$this->parent_id);
            if ($this->parent_id == 0) Yii::$app->cache->delete('root_photo_cats');
            foreach ($this->photos as $p) {
                $p->delete();
            }

            foreach ($this->subcategories as $s) {
                $s->delete();
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
            'photo_category_id' => 'Photo Category ID',
            'title' => 'Название',
            'parent_id' => 'Родительская категория',
            'cover' => 'Обложка',
            'description' => 'Краткое описание',
            'text' => 'Полное описание',
            'date' => 'Дата',
            'icon' => 'Иконка',

            'image' => 'Обложка',
            'icon_img' => 'Иконка'
        ]);
    }

    public function getSubcategories() {
        $subs = Yii::$app->cache->get('photo_category_subcategories_'.$this->primaryKey);
        if (!$subs) {
            $subs = PhotoCategory::find()->where(['parent_id' => $this->primaryKey])->all();
            Yii::$app->cache->set('photo_category_subcategories_'.$this->primaryKey, $subs);
        }
        return $subs;
    }

    public function getIsSubcategories() {
        $c = Yii::$app->cache->get('photo_category_is_subcategories_'.$this->primaryKey);
        if (!$c) {
            $c = PhotoCategory::find()->where(['parent_id' => $this->photo_category_id])->count();
            Yii::$app->cache->set('photo_category_is_subcategories_'.$this->primaryKey, ['count' => $c]);
        }
        else $c = $c['count'];
        return $c;
    }

    public function getParent() {
        return PhotoCategory::findOne(['photo_category_id' => $this->parent_id]);
    }

    public function getPhotos() {
        $photos = Yii::$app->cache->get('photos_'.$this->primaryKey);
        if (!$photos) {
            $photos = Photo::findAll(['category_id' => $this->photo_category_id]);
            Yii::$app->cache->set('photos_'.$this->primaryKey, $photos);
        }
        return $photos;
    }

    public function getCount() {
        $photos_count = Yii::$app->cache->get('photos_count_'.$this->primaryKey);
        if (!$photos_count) {
            $photos_count = Photo::find()->where(['category_id' => $this->primaryKey])->count();
            Yii::$app->cache->set('photos_count_'.$this->primaryKey, ['count' => $photos_count]);
        }
        else $photos_count = $photos_count['count'];
        return $photos_count;
    }
}
