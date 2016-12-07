<?php

namespace app\modules\news;

use yii\base\Module;
use app\components\models\ModuleSettings;
use Yii;

/**
 * news module definition class
 */
class News extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\news\controllers';

    public $indexImages;

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

        // custom initialization code goes here
    }
}
