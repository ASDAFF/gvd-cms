<?php

$this->title = 'Добавление разрешения | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Добавление разрешения</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li><a href="<?= Url::to(['/admin/user']) ?>">Пользователи</a></li>
    <li class="active">Добавление разрешения</li>
</ol>

<div class="row">
    <div class="col-md-5">
        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Название'])->label(false) ?>
            <?= $form->field($model, 'description')->textInput(['placeholder' => 'Описание'])->label(false) ?>
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>