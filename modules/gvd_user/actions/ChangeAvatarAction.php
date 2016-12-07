<?php

namespace app\modules\gvd_user\actions;

use Yii;
use yii\base\Action;
use app\modules\gvd_user\models\UserAvatarForm;
use app\modules\gvd_user\models\User;
use yii\web\UploadedFile;

class ChangeAvatarAction extends Action
{

    public $path = '/img/users/';

    public function run()
    {
        $model = new UserAvatarForm();
        $response = true;
        if ($model->load(Yii::$app->request->post())) {
            $model->avatar = UploadedFile::getInstance($model, 'avatar');
            if ($model->validate()) {
                if ($model->avatar) {
                    $photo = $model->avatar;
                    $name = \rand(100000000, 999999999);
                    $name = $this->checkUniqName($name);
                    $photo->saveAs(dirname(dirname(dirname(__DIR__))) . '/web' . $this->path . $name . '.' . $photo->extension);
                    $user = User::findOne(['id' => Yii::$app->user->id]);
                    $user->avatar = $this->path . $name . '.' . $photo->extension;
                    if (!$user->save()) $response = false;
                }
            }
            else {
                $response = false;
            }
        }
        else {
            $response = false;
        }
        return $response;
    }

    private function checkUniqName($name) {
        $base = User::find()->all();
        foreach ($base as $p) {
            if (substr($p->avatar, strrpos($p->avatar, '/')+1, strrpos($p->avatar, '.')) == $name) {
                $new_name = \rand(100000000, 999999999);
                $this->checkUniqName($new_name);
            }
        }
        return $name;
    }
}