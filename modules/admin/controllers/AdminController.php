<?php

namespace app\modules\admin\controllers;

use app\modules\news\api\NewsAPI;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use app\modules\gvd_user\models\LoginForm;
use app\modules\admin\models\Log;

/**
 * Default controller for the `admin` module
 */
class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['accessAdmin']
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => '@app/modules/admin/views/admin/error.php'
            ],
        ];
    }

    public function beforeAction($action)
    {

        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        return parent :: beforeAction($action);
    }

    public function actionIndex()
    {
        Yii::$app->user->setReturnUrl(['/admin']);

        $pop_news = NewsAPI::popularByViews(3);
        $pop_news_arr = [];
        foreach ($pop_news as $pop) {
            array_push($pop_news_arr, ['label' => $pop->title, 'value' => $pop->views]);
        }
        //return json_encode($pop_news_arr);

        return $this->render('index', [
            'pop_news' => json_encode($pop_news_arr)
        ]);
    }

    public function actionLogin() {
        $this->layout = false;

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $log = new Log();
            $log->action = 'enter';
            $log->user_ip = Yii::$app->request->userIP;
            $log->user_agent = Yii::$app->request->userAgent;
            $log->user_id = Yii::$app->user->id;
            $log->save();
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }

    public function actionLogout() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }

        return $this->redirect(['index']);
    }
}
