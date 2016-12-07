<?php

namespace yii\easyii\modules\gvd_user\controllers;

use yii\easyii\components\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\easyii\modules\gvd_user\models\User;
use yii\easyii\modules\gvd_user\models\UserSearch;
use yii\easyii\modules\gvd_user\models\UserForm;


class AController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'view', 'changestatus', 'changerole'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isUserAdmin(Yii::$app->user->identity->email);
                        },
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->user->setReturnUrl(['/admin/user']);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'data' => $dataProvider
        ]);
    }

    public function actionCreate() {
        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        $model = new UserForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->Adduser()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionView($id) {
        $u = User::findOne(['id' => $id]);

        return $this->render('view', [
            'model' => $u
        ]);
    }

    public function actionDelete($id) {
        $u = User::findOne(['id' => $id]);
        $u->delete();
        $this->redirect(['index']);
    }

    public function actionChangestatus($id) {
        $u = User::findOne(['id' => $id]);
        if ($u->status == 10) {
            $u->status = 0;
        }
        else $u->status = 10;
        $u->save();

        $this->redirect(['view', 'id' => $id]);
    }

    public function actionChangerole($id) {
        $u = User::findOne(['id' => $id]);
        if ($u->role == 0) {
            $u->role = 20;
        }
        else $u->role = 0;
        $u->save();

        $this->redirect(['view', 'id' => $id]);
    }
}
