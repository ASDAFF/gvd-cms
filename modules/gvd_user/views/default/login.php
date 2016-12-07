<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use himiklab\yii2\recaptcha\ReCaptcha;

?>

<div class="row">
    <div class="col-md-6">
        <h2>Вход</h2>
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'email'])->label(false) ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'пароль'])->label(false) ?>
            <a href="#" class="checkbox_link checked">запомнить</a>
            <?= Html::submitButton('Войти', ['name' => 'login-button']) ?>
            <?= $form->field($model, 'rememberMe')->hiddenInput()->label(false) ?>
        <?php ActiveForm::end(); ?>
            <a href="<?= \yii\helpers\Url::to(['site/requestpasswordreset']) ?>" id="remind_pass">Восстановить пароль</a>
            <h3>Или войдите через социальную сеть</h3>
            <div id="uLogin" data-ulogin="display=panel;fields=first_name,last_name,photo_big;providers=facebook,twitter,googleplus,vkontakte,yandex,odnoklassniki,mailru;hidden=;redirect_uri=/site/loginsoc"></div>
    </div>
    <div class="col-md-6">
        <h2>Регистрация</h2>
        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model_reg, 'email')->textInput(['placeholder' => 'email'])->label(false) ?>
            <?= $form->field($model_reg, 'password')->passwordInput(['placeholder' => 'пароль'])->label(false) ?>
            <?= $form->field($model_reg, 'password_rep')->passwordInput(['placeholder' => 'подтверждение пароля'])->label(false) ?>
            <?= ReCaptcha::widget([
                'name' => 'reCaptcha',
                'siteKey' => '6Lc1fSATAAAAAKwCdP8Us7xKYHb1q34V8R4PpG1Y',
                'widgetOptions' => ['class' => 'captcha']
            ]) ?>
            <?= Html::submitButton('Зарегистрироваться', ['name' => 'signup-button']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script src="//ulogin.ru/js/ulogin.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
