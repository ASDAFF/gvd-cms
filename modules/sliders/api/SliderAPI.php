<?php

namespace app\modules\sliders\api;

use app\modules\sliders\models\Slider;
use yii\base\Object;
use Yii;

class SliderAPI extends Object
{
    public static function slider($id) {
        $slider = Yii::$app->cache->get('slider_'.$id);
        if (!$slider) {
            $slider = Slider::findOne(['slider_id' => $id]);
            Yii::$app->cache->set('slider_'.$id, $slider);
        }
        return $slider;
    }
}
