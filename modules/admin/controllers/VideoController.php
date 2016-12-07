<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Log;
use app\modules\admin\models\SearchItemForm;
use app\modules\news\News;
use app\modules\video\models\Video;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use app\modules\video\models\VideoCategory;
use app\modules\video\api\VideoAPI;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

/**
 * Default controller for the `admin` module
 */
class VideoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view-category'],
                        'allow' => true,
                        'roles' => ['viewVideo']
                    ],
                    [
                        'actions' => ['create-category', 'create-video', 'category-image-upload', 'update-category', 'update-video', 'video-image-upload'],
                        'allow' => true,
                        'roles' => ['createUpdateVideo']
                    ],
                    [
                        'actions' => ['delete-category', 'delete-video'],
                        'allow' => true,
                        'roles' => ['deleteVideo']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete-video' => ['post'],
                    'delete-category' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'category-image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => 'http://'.$_SERVER['HTTP_HOST'].'/img/video/categories-text', // Directory URL address, where files are stored.
                'path' => Yii::getAlias('@webroot').'/img/video/categories-text' // Or absolute path to directory where files are stored.
            ],
            'video-image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => 'http://'.$_SERVER['HTTP_HOST'].'/img/video/videos-text', // Directory URL address, where files are stored.
                'path' => Yii::getAlias('@webroot').'/img/video/videos-text' // Or absolute path to directory where files are stored.
            ],
        ];
    }

    public function beforeAction($action)
    {

        if (!Yii::$app->getModule('video')->status) {
            throw new HttpException(404, 'Page not found');
        }

        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        return parent :: beforeAction($action);
    }

    public function actionIndex()
    {
        if (!Yii::$app->getModule('video')->categories) {
            return $this->redirect(['/admin/video/view-category']);
        }
        $cats = VideoAPI::rootCats();

        return $this->render('index', [
            'cats' => $cats
        ]);
    }

    public function actionCreateCategory($parent) {
        if (!Yii::$app->getModule('video')->categories) {
            throw new HttpException(404, 'Page not found');
        }

        $model = new VideoCategory();
        $model->parent_id = $parent;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('video_category_flash', '<strong>Категория "'.$model->title.'" успешно добавлена.</strong>');

            return $this->redirect(['/admin/video/index']);
        }

        $model->date = Yii::$app->formatter->asDatetime(time(), 'php: Y-m-d H:i');

        return $this->render('create_category', [
            'model' => $model
        ]);
    }

    public function actionUpdateCategory($id) {
        if (!Yii::$app->getModule('video')->categories) {
            throw new HttpException(404, 'Page not found');
        }

        $model = VideoCategory::findOne(['video_category_id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('video_category_flash', '<strong>Категория "'.$model->title.'" успешно отредактирована.</strong>');

            return $this->redirect(['/admin/video/index']);
        }

        $model->seo_title = $model->seo('title');
        $model->seo_keywords = $model->seo('keywords');
        $model->seo_description = $model->seo('description');
        $model->seo_robots = $model->seo('robots');

        return $this->render('update_category', [
            'model' => $model
        ]);
    }

    public function actionCreateVideo() {
        $parent = null;
        if (isset($_GET['parent'])) $parent = $_GET['parent'];

        $model = new Video();
        $model->category_id = $parent;
        $model->time = Yii::$app->formatter->asDate(time(), 'php: Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('video_flash'. '<strong>Видео успешно добавлено.</strong>');

            return $this->redirect(['/admin/video/view-category', 'id' => $parent]);
        }

        return $this->render('create_video', [
            'model' => $model,
        ]);
    }

    public function actionUpdateVideo($id) {
        $model = Video::findOne(['video_id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('video_flash'. '<strong>Видео успешно отредактировано.</strong>');

            return $this->redirect(['/admin/video/view-category', 'id' => $model->category_id]);
        }

        return $this->render('update_video', [
            'model' => $model,
        ]);
    }

    public function actionViewCategory() {
        $id = null;
        if (isset($_GET['id'])) $id = $_GET['id'];

        $search = new SearchItemForm();

        $cat = VideoCategory::findOne(['video_category_id' => $id]);
        $all_videos = Video::find()->where(['category_id' => $id])->orderBy(['time' => SORT_DESC])->all();

        $videos = Video::find()->where(['category_id' => $id]);

        if ($search->load(Yii::$app->request->get())) {
            if ($search->name) $videos = $videos->andWhere(['like', 'title', $search->name]);
            switch ($search->sort) {
                case 'dateAsc': {
                    $videos = $videos->orderBy(['time' => SORT_ASC]);
                    break;
                }
                case 'nameAsc': {
                    $videos = $videos->orderBy(['title' => SORT_ASC]);
                    break;
                }
                case 'nameDesc': {
                    $videos = $videos->orderBy(['title' => SORT_DESC]);
                    break;
                }
                default: {
                    $videos = $videos->orderBy(['time' => SORT_DESC]);
                    break;
                }
            }
        }
        else {
            $videos = $videos->orderBy(['time' => SORT_DESC]);
        }

        $pages = new Pagination(['totalCount' => $videos->count(), 'pageSize' => 9]);
        $pages->pageSizeParam = false;
        $videos = $videos->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('view_category', [
            'cat' => $cat,
            'videos' => $videos,
            'pages' => $pages,
            'all_videos' => $all_videos,
            'search' => $search
        ]);
    }

    public function actionDeleteVideo() {
        $id = Yii::$app->request->post('id');
        $item = Video::findOne(['video_id' => $id]);
        $name = $item->title;
        if ($item->delete()) {
            Yii::$app->session->setFlash('video_flash', '<strong>Успешно удалено видео:</strong> "'.($name ? $name : '<Без названия>').'".');
            return true;
        }
        Yii::$app->session->setFlash('video_flash', '<strong>Не удалось удалить видео:</strong> "'.($name ? $name : '<Без названия>').'"...');
    }

    public function actionDeleteCategory() {
        $id = Yii::$app->request->post('id');
        $item = VideoCategory::findOne(['video_category_id' => $id]);
        $name = $item->title;
        if ($item->delete()) {
            Yii::$app->session->setFlash('video_category_flash', '<strong>Успешно удалена категория:</strong> "'.$name.'".');
            return true;
        }
        Yii::$app->session->setFlash('video_category_flash', '<strong>Не удалось удалить категорию:</strong> "'.$name.'"...');
    }
}
