<?php

namespace app\modules\admin\controllers;

use app\modules\sliders\api\SliderAPI;
use app\modules\sliders\models\Slider;
use app\modules\sliders\models\SliderItem;
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
                        'actions' => ['index', 'view-slider'],
                        'allow' => true,
                        'roles' => ['viewSliders']
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['root']
                    ],
                    [
                        'actions' => ['create-item', 'update-item', 'item-to-left', 'item-to-right'],
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
                    'delete-item' => ['post'],
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

    public function actionViewSlider($id) {
        $slider = SliderAPI::slider($id);

        return $this->render('view_slider', [
            'slider' => $slider,
        ]);
    }

    public function actionCreateItem($id) {
        $slider = Slider::findOne(['slider_id' => $id]);

        $model = new SliderItem();
        $model->slider_id = $id;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('slide_flash', '<strong>Успешно добавлен слайд: </strong>"' . ($model->title ? $model->title : '<Без названия>') . '".');
                return $this->redirect(['/admin/sliders/view-slider', 'id' => $id]);
            }
        }

        return $this->render('create_item', [
            'model' => $model,
            'slider' => $slider
        ]);
    }

    public function actionUpdateItem($id) {
        $model = SliderItem::findOne(['slider_item_id' => $id]);
        $slider = $model->parent;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('slide_flash', '<strong>Успешно отредактирован слайд: </strong>"' . ($model->title ? $model->title : '<Без названия>') . '".');

                $log = new Log();
                $log->action = 'update';
                $log->user_ip = Yii::$app->request->userIP;
                $log->user_agent = Yii::$app->request->userAgent;
                $log->user_id = Yii::$app->user->id;
                $log->item_id = $model->primaryKey;
                $log->item_class = $model->className();
                $log->save();

                return $this->redirect(['/admin/sliders/view-slider', 'id' => $slider->primaryKey]);
            }
        }

        return $this->render('update_item', [
            'model' => $model,
            'slider' => $slider
        ]);
    }

    public function actionItemToLeft($id) {
        $slide = SliderItem::findOne(['slider_item_id' => $id]);
        $flag = true;
        $prev = $slide->parent->getSlideByNum($slide->order_num-1);
        if ($prev) {
            $prev->order_num = $slide->order_num;
            if (!$prev->save()) $flag = false;
        }
        $slide->order_num--;
        if (!$slide->save()) $flag = false;
        if ($flag) {
            Yii::$app->session->setFlash('slide_flash', '<strong>Слайдер успешно перемещен.</strong>');
        }
        else {
            Yii::$app->session->setFlash('slide_flash', '<strong>Не удалось переместить слайдер...</strong>');
        }
        return $this->redirect(['view-slider', 'id' => $slide->slider_id]);
    }

    public function actionItemToRight($id) {
        $slide = SliderItem::findOne(['slider_item_id' => $id]);
        $flag = true;
        $next = $slide->parent->getSlideByNum($slide->order_num+1);
        if ($next) {
            $next->order_num = $slide->order_num;
            if (!$next->save()) $flag = false;
        }
        $slide->order_num++;
        if (!$slide->save()) $flag = false;
        if ($flag) {
            Yii::$app->session->setFlash('slide_flash', '<strong>Слайдер успешно перемещен.</strong>');
        }
        else {
            Yii::$app->session->setFlash('slide_flash', '<strong>Не удалось переместить слайдер...</strong>');
        }
        return $this->redirect(['view-slider', 'id' => $slide->slider_id]);
    }

    public function actionDeleteItem() {
        $id = Yii::$app->request->post('id');
        $item = SliderItem::findOne(['slider_item_id' => $id]);
        $name = $item->title;

        $pos = $item->order_num;
        $slider = $item->parent;

        if ($item->delete()) {
            Yii::$app->session->setFlash('slide_flash', '<strong>Слайд успешно удален.</strong>');

            $pos++;
            $slide = $slider->getSlideByNum($pos);
            while ($slide) {
                $slide->order_num--;
                $slide->save();
                $pos++;
                $slide = $slider->getSlideByNum($pos);
            }

            return true;
        }
        Yii::$app->session->setFlash('slide_flash', '<strong>Не удалось удалить слайд...</strong>');
    }
}
