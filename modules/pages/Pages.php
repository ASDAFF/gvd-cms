<?php

namespace app\modules\pages;

use app\components\models\ModuleSettings;
use Yii;

class Pages extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\pages\controllers';

    public $status;
    public $cover;

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
