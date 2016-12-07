<?php

namespace app\modules\gvd_user\actions;

use Yii;
use yii\base\Action;

class GetAvatarAction extends Action
{

    public function run()
    {
        return Yii::$app->user->identity->avatar;
    }
}