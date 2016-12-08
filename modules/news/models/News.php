<?php

namespace app\modules\news\models;

use app\components\models\Seo;
use Yii;
use app\modules\news\models\NewsImage;
use app\components\behaviors\LogBehavior;
use app\components\behaviors\SeoBehavior;

/**
 * This is the model class for table "news".
 *
 * @property integer $news_id
 * @property string $title
 * @property string $description
 * @property string $epigraph
 * @property integer $views
 * @property string $date
 * @property integer $slider
 * @property string $text
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['views', 'slider', 'published', 'popular'], 'integer'],
            [['date'], 'safe'],
            [['text'], 'string'],
            [['title', 'description', 'epigraph'], 'string'],

            [['views', 'popular'], 'default', 'value' => 0],
        ];
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
            'seo' => [
                'class' => SeoBehavior::className(),
            ]
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        Yii::$app->cache->delete('news_item_'.$this->primaryKey);

        for ($i = 1; $i < 10; $i++) {
            Yii::$app->cache->delete('news_last_'.$i);
            Yii::$app->cache->delete('news_pop_'.$i);
            Yii::$app->cache->delete('news_pop_by_views_'.$i);
        }

        if ($this->slider == 0 || $this->slider == null) {
            foreach ($this->sliderPhoto as $photo) {
                $photo->delete();
            }
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            Yii::$app->cache->delete('news_item_'.$this->primaryKey);

            for ($i = 1; $i < 10; $i++) {
                Yii::$app->cache->delete('news_last_'.$i);
                Yii::$app->cache->delete('news_pop_'.$i);
                Yii::$app->cache->delete('news_pop_by_views_'.$i);
            }

            $photos = NewsImage::findAll(['news_id' => $this->news_id]);
            foreach ($photos as $p) {
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
        return [
            'news_id' => 'News ID',
            'title' => 'Название',
            'description' => 'Краткое описание',
            'epigraph' => 'Эпиграф',
            'views' => 'Просмотры',
            'date' => 'Дата',
            'slider' => 'Включить слайдер с фото',
            'text' => 'Текст новости',
            'popular' => 'Популярная'
        ];
    }

    public function getSliderPhoto() {
        $photos = Yii::$app->cache->get('news_slider_photos_'.$this->primaryKey);
        if (!$photos) {
            $photos = NewsImage::findAll(['news_id' => $this->news_id, 'label' => 'slider']);
            Yii::$app->cache->set('news_slider_photos_'.$this->primaryKey, $photos);
        }
        return $photos;
    }

    public function getCover() {
        $photo = Yii::$app->cache->get('news_cover_photo_'.$this->primaryKey);
        if (!$photo) {
            $photo = NewsImage::findOne(['news_id' => $this->news_id, 'label' => 'cover']);
            Yii::$app->cache->set('news_cover_photo_'.$this->primaryKey, $photo);
        }
        return $photo;
    }

    public function getCoverPath() {
        return $this->cover->image;
    }

    public function getMainImg() {
        $photo = Yii::$app->cache->get('news_main_photo_'.$this->primaryKey);
        if (!$photo) {
            $photo = NewsImage::findOne(['news_id' => $this->news_id, 'label' => 'main']);
            Yii::$app->cache->set('news_main_photo_'.$this->primaryKey, $photo);
        }
        return $photo;
    }

    public function getMainImgPath() {
        return $this->mainImg->image;
    }

    public function getExtraImg() {
        $photo = Yii::$app->cache->get('news_extra_photo_'.$this->primaryKey);
        if (!$photo) {
            $photo = NewsImage::findOne(['news_id' => $this->news_id, 'label' => 'extra']);
            Yii::$app->cache->set('news_extra_photo_'.$this->primaryKey, $photo);
        }
        return $photo;
    }

    public function getExtraImgPath() {
        return $this->extraImg->image;
    }
}
