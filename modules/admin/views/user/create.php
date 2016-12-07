<?php

$this->title = 'Добавление пользователя | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Добавление пользователя</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li><a href="<?= Url::to(['/admin/user']) ?>">Пользователи</a></li>
    <li class="active">Добавление пользователя</li>
</ol>

<div class="row">
    <div class="col-md-5">
        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= $form->field($model, 'email')->textInput(['placeholder' => 'E-mail'])->label(false) ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Пароль'])->label(false) ?>
            <?= $form->field($model, 'phone')->textInput(['placeholder' => 'Номер телефона'])->label(false) ?>
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Имя'])->label(false) ?>
            <?= $form->field($model, 'last_name')->textInput(['placeholder' => 'Фамилия'])->label(false) ?>
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>