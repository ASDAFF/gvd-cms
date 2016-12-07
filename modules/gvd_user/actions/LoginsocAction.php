<?php

namespace yii\easyii\modules\gvd_user\actions;

use Yii;
use yii\base\Action;
use yii\easyii\modules\gvd_user\models\User;

class LoginsocAction extends Action
{

    public function run()
    {
        $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
        $user = json_decode($s, true);
        $user_our = User::find()->where(['soc_id' => $user['network'].$user['uid']])->one();
        if ($user_our) {
            $user_our->name = $user['first_name'];
            $user_our->last_name = $user['last_name'];
            $user_our->avatar = $user['photo_big'];
            $user_our->updated_at = time();
            $user_our->save();
            Yii::$app->user->login($user_our);
            return Yii::$app->getResponse()->redirect(['/']);
        }
        else {
            $model = new User();
            $model->name = $user['first_name'];
            $model->last_name = $user['last_name'];
            $model->avatar = '';
            $model->soc_id = $user['network'].$user['uid'];
            $model->status = 10;
            $model->created_at = time();
            $model->updated_at = time();
            if ($model->save()) Yii::$app->user->login($model);
            return Yii::$app->getResponse()->redirect(['/']);
        }

    }
}