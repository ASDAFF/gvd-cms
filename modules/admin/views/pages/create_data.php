<?php

$this->title = 'Добавление поля | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Добавление поля страницы "<?= $page->title ?>"</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li><a href="<?= Url::to(['/admin/pages/index']) ?>">Страницы</a></li>
    <li><a href="<?= Url::to(['/admin/pages/data', 'id' => $page->primaryKey]) ?>">Поля страницы "<?= $page->title ?>"</a></li>
    <li class="active">Добавление поля</li>
</ol>

<div class="row">
    <div class="col-md-12">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'key')->textInput() ?>

        <?= $form->field($model, 'title')->textInput() ?>

        <?= $form->field($model, 'type')->dropDownList(['string' => 'Строка', 'text' => 'Текст']) ?>

        <?= $form->field($model, 'page_id')->hiddenInput(['value' => $page->primaryKey])->label(false) ?>

        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>