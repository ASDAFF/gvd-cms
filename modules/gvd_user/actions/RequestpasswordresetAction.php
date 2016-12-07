<?php

namespace yii\easyii\modules\gvd_user\actions;

use Yii;
use yii\base\Action;
use yii\easyii\modules\gvd_user\models\PasswordResetRequestForm;

class RequestpasswordresetAction extends Action
{

    public $view = '@easyii/modules/gvd_user/views/default/requestPasswordResetToken';

    public function run()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'На указанный email было выслано письмо.');
            } else {
                Yii::$app->session->setFlash('error', 'К сожалению, письмо не было выслано на указанный email. Проверьте его правильность.');
            }
        }

        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}