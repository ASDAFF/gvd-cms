<?php

namespace app\modules\video\models;

use app\components\behaviors\ItemIconBehavior;
use Yii;
use app\modules\admin\models\Log;
use app\components\behaviors\LogBehavior;
use app\components\behaviors\ItemImageBehavior;
use app\components\behaviors\SeoBehavior;

/**
 * This is the model class for table "video_category".
 *
 * @property integer $video_category_id
 * @property string $title
 * @property integer $parent_id
 * @property string $cover
 * @property string $description
 * @property string $text
 * @property string $date
 * @property string $icon
 */
class VideoCategory extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'video_category';
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
                'path' => '/img/video/category-covers/',
                'attr_name' => 'cover'
            ],
            'icon' => [
                'class' => ItemIconBehavior::className(),
                'path' => '/img/video/category-icons/',
                'attr_name' => 'icon'
            ],
            'seo' => [
                'class' => SeoBehavior::className(),
            ]
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        if ($this->parent_id == 0) {
            Yii::$app->cache->delete('root_video_cats');
        }
        else {
            Yii::$app->cache->delete('video_category_is_subcategories_'.$this->parent_id);
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            Yii::$app->cache->delete('video_category_subcategories_'.$this->parent_id);
            Yii::$app->cache->delete('video_category_is_subcategories_'.$this->parent_id);
            if ($this->parent_id == 0) Yii::$app->cache->delete('root_video_cats');

            foreach ($this->videos as $v) {
                $v->delete();
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
            'video_category_id' => 'Video Category ID',
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
        $subs = Yii::$app->cache->get('video_category_subcategories_'.$this->primaryKey);
        if (!$subs) {
            $subs = VideoCategory::find()->where(['parent_id' => $this->primaryKey])->all();
            Yii::$app->cache->set('video_category_subcategories_'.$this->primaryKey, $subs);
        }
        return $subs;
    }

    public function getIsSubcategories() {
        $c = Yii::$app->cache->get('video_category_is_subcategories_'.$this->primaryKey);
        if (!$c) {
            $c = VideoCategory::find()->where(['parent_id' => $this->video_category_id])->count();
            Yii::$app->cache->set('video_category_is_subcategories_'.$this->primaryKey, ['count' => $c]);
        }
        else $c = $c['count'];
        return $c;
    }

    public function getParent() {
        return VideoCategory::findOne(['video_category_id' => $this->parent_id]);
    }

    public function getVideos() {
        $videos = Yii::$app->cache->get('videos_'.$this->primaryKey);
        if (!$videos) {
            $videos = Video::findAll(['category_id' => $this->video_category_id]);
            Yii::$app->cache->set('videos_'.$this->primaryKey, $videos);
        }
        return $videos;
    }
}
