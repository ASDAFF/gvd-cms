<?php

namespace app\components\behaviors;

use yii\db\ActiveRecord;
use yii\base\Behavior;
use app\components\models\Seo;
use Yii;

class SeoBehavior extends Behavior
{
    public $seo_title;
    public $seo_keywords;
    public $seo_description;
    public $seo_robots;

    public function rules()
    {
        return [
            [['seo_robots'], 'string'],
            [['seo_title', 'seo_keywords', 'seo_description'], 'string', 'max' => 255],

            [['seo_robots'], 'default', 'value' => 'noindex, nofollow'],
            [['seo_title', 'seo_description', 'seo_keywords'], 'default', 'value' => null]
        ];
    }

    public function attributeLabels()
    {
        return [
            'seo_title' => 'Title (Заголовок страницы)',
            'seo_keywords' => 'Meta Keywords (Ключевые слова)',
            'seo_description' => 'Meta Description (Описание страницы)',
            'seo_robots' => 'Meta Robots (Индексирование страницы)',
        ];
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }

    public function afterDelete($event)
    {
        $seo = Seo::findOne(['item_class' => $this->owner->className(), 'item_id' => $this->owner->primaryKey]);
        if ($seo) {
            $seo->delete();
            Yii::$app->cache->delete('seo_'.$this->owner->className().'_'.$this->owner->primaryKey);
        }
    }

    public function afterSave($event)
    {
        $seo = Seo::findOne(['item_class' => $this->owner->className(), 'item_id' => $this->owner->primaryKey]);
        if (!$seo) {
            $seo = new Seo();
            $seo->item_class = $this->owner->className();
            $seo->item_id = $this->owner->primaryKey;
        }
        $seo->title = $this->owner->seo_title;
        $seo->description = $this->owner->seo_description;
        $seo->keywords = $this->owner->seo_keywords;
        $seo->robots = $this->owner->seo_robots;
        $seo->save();
        Yii::$app->cache->delete('seo_'.$this->owner->className().'_'.$this->owner->primaryKey);
    }

    public function seo($attr) {
        $seo = Yii::$app->cache->get('seo_'.$this->owner->className().'_'.$this->owner->primaryKey);
        if (!$seo) {
            $seo = Seo::findOne(['item_class' => $this->owner->className(), 'item_id' => $this->owner->primaryKey]);
            Yii::$app->cache->set('seo_'.$this->owner->className().'_'.$this->owner->primaryKey, $seo);
        }
        if ($seo) {
            return $seo->{$attr};
        }
        return null;
    }
}

?>