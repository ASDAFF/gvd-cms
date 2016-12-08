<?php

namespace app\modules\text_info;

use app\components\models\ModuleSettings;
use Yii;

class TextInfo extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\text_info\controllers';

    public $status;

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
