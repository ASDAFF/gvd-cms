<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<h1><?= Html::encode($this->title) ?></h1>

    <p>Введите новый пароль:</p>

    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

    <?= $form->field($model, 'password')->passwordInput(['autofocus' => true])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>