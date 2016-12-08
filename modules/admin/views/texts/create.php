<?php

$this->title = 'Добавление текстовой информации | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Добавление текстовой информации</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li>Элементы сайта</li>
    <li><a href="<?= Url::to(['/admin/texts/index']) ?>">Текстовая информация</a></li>
    <li class="active">Добавление текстовой информации</li>
</ol>

<div class="row">
    <div class="col-md-12">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'text_info_key')->textInput() ?>
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'value')->textInput() ?>

        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>