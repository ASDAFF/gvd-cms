<?php

namespace app\modules\video\models;

use app\components\behaviors\ItemImageBehavior;
use Yii;
use yii\web\UploadedFile;
use app\components\behaviors\LogBehavior;

/**
 * This is the model class for table "video".
 *
 * @property integer $video_id
 * @property integer $category_id
 * @property string $title
 * @property string $cover
 * @property string $time
 * @property string $url
 */
class Video extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id'], 'integer'],
            [['title', 'cover', 'url', 'text'], 'string'],
            [['time', 'url'], 'required'],
            [['time'], 'safe'],
            ['url', 'match', 'pattern' => '/https?:\/\/(www\.)?youtube\.com\/watch\?v=([\w-]{11}).*/i', 'message' => 'Формат URL не соответствует шаблону: https://www.youtube.com/watch?v=код_видео'],

            [['cover', 'title', 'category_id', 'text'], 'default', 'value' => null],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'video_id' => 'Video ID',
            'category_id' => 'Category ID',
            'title' => 'Название',
            'cover' => 'Обложка',

            'image' => 'Обложка',

            'time' => 'Time',
            'url' => 'URL',
            'text' => 'Описание'
        ];
    }

    public function behaviors()
    {
        return [
            'log' => [
                'class' => LogBehavior::className(),
            ],
            'cover' => [
                'class' => ItemImageBehavior::className(),
                'path' => '/img/video/covers/',
                'attr_name' => 'cover'
            ],
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        Yii::$app->cache->delete('videos_'.$this->category_id);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            Yii::$app->cache->delete('videos_'.$this->category_id);
            return true;
        } else {
            return false;
        }
    }

    public function getIframeUrl() {
        return str_replace('watch?v=', 'embed/', $this->url);
    }
}
