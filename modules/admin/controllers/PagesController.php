<?php

namespace app\modules\admin\controllers;

use app\modules\pages\api\PageAPI;
use app\modules\pages\models\DataForm;
use app\modules\pages\models\Page;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\Log;


class PagesController extends Controller
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
                        'roles' => ['viewPages']
                    ],
                    [
                        'actions' => ['create', 'update'],
                        'allow' => true,
                        'roles' => ['createUpdatePages']
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['deletePages']
                    ],
                    [
                        'actions' => ['data', 'create-data', 'update-data', 'delete-data'],
                        'allow' => true,
                        'roles' => ['root']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'delete-data' => ['post']
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'page-image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => 'http://'.$_SERVER['HTTP_HOST'].'/img/pages', // Directory URL address, where files are stored.
                'path' => Yii::getAlias('@webroot').'/img/pages' // Or absolute path to directory where files are stored.
            ],
        ];
    }

    public function beforeAction($action)
    {

        if (!Yii::$app->getModule('pages')->status) {
            throw new HttpException(404, 'Page not found');
        }

        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        return parent :: beforeAction($action);
    }

    public function actionIndex()
    {
        $pages = Page::find()->all();

        return $this->render('index', [
            'pages' => $pages
        ]);
    }

    public function actionCreate() {
        $model = new Page();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('pages_flash', '<strong>Успешно добавлена страница: </strong>"' . $model->title . '".');
                return $this->redirect(['/admin/pages/index']);
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id) {
        $model = PageAPI::page($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('pages_flash', '<strong>Успешно отредактирована страница: </strong>"' . $model->title . '".');

                $log = new Log();
                $log->action = 'update';
                $log->user_ip = Yii::$app->request->userIP;
                $log->user_agent = Yii::$app->request->userAgent;
                $log->user_id = Yii::$app->user->id;
                $log->item_id = $model->primaryKey;
                $log->item_class = $model->className();
                $log->save();

                return $this->redirect(['/admin/pages/index']);
            }
        }

        $model->seo_title = $model->seo('title');
        $model->seo_keywords = $model->seo('keywords');
        $model->seo_description = $model->seo('description');
        $model->seo_robots = $model->seo('robots');

        foreach ($model->dataObj as $key => $field) {
            $model->fields[$key] = $field->value;
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }
    
    public function actionDelete() {
        $id = Yii::$app->request->post('id');
        $item = Page::findOne(['page_id' => $id]);
        $name = $item->title;
        if ($item->delete()) {
            Yii::$app->session->setFlash('pages_flash', '<strong>Страница "'.$name.'" успешно удалена.</strong>');
            return true;
        }
        Yii::$app->session->setFlash('pages_flash', '<strong>Не удалось удалить страницу: "'.$name.'"...</strong>');
    }

    public function actionData($id) {
        $page = PageAPI::page($id);


        return $this->render('data', [
            'page' => $page
        ]);
    }

    public function actionCreateData($id) {
        $page = PageAPI::page($id);

        $model = new DataForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->addField()) {
                Yii::$app->session->setFlash('pages_data_flash', '<strong>Успешно добавлено поле: </strong>"' . $model->title . '".');

                $log = new Log();
                $log->action = 'update';
                $log->user_ip = Yii::$app->request->userIP;
                $log->user_agent = Yii::$app->request->userAgent;
                $log->user_id = Yii::$app->user->id;
                $log->item_id = $page->primaryKey;
                $log->item_class = $page->className();
                $log->save();

                return $this->redirect(['/admin/pages/data', 'id' => $id]);
            }
        }

        return $this->render('create_data', [
            'page' => $page,
            'model' => $model
        ]);
    }

    public function actionUpdateData($id, $key) {
        $page = PageAPI::page($id);

        $model = new DataForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->editField()) {
                Yii::$app->session->setFlash('pages_data_flash', '<strong>Успешно добавлено поле: </strong>"' . $model->title . '".');

                $log = new Log();
                $log->action = 'update';
                $log->user_ip = Yii::$app->request->userIP;
                $log->user_agent = Yii::$app->request->userAgent;
                $log->user_id = Yii::$app->user->id;
                $log->item_id = $page->primaryKey;
                $log->item_class = $page->className();
                $log->save();

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
        $item = PageAPI::page($page);
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
