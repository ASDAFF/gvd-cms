<?php

namespace app\modules\news\models;

use Yii;

/**
 * This is the model class for table "news_image".
 *
 * @property integer $news_image_id
 * @property integer $news_id
 * @property string $image
 * @property string $label
 */
class NewsImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_id', 'image', 'label'], 'required'],
            [['news_id'], 'integer'],
            [['label'], 'string'],
            [['image'], 'string', 'max' => 255],
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        switch ($this->label) {
            case 'slider': {
                Yii::$app->cache->delete('news_slider_photos_' . $this->news_id);
                break;
            }
            case 'cover': {
                Yii::$app->cache->delete('news_cover_photo_' . $this->news_id);
                break;
            }
            case 'main': {
                Yii::$app->cache->delete('news_main_photo_' . $this->news_id);
                break;
            }
            case 'extra': {
                Yii::$app->cache->delete('news_extra_photo_' . $this->news_id);
                break;
            }
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            @unlink(dirname(dirname(dirname(__DIR__))) . '/web'.$this->image);

            switch ($this->label) {
                case 'slider': {
                    Yii::$app->cache->delete('news_slider_photos_' . $this->news_id);
                    break;
                }
                case 'cover': {
                    Yii::$app->cache->delete('news_cover_photo_' . $this->news_id);
                    break;
                }
                case 'main': {
                    Yii::$app->cache->delete('news_main_photo_' . $this->news_id);
                    break;
                }
                case 'extra': {
                    Yii::$app->cache->delete('news_extra_photo_' . $this->news_id);
                    break;
                }
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
            'news_image_id' => 'News Image ID',
            'news_id' => 'News ID',
            'image' => 'Image',
            'label' => 'Label',
        ];
    }
}
