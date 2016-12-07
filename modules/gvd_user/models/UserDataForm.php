<?php

namespace app\modules\gvd_user\models;

use Yii;
use yii\base\Model;
use app\modules\gvd_user\models\User;

class UserDataForm extends Model
{
    public $email;
    public $phone;
    public $name;
    public $last_name;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'validateUserEmail'],
            [['name', 'last_name', 'phone'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'last_name' => 'Фамилия',
            'email' => 'Email',
            'phone' => 'Телефон'
        ];
    }

    public function validateUserEmail($attribute, $params)
    {
        $users = User::find()->all();
        foreach ($users as $u) {
            if ($this->email == $u->email && $u->id != Yii::$app->user->id) {
                $this->addError($attribute, 'Пользователь с таким email уже существует.');
                return false;
            }
        }
    }

    public function ChangeData() {
        if ($this->validate()) {
            $user = User::findOne(['id' => Yii::$app->user->id]);
            $user->email = $this->email;
            $user->name = $this->name;
            $user->last_name = $this->last_name;
            $user->phone = $this->phone;
            $user->generateAuthKey();
            return $user->save();
        }
        return false;
    }

    public function SetData() {
        $user = User::findOne(['id' => Yii::$app->user->id]);
        $this->email = $user->email;
        $this->name = $user->name;
        $this->last_name = $user->last_name;
        $this->phone = $user->phone;
    }
}
