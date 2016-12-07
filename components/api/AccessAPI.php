<?php

namespace app\components\api;

use yii\base\Object;
use Yii;

class AccessAPI extends Object
{
    public static function can($perm_name) {
        $access = Yii::$app->cache->get('user_'.Yii::$app->user->id.'_perm_'.$perm_name);
        if (!$access) {
            $access = Yii::$app->user->can($perm_name);
             Yii::$app->cache->set('user_'.Yii::$app->user->id.'_perm_'.$perm_name, $access);
        }
        return $access;
    }
}
