<?php

namespace app\modules\gvd_user\models;

use Yii;
use yii\base\Model;
use app\modules\gvd_user\models\User;

class UserForm extends Model
{
    public $email;
    public $password;
    public $phone;
    public $name;
    public $last_name;
    //public $avatar;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\modules\gvd_user\models\User', 'message' => 'Пользователь с таким email уже существует.'],
            [['password', 'name', 'last_name', 'phone'], 'string'],
            //['avatar', 'file', 'extensions' => 'jpeg, gif, png', 'on' => ['insert', 'update']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'last_name' => 'Фамилия',
            'email' => 'Email',
            'phone' => 'Телефон',
            'password' => 'Пароль'
        ];
    }

    public function Adduser() {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->name = $this->name;
        $user->last_name = $this->last_name;
        $user->phone = $this->phone;
        $user->avatar = '/img/admin/default_profile.png';
        $user->generateAuthKey();
        if ($user->save())
            return $user->id;
        return false;
    }
}
