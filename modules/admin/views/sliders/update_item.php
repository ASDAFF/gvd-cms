<?php

$this->title = 'Редактирование слайда | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->registerCss("
    .preview_img {
        width: 100%;
        position: relative;
        overflow: hidden;
        padding: 0!important;
    }
    .preview_img:before {
        content: '';
        display: block;
        position: relative;
        padding-top: 40%;
    }
    .preview_img div {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%!important;
        border: 0!important;
        background: center no-repeat;
        background-size: cover;
    }
    .icon_preview:before {
        padding-top: 40%;
    }
");

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Редактирование слайда номер <?= $model->order_num ?></h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li>Элементы сайта</li>
    <li><a href="<?= Url::to(['/admin/sliders/index']) ?>">Слайдеры</a></li>
    <li><?= $slider->title ?></li>
    <li class="active">Редактирование слайда номер <?= $model->order_num ?></li>
</ol>

<div class="row">
    <div class="col-md-12">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'title')->textInput() ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="preview_img well memberblock" data-input="slideritem-image">
                            <div<?= $model->photo ? ' style="background-image: url('.$model->photo.');"' : null ?>><a class="memmbername delete_img_preview" href="#" data-input="slideritem-image">удалить</a></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'image')->fileInput(['class' => 'img_preview']) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <?php if (Yii::$app->getModule('sliders')->hover_photo) { ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="preview_img icon_preview well memberblock" data-input="slideritem-icon_img">
                                <div<?= $model->photo_hover ? ' style="background-image: url('.$model->photo_hover.');"' : null ?>><a class="memmbername delete_img_preview" href="#" data-input="slideritem-icon_img">удалить</a></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'icon_img')->fileInput(['class' => 'img_preview']) ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <?= $form->field($model, 'description')->textInput() ?>

        <?= $form->field($model, 'link')->textInput() ?>

        <?= $form->field($model, 'slider_id')->hiddenInput()->label(false) ?>

        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>