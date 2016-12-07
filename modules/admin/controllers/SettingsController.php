<?php

namespace app\modules\admin\controllers;

use app\modules\news\News;
use app\modules\pages\Pages;
use app\modules\photo\Photo;
use app\modules\sliders\Sliders;
use app\modules\video\Video;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use app\components\models\ModuleSettings;

/**
 * Default controller for the `admin` module
 */
class SettingsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'change'],
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

    public function actionIndex()
    {
        Yii::$app->user->setReturnUrl(['/admin']);

        $news = ModuleSettings::findAll(['module_class' => News::className()]);
        $videos = ModuleSettings::findAll(['module_class' => Video::className()]);
        $photos = ModuleSettings::findAll(['module_class' => Photo::className()]);
        $pages = ModuleSettings::findAll(['module_class' => Pages::className()]);
        $sliders = ModuleSettings::findAll(['module_class' => Sliders::className()]);

        return $this->render('index', [
            'news' => $news,
            'videos' => $videos,
            'photos' => $photos,
            'pages' => $pages,
            'sliders' => $sliders
        ]);
    }

    public function actionChange() {
        $id = Yii::$app->request->post('id');
        $val = Yii::$app->request->post('val');

        $el = ModuleSettings::findOne(['module_settings_id' => $id]);
        $el->value = $val;
        $el->save();
        Yii::$app->cache->delete('module_settings_'.$el->module_class);
    }
}
