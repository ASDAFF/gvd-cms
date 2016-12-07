<?php

namespace yii\easyii\modules\gvd_user\actions;

use Yii;
use yii\base\Action;

class LogoutAction extends Action
{

    public function run()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }

        return Yii::$app->getResponse()->redirect(['/']);
    }
}