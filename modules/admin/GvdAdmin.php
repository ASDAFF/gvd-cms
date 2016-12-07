<?php

namespace app\modules\admin;

use Yii;
use yii\helpers\Url;
/**
 * admin module definition class
 */
class GvdAdmin extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    public $layout = 'admin';

    public $defaultRoute = 'admin';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$app->user->loginUrl = Url::to(['/admin/admin/login']);
        Yii::$app->errorHandler->errorAction = '/admin/admin/error';

        // custom initialization code goes here
    }
}
