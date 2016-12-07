<?php
namespace yii\easyii\modules\gvd_user\models;

use Yii;
use yii\base\Model;
use yii\easyii\modules\gvd_user\GvdUser;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'yii\easyii\modules\gvd_user\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Пользователя с таким email не существует.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
        }
        
        if (!$user->save()) {
            return false;
        }

        return mail($this->email, 'Восстановление пароля | ' . GvdUser::$this_domain, 'Для восстановления пароля на сайте '.GvdUser::$this_domain.' перейдите по ссылке: http://'.GvdUser::$this_domain.'/reset-password?token='.$user->password_reset_token);
    }
}
