<?php
namespace yii\easyii\modules\gvd_user\models;

use yii\base\Model;
use Yii;
use himiklab\yii2\recaptcha\ReCaptchaValidator;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $password_rep;
    public $reCaptcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => 'yii\easyii\modules\gvd_user\models\User', 'message' => 'Пользователь с таким email уже существует.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password_rep', 'required'],
            ['password_rep', 'string', 'min' => 6],
            ['password', 'validatePassword'],

            [['reCaptcha'], ReCaptchaValidator::className()],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->password != $this->password_rep) {
                $this->addError($attribute, 'Пароли не совпадают.');
            }
        }
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->avatar = '/img/avatars/default.jpg';
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
            'password_rep' => 'Подтверждение пароля',
            'reCaptcha' => 'Гугл капча'
        ];
    }
}
