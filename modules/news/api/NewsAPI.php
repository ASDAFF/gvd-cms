<?php

namespace app\modules\news\api;

use yii\base\Object;
use app\modules\news\models\News;
use Yii;

class NewsAPI extends Object
{
    public static function last($count) {
        $last = Yii::$app->cache->get('news_last_'.$count);
        if (!$last) {
            $last = News::find()->where(['published' => 1])->orderBy(['date' => SORT_DESC])->limit($count)->all();
            Yii::$app->cache->set('news_last_'.$count, $last);
        }
        return $last;
    }

    public static function popular($count) {
        $pop = Yii::$app->cache->get('news_pop_'.$count);
        if (!$pop) {
            $pop = News::find()->where(['published' => 1])->orderBy(['popular' => SORT_DESC, 'views' => SORT_DESC, 'date' => SORT_DESC])->limit($count)->all();
            Yii::$app->cache->set('news_pop_'.$count, $pop);
        }
        return $pop;
    }

    public static function popularByViews($count) {
        $pop = Yii::$app->cache->get('news_pop_by_views_'.$count);
        if (!$pop) {
            $pop = News::find()->where(['published' => 1])->orderBy(['views' => SORT_DESC, 'date' => SORT_DESC])->limit($count)->all();
            Yii::$app->cache->set('news_pop_by_views_'.$count, $pop);
        }
        return $pop;
    }
}
