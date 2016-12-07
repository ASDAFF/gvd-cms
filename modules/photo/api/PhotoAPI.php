<?php

namespace app\modules\photo\api;

use yii\base\Object;
use app\modules\photo\models\PhotoCategory;
use Yii;

class PhotoAPI extends Object
{
    public static function rootCats() {
        $roots = Yii::$app->cache->get('root_photo_cats');
        if (!$roots) {
            $roots = PhotoCategory::findAll(['parent_id' => 0]);
            Yii::$app->cache->set('root_photo_cats', $roots);
        }

        return $roots;
    }
}
