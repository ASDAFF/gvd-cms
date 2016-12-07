<?php

namespace app\modules\news\models;

use Yii;
use yii\base\Model;
use app\modules\news\models\News;
use app\components\SeoBehavior;

class NewsForm extends Model
{
    public $id;
    public $title;
    public $description;
    public $epigraph;
    public $date;
    public $published;
    public $text;
    public $slider;

    public $seo_title;
    public $seo_keywords;
    public $seo_description;
    public $seo_robots;

    public function rules()
    {
        return [
            [['id', 'title', 'description', 'date', 'text'], 'required'],
            [['title', 'description', 'epigraph', 'date', 'text', 'seo_title'], 'string'],
            [['title'], 'unique', 'targetClass' => 'app\modules\news\models\News', 'message' => 'Новость с таким названием уже существует.'],
            [['published', 'slider', 'popular'], 'integer'],

            [['epigraph'], 'default', 'value' => null],
            [['slider', 'popular'], 'default', 'value' => 0],

            [['seo_robots'], 'string'],
            [['seo_title', 'seo_keywords', 'seo_description'], 'string', 'max' => 255],

            [['seo_robots'], 'default', 'value' => 'noindex, nofollow'],
            [['seo_title', 'seo_description', 'seo_keywords'], 'default', 'value' => null]

        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'description' => 'Краткое описание',
            'epigraph' => 'Эпиграф',
            'date' => 'Дата',
            'published' => 'Опубликовать на сайте',
            'text' => 'Текст новости',
            'slider' => 'Включить слайдер с фотографиями',
            'popular' => 'Сделать популярной',

            'seo_title' => 'Title (Заголовок страницы)',
            'seo_keywords' => 'Meta Keywords (Ключевые слова)',
            'seo_description' => 'Meta Description (Описание страницы)',
            'seo_robots' => 'Meta Robots (Индексирование страницы)',
        ];
    }

    public function addNews() {
        if ($this->validate()) {
            $item = News::findOne(['news_id' => $this->id]);
            $item->title = $this->title;
            $item->description = $this->description;
            $item->date = $this->date;
            $item->text = $this->text;
            $item->epigraph = $this->epigraph;
            $item->slider = $this->slider;
            $item->published = $this->published;
            $item->popular = $this->popular;

            $item->seo_title = $this->seo_title;
            $item->seo_description = $this->seo_description;
            $item->seo_keywords = $this->seo_keywords;
            $item->seo_robots = $this->seo_robots;

            return $item->save();
        }
        return false;
    }
}
