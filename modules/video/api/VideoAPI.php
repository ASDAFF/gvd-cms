<?php

namespace app\modules\video\api;

use yii\base\Object;
use app\modules\video\models\VideoCategory;
use Yii;

class VideoAPI extends Object
{
    public static function rootCats() {
        $roots = Yii::$app->cache->get('root_video_cats');
        if (!$roots) {
            $roots = VideoCategory::findAll(['parent_id' => 0]);
            Yii::$app->cache->set('root_video_cats', $roots);
        }

        return $roots;
    }
}
