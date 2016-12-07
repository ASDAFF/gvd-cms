<?php

namespace yii\easyii\modules\gvd_user\actions;

use Yii;
use yii\base\Action;
use yii\easyii\modules\gvd_user\models\LoginForm;
use yii\easyii\modules\gvd_user\models\SignupForm;

class LoginAction extends Action
{

    public $view = '@easyii/modules/gvd_user/views/default/login';

    public function run()
    {
        if (Yii::$app->user->isGuest) {

            $model = new LoginForm();
            $model_reg = new SignupForm();

            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return Yii::$app->getResponse()->redirect(['/']);
            }

            if ($model_reg->load(Yii::$app->request->post())) {
                if ($user = $model_reg->signup()) {
                    if (Yii::$app->getUser()->login($user)) {
                        return Yii::$app->getResponse()->redirect(['/']);
                    }
                }
            }

            Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

            return $this->controller->render($this->view, [
                'model' => $model,
                'model_reg' => $model_reg
            ]);

        }
        else {
            return Yii::$app->getResponse()->redirect(['/']);
        }
    }
}