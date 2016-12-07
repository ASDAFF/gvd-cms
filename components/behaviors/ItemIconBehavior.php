<?php

namespace app\components\behaviors;

use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\web\UploadedFile;
use Yii;

class ItemIconBehavior extends Behavior
{
    public $icon_img;

    public $path;
    public $attr_name;

    public function rules()
    {
        return [
            [['icon_img'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'checkExtensionByMimeType' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'icon_img' => 'Иконка'
        ];
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete'
        ];
    }

    public function beforeValidate()
    {
        $this->owner->icon_img = UploadedFile::getInstance($this->owner, 'icon_img');
    }

    public function beforeSave($insert)
    {
        if ($this->owner->icon_img) {
            $photo = $this->owner->icon_img;
            $name = \rand(100000000, 999999999);
            $name = $this->checkUniqName($name);
            $photo->saveAs(Yii::getAlias('@webroot') . $this->path . $name . '.' . $photo->extension);
            if ($this->owner->{$this->attr_name}) @unlink($this->owner->{$this->attr_name});
            $this->owner->{$this->attr_name} = $this->path . $name . '.' . $photo->extension;
        }
    }

    public function beforeDelete()
    {
        @unlink(Yii::getAlias('@webroot') . $this->owner->{$this->attr_name});
    }

    private function checkUniqName($name) {
        $cl = $this->owner->className();
        $base = $cl::find()->all();
        foreach ($base as $p) {
            if (substr($p->{$this->attr_name}, 0, strrpos($p->{$this->attr_name}, '.')) == $name) {
                $new_name = \rand(100000000, 999999999);
                $this->checkUniqName($new_name);
            }
        }
        return $name;
    }

}

?>