<?php

namespace yii\easyii\modules\gvd_user\actions;

use Yii;
use yii\base\Action;
use yii\easyii\modules\gvd_user\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

class ResetpasswordAction extends Action
{

    public $view = '@easyii/modules/gvd_user/views/default/resetpassword';

    public function run()
    {
        $token = $_GET['token'];
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            return $this->controller->redirect(['site/login']);
        }

        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        return $this->controller->render('resetpassword', [
            'model' => $model,
        ]);
    }
}