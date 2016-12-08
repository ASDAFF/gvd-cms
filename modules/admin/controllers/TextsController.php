<?php

namespace app\modules\admin\controllers;

use app\modules\text_info\api\TextsAPI;
use app\modules\text_info\models\TextInfo;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\Log;


class TextsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['viewTexts']
                    ],
                    [
                        'actions' => ['create', 'delete'],
                        'allow' => true,
                        'roles' => ['root']
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['updateTexts']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {

        if (!Yii::$app->getModule('texts')->status) {
            throw new HttpException(404, 'Page not found');
        }

        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        return parent :: beforeAction($action);
    }

    public function actionIndex()
    {
        $texts = TextInfo::find()->all();

        return $this->render('index', [
            'texts' => $texts
        ]);
    }

    public function actionCreate() {
        $model = new TextInfo();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('texts_flash', '<strong>Успешно добавлено: </strong>"' . $model->title . '".');
                return $this->redirect(['/admin/texts/index']);
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id) {
        $model = TextsAPI::text($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('texts_flash', '<strong>Успешно отредактирована текстовая информация: </strong>"' . $model->title . '".');

                $log = new Log();
                $log->action = 'update';
                $log->user_ip = Yii::$app->request->userIP;
                $log->user_agent = Yii::$app->request->userAgent;
                $log->user_id = Yii::$app->user->id;
                $log->item_id = $model->primaryKey;
                $log->item_class = $model->className();
                $log->save();

                return $this->redirect(['/admin/texts/index']);
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete() {
        $id = Yii::$app->request->post('id');
        $item = TextsAPI::text($id);
        $name = $item->title;
        if ($item->delete()) {
            Yii::$app->session->setFlash('texts_flash', '<strong>Текстовая информация "'.$name.'" успешно удалена.</strong>');
            return true;
        }
        Yii::$app->session->setFlash('texts_flash', '<strong>Не удалось удалить текстовую информацию: "'.$name.'"...</strong>');
    }
}
