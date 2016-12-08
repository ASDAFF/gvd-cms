<?php

namespace app\modules\pages\api;

use app\modules\pages\models\Page;
use yii\base\Object;
use Yii;

class PageAPI extends Object
{
    // Получение Страницы по id или slug

    public static function page($key) {
        $page = Yii::$app->cache->get('page_'.$key);
        if (!$page) {
            $page = Page::find()->where(['page_id' => $key])->orWhere(['slug' => $key])->one();
            Yii::$app->cache->set('page_'.$key, $page);
        }
        return $page;
    }

    // Получение корневых Страниц

    public static function rootPages() {
        $pages = Yii::$app->cache->get('root_pages');
        if (!$pages) {
            $pages = Page::find()->where(['root_page_id' => null])->orderBy(['page_id' => SORT_ASC])->all();
            Yii::$app->cache->set('root_pages', $pages);
        }
        return $pages;
    }
}
