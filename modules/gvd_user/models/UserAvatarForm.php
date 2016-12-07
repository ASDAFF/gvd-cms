<?php

namespace app\modules\gvd_user\models;

use Yii;
use yii\base\Model;

class UserAvatarForm extends Model
{
    public $avatar;

    public function rules()
    {
        return [
            [['avatar'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'checkExtensionByMimeType' => false],

        ];
    }

    public function attributeLabels()
    {
        return [

        ];
    }
}
