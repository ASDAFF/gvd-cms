<?php

namespace app\modules\admin\controllers;

use app\modules\sliders\models\Slider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\modules\admin\models\Log;


class SlidersController extends Controller
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
                        'roles' => ['viewSliders']
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['root']
                    ],
                    [
                        'actions' => ['create-item', 'update-item'],
                        'allow' => true,
                        'roles' => ['createUpdateSliders']
                    ],
                    [
                        'actions' => ['delete-item'],
                        'allow' => true,
                        'roles' => ['deleteSliders']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'delete-item' => ['post']
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {

        if (!Yii::$app->getModule('sliders')->status) {
            throw new HttpException(404, 'Page not found');
        }

        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        return parent :: beforeAction($action);
    }

    public function actionIndex()
    {
        $sliders = Slider::find()->all();

        return $this->render('index', [
            'sliders' => $sliders
        ]);
    }

    public function actionCreate() {
        $model = new Slider();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('sliders_flash', '<strong>Успешно добавлен слайдер: </strong>"' . $model->title . '".');
                return $this->redirect(['/admin/sliders/index']);
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id) {
        $model = Slider::findOne(['slider_id' => $id]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('sliders_flash', '<strong>Успешно отредактирован слайдер: </strong>"' . $model->title . '".');

                $log = new Log();
                $log->action = 'update';
                $log->user_ip = Yii::$app->request->userIP;
                $log->user_agent = Yii::$app->request->userAgent;
                $log->user_id = Yii::$app->user->id;
                $log->item_id = $model->primaryKey;
                $log->item_class = $model->className();
                $log->save();

                return $this->redirect(['/admin/sliders/index']);
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }
    
    public function actionDelete() {
        $id = Yii::$app->request->post('id');
        $item = Slider::findOne(['slider_id' => $id]);
        $name = $item->title;
        if ($item->delete()) {
            Yii::$app->session->setFlash('sliders_flash', '<strong>Слайдер "'.$name.'" успешно удален.</strong>');
            return true;
        }
        Yii::$app->session->setFlash('sliders_flash', '<strong>Не удалось удалить слайдер: "'.$name.'"...</strong>');
    }

    public function actionData($id) {
        $page = Page::findOne(['page_id' => $id]);


        return $this->render('data', [
            'page' => $page
        ]);
    }

    public function actionCreateData($id) {
        $page = Page::findOne(['page_id' => $id]);

        $model = new DataForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->addField()) {
                Yii::$app->session->setFlash('pages_data_flash', '<strong>Успешно добавлено поле: </strong>"' . $model->title . '".');
                return $this->redirect(['/admin/pages/data', 'id' => $id]);
            }
        }

        return $this->render('create_data', [
            'page' => $page,
            'model' => $model
        ]);
    }

    public function actionUpdateData($id, $key) {
        $page = Page::findOne(['page_id' => $id]);

        $model = new DataForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->editField()) {
                Yii::$app->session->setFlash('pages_data_flash', '<strong>Успешно добавлено поле: </strong>"' . $model->title . '".');
                return $this->redirect(['/admin/pages/data', 'id' => $id]);
            }
        }

        $model->key = $key;
        $model->title = $page->dataObj->{$key}->title;
        $model->type = $page->dataObj->{$key}->type;

        return $this->render('update_data', [
            'page' => $page,
            'model' => $model
        ]);
    }
    
    public function actionDeleteData() {
        $key = Yii::$app->request->post('key');
        $page = Yii::$app->request->post('page');
        $item = Page::findOne(['page_id' => $page]);
        $name = $item->dataObj->{$key}->title;
        $d = ArrayHelper::toArray($item->dataObj);
        ArrayHelper::remove($d, $key);
        $item->data = json_encode($d);
        if ($item->save()) {
            Yii::$app->session->setFlash('pages_data_flash', '<strong>Поле "' . $name . '" успешно удалено.</strong>');
            return true;
        }
        Yii::$app->session->setFlash('pages_data_flash', '<strong>Поле "' . $name . '" не удалось удалить...</strong>');
    }
}
