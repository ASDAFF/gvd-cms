<?php

namespace app\modules\news\actions;

use Yii;
use yii\base\Action;
use app\modules\news\models\NewsImage;
use app\modules\news\models\NewsImageForm;
use yii\web\UploadedFile;

class NewsImageUploadAction extends Action
{

    public $path = '/img/news/';

    public function run()
    {
        $model = new NewsImageForm();
        $response = true;
        if ($model->load(Yii::$app->request->post())) {
            $model->images = UploadedFile::getInstances($model, 'images');
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->validate()) {
                if ($model->images) {
                    foreach ($model->images as $photo) {
                        $name = \rand(100000000, 999999999);
                        $name = $this->checkUniqName($name);
                        $photo->saveAs(dirname(dirname(dirname(__DIR__))) . '/web' . $this->path . $name . '.' . $photo->extension);
                        $item = new NewsImage();
                        $item->image = $this->path . $name . '.' . $photo->extension;
                        $item->news_id = $model->news_id;
                        $item->label = $model->label;
                        if (!$item->save()) $response = false;
                    }
                }
                if ($model->image) {
                    $photo = $model->image;
                    $name = \rand(100000000, 999999999);
                    $name = $this->checkUniqName($name);
                    $photo->saveAs(dirname(dirname(dirname(__DIR__))) . '/web' . $this->path . $name . '.' . $photo->extension);
                    $item = new NewsImage();
                    $item->image = $this->path . $name . '.' . $photo->extension;
                    $item->news_id = $model->news_id;
                    $item->label = $model->label;
                    if (!$item->save()) $response = false;
                }
            }
            else {
                $response = false;
            }
        }
        else {
            $response = false;
        }
        return $response;
    }

    private function checkUniqName($name) {
        $base = NewsImage::find()->all();
        foreach ($base as $p) {
            if (substr($p->image, 0, strrpos($p->image, '.')) == $name) {
                $new_name = \rand(100000000, 999999999);
                $this->checkUniqName($new_name);
            }
        }
        return $name;
    }
}