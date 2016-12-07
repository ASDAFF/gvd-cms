<?php

namespace app\modules\admin\controllers;

use app\components\models\Seo;
use app\modules\admin\models\SearchItemForm;
use app\modules\news\models\News;
use app\modules\news\models\NewsImage;
use app\modules\news\models\NewsUpdateForm;
use yii\data\Pagination;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\news\models\NewsForm;
use app\modules\news\models\NewsImageForm;
use app\modules\admin\models\Log;
use yii\web\HttpException;


class NewsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // Просмотр новостей
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['viewNews']
                    ],
                    // Добавление и редактирование новостей
                    [
                        'actions' => ['create', 'update', 'upload-photo', 'get-slider-photos', 'delete-photo', 'get-img', 'publish', 'popular'],
                        'allow' => true,
                        'roles' => ['createUpdateNews']
                    ],
                    // Удаление новостей
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['deleteNews']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'change-role' => ['post'],
                    'change-status' => ['post'],
                    'delete' => ['post'],
                    'delete-photo' => ['post'],
                    'upload-photo' => ['post'],
                    'publish' => ['post'],
                    'popular' => ['post']
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!Yii::$app->getModule('news')->status) {
            throw new HttpException(404, 'Page not found');
        }
        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        return parent :: beforeAction($action);
    }

    public function actions()
    {
        return [
            'upload-photo' => [
                'class' => 'app\modules\news\actions\NewsImageUploadAction',
            ],
        ];
    }

    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $news = News::findAll(['title' => '', 'published' => null]);
        foreach ($news as $n) {
            $n->delete();
        }

        $search = new SearchItemForm();

        $all_news = News::find()->where(['published' => 0])->orWhere(['published' => 1])->orderBy(['date' => SORT_DESC])->all();
        $news = News::find()->where(['published' => 0])->orWhere(['published' => 1]);

        if ($search->load(Yii::$app->request->get())) {
            if ($search->name) $news = $news->andWhere(['like', 'title', $search->name]);
            switch ($search->sort) {
                case 'dateAsc': {
                    $news = $news->orderBy(['date' => SORT_ASC]);
                    break;
                }
                case 'nameAsc': {
                    $news = $news->orderBy(['title' => SORT_ASC]);
                    break;
                }
                case 'nameDesc': {
                    $news = $news->orderBy(['title' => SORT_DESC]);
                    break;
                }
                default: {
                    $news = $news->orderBy(['date' => SORT_DESC]);
                    break;
                }
            }
        }
        else {
            $news = $news->orderBy(['date' => SORT_DESC]);
        }

        $pages = new Pagination(['totalCount' => $news->count(), 'pageSize' => 9]);
        $pages->pageSizeParam = false;
        $news = $news->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index', [
            'news' => $news,
            'all_news' => $all_news,
            'pages' => $pages,
            'search' => $search
        ]);
    }

    public function actionCreate() {
        $slider_form = new NewsImageForm();
        $cover_form = new NewsImageForm();

        $model = new NewsForm();

        if ($model->load(Yii::$app->request->post()) && $model->addNews()) {
            Yii::$app->session->setFlash('news_flash', '<strong>Успешно добавлена новость:</strong> "'.$model->title.'".');

            return $this->redirect(['/admin/news/index']);
        }

        $item = new News();
        $item->save();

        $model->id = $item->news_id;
        $model->date = Yii::$app->formatter->asDatetime(time(), 'php: Y-m-d H:i');

        return $this->render('create', [
            'model' => $model,
            'slider_form' => $slider_form,
            'cover_form' => $cover_form,
            'item' => $item,
            'indexImages' => Yii::$app->getModule('news')->indexImages
        ]);
    }

    public function actionUpdate($id) {
        $slider_form = new NewsImageForm();
        $cover_form = new NewsImageForm();

        $item = News::findOne(['news_id' => $id]);
        $model = new NewsUpdateForm();

        if ($model->load(Yii::$app->request->post()) && $model->updateNews()) {
            Yii::$app->session->setFlash('news_flash', '<strong>Успешно изменена новость:</strong> "'.$model->title.'".');

            $log = new Log();
            $log->action = 'update';
            $log->user_ip = Yii::$app->request->userIP;
            $log->user_agent = Yii::$app->request->userAgent;
            $log->user_id = Yii::$app->user->id;
            $log->item_id = $model->id;
            $log->item_class = News::className();
            $log->save();

            return $this->redirect(['/admin/news/index']);
        }

        $model->id = $item->news_id;
        $model->title = $item->title;
        $model->description = $item->description;
        $model->epigraph = $item->epigraph;
        $model->date = $item->date;
        $model->text = $item->text;
        $model->slider = $item->slider;
        $model->published = $item->published;
        $model->popular = $item->popular;

        $model->seo_title = $item->seo('title');
        $model->seo_keywords = $item->seo('keywords');
        $model->seo_description = $item->seo('description');
        $model->seo_robots = $item->seo('robots');

        return $this->render('update', [
            'model' => $model,
            'slider_form' => $slider_form,
            'cover_form' => $cover_form,
            'item' => $item,
            'indexImages' => Yii::$app->getModule('news')->indexImages
        ]);
    }

    public function actionDelete() {
        $id = Yii::$app->request->post('id');
        $item = News::findOne(['news_id' => $id]);
        $name = $item->title;
        if ($item->delete()) {
            Yii::$app->session->setFlash('news_flash', '<strong>Успешно удалена новость:</strong> "'.$name.'".');
            return true;
        }
        Yii::$app->session->setFlash('news_flash', '<strong>Не удалось удалить новость:</strong> "'.$name.'"...');
    }

    public function actionPublish() {
        $id = Yii::$app->request->post('id');
        $item = News::findOne(['news_id' => $id]);
        if ($item->published) {
            $item->published = 0;
            Yii::$app->session->setFlash('news_flash', '<strong>Из публикации убрана новость:</strong> "'.$item->title.'".');
        }
        else {
            $item->published = 1;
            Yii::$app->session->setFlash('news_flash', '<strong>Успешно опубликована новость:</strong> "'.$item->title.'".');
        }
        if ($item->save()){
            $log = new Log();
            $log->action = 'update';
            $log->user_ip = Yii::$app->request->userIP;
            $log->user_agent = Yii::$app->request->userAgent;
            $log->user_id = Yii::$app->user->id;
            $log->item_id = $id;
            $log->item_class = News::className();
            $log->save();
        }
        return true;
    }

    public function actionPopular() {
        $id = Yii::$app->request->post('id');
        $item = News::findOne(['news_id' => $id]);
        if ($item->popular) {
            $item->popular = 0;
            Yii::$app->session->setFlash('news_flash', '<strong>Из популярных убрана новость:</strong> "'.$item->title.'".');
        }
        else {
            $item->popular = 1;
            Yii::$app->session->setFlash('news_flash', '<strong>Успешно сделана популярной новость:</strong> "'.$item->title.'".');
        }
        if ($item->save()){
            $log = new Log();
            $log->action = 'update';
            $log->user_ip = Yii::$app->request->userIP;
            $log->user_agent = Yii::$app->request->userAgent;
            $log->user_id = Yii::$app->user->id;
            $log->item_id = $id;
            $log->item_class = News::className();
            $log->save();
        }
        return true;
    }





    public function actionGetSliderPhotos() {
        $id = Yii::$app->request->post('id');

        $photos = NewsImage::findAll(['news_id' => $id, 'label' => 'slider']);
        $res = '<br>';
        foreach ($photos as $p) {
            $res .= '<div class="member">';
            $res .= '<img src="'.$p->image.'" alt="">';
            $res .= '<a class="memmbername delete_slider_photo" href="#" data-id="'.$p->news_image_id.'">удалить</a>';
            $res .= '</div>';
        }
        return $res;
    }
    
    public function actionDeletePhoto() {
        $id = Yii::$app->request->post('id');
        $photo = NewsImage::findOne(['news_image_id' => $id]);
        return $photo->delete();
    }

    public function actionGetImg() {
        $id = Yii::$app->request->post('id');
        $label = Yii::$app->request->post('label');

        $photo = NewsImage::findOne(['news_id' => $id, 'label' => $label]);
        $res = [];
        $res['id'] = $photo->news_image_id;
        $res['image'] = $photo->image;
        return json_encode($res);
    }

}
