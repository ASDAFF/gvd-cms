<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Log;
use app\modules\admin\models\SearchItemForm;
use app\modules\news\News;
use app\modules\photo\models\Photo;
use app\modules\photo\models\PhotosForm;
use app\modules\video\models\Video;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use app\modules\photo\models\PhotoCategory;
use app\modules\photo\api\PhotoAPI;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;


class PhotoController extends Controller
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
                        'roles' => ['viewPhoto']
                    ],
                    [
                        'actions' => ['create-category', 'create-photo', 'category-image-upload', 'update-category', 'update-photo', 'photo-image-upload'],
                        'allow' => true,
                        'roles' => ['createUpdatePhoto']
                    ],
                    [
                        'actions' => ['delete-category', 'delete-photo', 'delete-few-photos', 'delete-all-photos-in-category'],
                        'allow' => true,
                        'roles' => ['deletePhoto']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete-photo' => ['post'],
                    'delete-few-photos' => ['post'],
                    'delete-category' => ['post'],
                    'delete-all-photos-in-category' => ['post']
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'category-image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => 'http://'.$_SERVER['HTTP_HOST'].'/img/photo/categories-text', // Directory URL address, where files are stored.
                'path' => Yii::getAlias('@webroot').'/img/photo/categories-text' // Or absolute path to directory where files are stored.
            ],
            'photo-image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => 'http://'.$_SERVER['HTTP_HOST'].'/img/photo/photos-text', // Directory URL address, where files are stored.
                'path' => Yii::getAlias('@webroot').'/img/photo/photos-text' // Or absolute path to directory where files are stored.
            ],
        ];
    }

    public function beforeAction($action)
    {

        if (!Yii::$app->getModule('photo')->status) {
            throw new HttpException(404, 'Page not found');
        }

        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        return parent :: beforeAction($action);
    }

    public function actionIndex()
    {
        if (!Yii::$app->getModule('photo')->categories) {
            return $this->redirect(['/admin/photo/view-category']);
        }

        $photos_form = new PhotosForm();
        if ($photos_form->load(Yii::$app->request->post())) {
            $c = $photos_form->upload();
            if ($c > 0) {
                Yii::$app->session->setFlash('photo_category_flash', '<strong>Успешно загружено фотографий: '. $c . '.</strong>');
            }
            else if ($c == 0) {
                Yii::$app->session->setFlash('photo_category_flash', '<strong>Загружено фотографий: '. $c . '.</strong>');
            }
            else {
                Yii::$app->session->setFlash('photo_category_flash', '<strong>Не удалось загрузить фотографии...</strong>');
            }
            return $this->refresh();
        }

        $cats = PhotoAPI::rootCats();

        return $this->render('index', [
            'cats' => $cats,
            'photos_form' => $photos_form
        ]);
    }

    public function actionCreateCategory($parent) {
        if (!Yii::$app->getModule('photo')->categories) {
            throw new HttpException(404, 'Page not found');
        }

        $model = new PhotoCategory();
        $model->parent_id = $parent;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('photo_category_flash', '<strong>Категория "'.$model->title.'" успешно добавлена.</strong>');

            return $this->redirect(['/admin/photo/index']);
        }

        $model->date = Yii::$app->formatter->asDatetime(time(), 'php: Y-m-d H:i');

        return $this->render('create_category', [
            'model' => $model
        ]);
    }

    public function actionUpdateCategory($id) {
        if (!Yii::$app->getModule('photo')->categories) {
            throw new HttpException(404, 'Page not found');
        }

        $model = PhotoCategory::findOne(['photo_category_id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('photo_category_flash', '<strong>Категория "'.$model->title.'" успешно отредактирована.</strong>');

            $log = new Log();
            $log->action = 'update';
            $log->user_ip = Yii::$app->request->userIP;
            $log->user_agent = Yii::$app->request->userAgent;
            $log->user_id = Yii::$app->user->id;
            $log->item_id = $model->primaryKey;
            $log->item_class = $model->className();
            $log->save();

            return $this->redirect(['/admin/photo/index']);
        }

        $model->seo_title = $model->seo('title');
        $model->seo_keywords = $model->seo('keywords');
        $model->seo_description = $model->seo('description');
        $model->seo_robots = $model->seo('robots');

        return $this->render('update_category', [
            'model' => $model
        ]);
    }

    public function actionCreatePhoto() {
        $parent = null;
        if (isset($_GET['parent'])) $parent = $_GET['parent'];

        $model = new Photo();
        $model->category_id = $parent;
        $model->time = Yii::$app->formatter->asDate(time(), 'php: Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('photo_flash'. '<strong>Фото успешно добавлено.</strong>');

            return $this->redirect(['/admin/photo/view-category', 'id' => $parent]);
        }

        return $this->render('create_photo', [
            'model' => $model,
        ]);
    }

    public function actionUpdatePhoto($id) {
        $model = Photo::findOne(['photo_id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('photo_flash'. '<strong>Фото успешно отредактировано.</strong>');

            $log = new Log();
            $log->action = 'update';
            $log->user_ip = Yii::$app->request->userIP;
            $log->user_agent = Yii::$app->request->userAgent;
            $log->user_id = Yii::$app->user->id;
            $log->item_id = $model->primaryKey;
            $log->item_class = $model->className();
            $log->save();

            return $this->redirect(['/admin/photo/view-category', 'id' => $model->category_id]);
        }

        return $this->render('update_photo', [
            'model' => $model,
        ]);
    }

    public function actionViewCategory() {
        $id = null;
        if (isset($_GET['id'])) $id = $_GET['id'];

        $search = new SearchItemForm();

        $cat = PhotoCategory::findOne(['photo_category_id' => $id]);

        $photos_form = new PhotosForm();
        if ($photos_form->load(Yii::$app->request->post())) {
            $c = $photos_form->upload();
            if ($c > 0) {
                Yii::$app->session->setFlash('photo_flash', '<strong>Успешно загружено фотографий: '. $c . '.</strong>');
            }
            else if ($c == 0) {
                Yii::$app->session->setFlash('photo_flash', '<strong>Загружено фотографий: '. $c . '.</strong>');
            }
            else {
                Yii::$app->session->setFlash('photo_flash', '<strong>Не удалось загрузить фотографии...</strong>');
            }
            return $this->refresh();
        }

        $photos = Photo::find()->where(['category_id' => $id]);

        if ($search->load(Yii::$app->request->get())) {
            if ($search->name) $photos = $photos->andWhere(['like', 'title', $search->name]);
            switch ($search->sort) {
                case 'dateAsc': {
                    $photos = $photos->orderBy(['time' => SORT_ASC]);
                    break;
                }
                case 'nameAsc': {
                    $photos = $photos->orderBy(['title' => SORT_ASC]);
                    break;
                }
                case 'nameDesc': {
                    $photos = $photos->orderBy(['title' => SORT_DESC]);
                    break;
                }
                default: {
                    $photos = $photos->orderBy(['time' => SORT_DESC]);
                    break;
                }
            }
        }
        else {
            $photos = $photos->orderBy(['time' => SORT_DESC]);
        }

        $pages = new Pagination(['totalCount' => $photos->count(), 'pageSize' => 9]);
        $pages->pageSizeParam = false;
        $photos = $photos->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('view_category', [
            'cat' => $cat,
            'photos' => $photos,
            'pages' => $pages,
            'search' => $search,
            'photos_form' => $photos_form
        ]);
    }

    public function actionDeletePhoto() {
        $id = Yii::$app->request->post('id');
        $item = Photo::findOne(['photo_id' => $id]);
        $name = $item->title;
        if ($item->delete()) {
            Yii::$app->session->setFlash('photo_flash', '<strong>Фото успешно удалено.</strong>');
            return true;
        }
        Yii::$app->session->setFlash('photo_flash', '<strong>Не удалось удалить фото...</strong>');
    }

    public function actionDeleteFewPhotos() {
        $ids = Yii::$app->request->post('ids');
        $c = 0;
        foreach ($ids as $id) {
            $item = Photo::findOne(['photo_id' => $id]);
            $name = $item->title;
            if ($item->delete()) {
                $c++;
            }
        }
        if ($c > 0)
            Yii::$app->session->setFlash('photo_flash', '<strong>Успешно удалено '.$c.' фото.</strong>');
        else
            Yii::$app->session->setFlash('photo_flash', '<strong>Не удалось удалить фотографии...</strong>');
        return true;
    }

    public function actionDeleteCategory() {
        $id = Yii::$app->request->post('id');
        $item = PhotoCategory::findOne(['photo_category_id' => $id]);
        $name = $item->title;
        if ($item->delete()) {
            Yii::$app->session->setFlash('photo_category_flash', '<strong>Успешно удалена категория:</strong> "'.$name.'".');
            return true;
        }
        Yii::$app->session->setFlash('photo_category_flash', '<strong>Не удалось удалить категорию:</strong> "'.$name.'"...');
    }

    public function actionDeleteAllPhotosInCategory() {
        $id = Yii::$app->request->post('id');
        $item = PhotoCategory::findOne(['photo_category_id' => $id]);
        $k = $item->count;
        $c = $k;
        foreach ($item->photos as $p) {
            if ($p->delete()) $c--;
        }
        if ($c > 0) {
            Yii::$app->session->setFlash('photo_category_flash', '<strong>Не удалось удалить '.$c.' фото в '.$k.' категории "'.$item->title.'".</strong>');
        }
        else {
            Yii::$app->session->setFlash('photo_category_flash', '<strong>Успешно удалены все '.$k.' фото в категории "'.$item->title.'".</strong>');
        }
        return true;
    }
}
