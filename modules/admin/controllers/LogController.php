<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Log;
use app\modules\pages\models\Page;
use app\modules\photo\models\Photo;
use app\modules\photo\models\PhotoCategory;
use app\modules\video\models\VideoCategory;
use app\modules\video\models\Video;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use app\modules\news\models\News;
use yii\web\HttpException;

/**
 * Default controller for the `admin` module
 */
class LogController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['enter'],
                        'allow' => true,
                        'roles' => ['enterLog']
                    ],
                    [
                        'actions' => ['news'],
                        'allow' => true,
                        'roles' => ['newsLog']
                    ],
                    [
                        'actions' => ['video'],
                        'allow' => true,
                        'roles' => ['videoLog']
                    ],
                    [
                        'actions' => ['video-category'],
                        'allow' => true,
                        'roles' => ['videoCategoryLog']
                    ],
                    [
                        'actions' => ['photo'],
                        'allow' => true,
                        'roles' => ['photoLog']
                    ],
                    [
                        'actions' => ['photo-category'],
                        'allow' => true,
                        'roles' => ['photoCategoryLog']
                    ],
                    [
                        'actions' => ['pages'],
                        'allow' => true,
                        'roles' => ['pagesLog']
                    ],

                    [
                        'actions' => ['clear'],
                        'allow' => true,
                        'roles' => ['root']
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {

        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        return parent :: beforeAction($action);
    }

    public function actionEnter()
    {
        $logs = Log::find()->where(['action' => 'enter'])->orderBy(['time' => SORT_DESC])->all();

        return $this->render('enter', [
            'logs' => $logs
        ]);
    }

    public function actionNews() {

        if (!Yii::$app->getModule('news')->status) {
            throw new HttpException(404, 'Page not found');
        }

        $logs = Log::find()->where(['item_class' => News::className()])->orderBy(['time' => SORT_DESC, 'log_id' => SORT_DESC])->all();

        return $this->render('news', [
            'logs' => $logs
        ]);
    }

    public function actionVideoCategory() {
        if (!Yii::$app->getModule('video')->status || !Yii::$app->getModule('video')->categories) {
            throw new HttpException(404, 'Page not found');
        }

        $logs = Log::find()->where(['item_class' => VideoCategory::className()])->orderBy(['time' => SORT_DESC, 'log_id' => SORT_DESC])->all();

        return $this->render('video_category', [
            'logs' => $logs
        ]);
    }

    public function actionVideo() {

        if (!Yii::$app->getModule('video')->status) {
            throw new HttpException(404, 'Page not found');
        }

        $logs = Log::find()->where(['item_class' => Video::className()])->orderBy(['time' => SORT_DESC, 'log_id' => SORT_DESC])->all();

        return $this->render('video', [
            'logs' => $logs
        ]);
    }

    public function actionPhotoCategory() {
        if (!Yii::$app->getModule('photo')->status || !Yii::$app->getModule('photo')->categories) {
            throw new HttpException(404, 'Page not found');
        }

        $logs = Log::find()->where(['item_class' => PhotoCategory::className()])->orderBy(['time' => SORT_DESC, 'log_id' => SORT_DESC])->all();

        return $this->render('photo_category', [
            'logs' => $logs
        ]);
    }

    public function actionPhoto() {

        if (!Yii::$app->getModule('photo')->status) {
            throw new HttpException(404, 'Page not found');
        }

        $logs = Log::find()->where(['item_class' => Photo::className()])->orderBy(['time' => SORT_DESC, 'log_id' => SORT_DESC])->all();

        return $this->render('photo', [
            'logs' => $logs
        ]);
    }

    public function actionPages() {

        if (!Yii::$app->getModule('pages')->status) {
            throw new HttpException(404, 'Page not found');
        }

        $logs = Log::find()->where(['item_class' => Page::className()])->orderBy(['time' => SORT_DESC, 'log_id' => SORT_DESC])->all();

        return $this->render('pages', [
            'logs' => $logs
        ]);
    }




    public function actionClear($cl) {
        $logs = Log::findAll(['item_class' => $cl]);
        foreach ($logs as $l) {
            $l->delete();
        }
        Yii::$app->session->setFlash('log_flash', 'Логи очищены.');
        return $this->redirect(Yii::$app->request->referrer);
    }
}
