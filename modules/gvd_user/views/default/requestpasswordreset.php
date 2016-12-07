<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>


    <h1>Восстановление пароля</h1>

    <p>Введите ваш email. На него будет отправлена ссылка для восстановления пароля.</p>

    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <p>
        <?php
        if (Yii::$app->session->hasFlash('success')) echo Yii::$app->session->getFlash('success');
        else if (Yii::$app->session->hasFlash('error')) echo Yii::$app->session->getFlash('error');
        ?>
    </p>
