<?php

namespace app\modules\text_info\api;

use app\modules\text_info\models\TextInfo;
use yii\base\Object;
use Yii;

class TextsAPI extends Object
{
    // Получение Текстовой информации по id или key

    public static function text($key) {
        $txt = Yii::$app->cache->get('text_'.$key);
        if (!$txt) {
            $txt = TextInfo::find()->where(['text_info_id' => $key])->orWhere(['text_info_key' => $key])->one();
            Yii::$app->cache->set('text_'.$key, $txt);
        }
        return $txt;
    }
}
