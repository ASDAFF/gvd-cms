<?php

namespace app\modules\video;

use app\components\models\ModuleSettings;
use Yii;
/**
 * video module definition class
 */
class Video extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\video\controllers';

    public $status;
    public $categories;
    public $category_cover;
    public $category_description;
    public $category_text;
    public $category_icon;
    public $category_date;
    public $video_text;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $its = Yii::$app->cache->get('module_settings_'.$this->className());
        if (!$its) {
            $its = ModuleSettings::findAll(['module_class' => $this->className()]);
            Yii::$app->cache->set('module_settings_'.$this->className(), $its);
        }
        foreach ($its as $it) {
            $this->{$it->key} = $it->value;
        }
    }
}
