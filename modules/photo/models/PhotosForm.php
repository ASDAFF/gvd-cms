<?php

namespace app\modules\photo\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

class PhotosForm extends Model
{
    public $images;
    public $cat;

    public function rules()
    {
        return [
            [['images'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'checkExtensionByMimeType' => false, 'maxFiles' => 50],
            [['cat'], 'integer'],

            [['cat'], 'default', 'value' => null]
        ];
    }

    public function upload() {
        $this->images = UploadedFile::getInstances($this, 'images');
        $c = 0;
        if ($this->validate()) {
            if ($this->images) {
                foreach ($this->images as $key => $photo) {
                    $name = \rand(100000000, 999999999);
                    $name = $this->checkUniqName($name);
                    $photo->saveAs(Yii::getAlias('@webroot') . '/img/photo/' . $name . '.' . $photo->extension);
                    $item = new Photo();
                    $item->category_id = $this->cat;
                    $item->time = \time();
                    $item->photo = '/img/photo/' . $name . '.' . $photo->extension;
                    if ($item->save()) $c++;
                }
                return $c;
            }
            return $c;
        }
        return null;
    }

    private function checkUniqName($name) {
        $base = Photo::find()->all();
        foreach ($base as $p) {
            if (substr($p->photo, strrpos($p->photo, '/')+1, strrpos($p->photo, '.')) == $name) {
                $new_name = \rand(100000000, 999999999);
                $this->checkUniqName($new_name);
            }
        }
        return $name;
    }

}

?>