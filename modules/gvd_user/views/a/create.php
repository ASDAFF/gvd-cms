<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Добавление пользователя';

?>

<?= $this->render('_menu') ?>

<div class="row">
        <div class="col-xs-12 col-md-5">

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= $form->field($model, 'email')->textInput() ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'phone')->textInput() ?>
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'last_name')->textInput() ?>
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        </div>

</div>