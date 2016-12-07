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
}
