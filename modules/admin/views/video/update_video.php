<?php

$this->title = 'Редактирование видео в видеогалерее | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use vova07\imperavi\Widget;

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
        padding-top: 55%;
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
");

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Редактирование видео в видеогалерее</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li><a href="<?= Url::to(['/admin/video/index']) ?>">Видеогалерея</a></li>
    <li class="active">Редактирование видео</li>
</ol>

<div class="row">
    <div class="col-md-12">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'title')->textInput() ?>

        <?= $form->field($model, 'category_id')->hiddenInput()->label(false) ?>

        <div class="row">
            <div class="col-md-3">
                <div class="preview_img well memberblock" data-input="video-image">
                    <div<?= $model->cover ? ' style="background-image: url('.$model->cover.');"' : null ?>><a class="memmbername delete_img_preview" href="#" data-input="video-image">удалить</a></div>
                </div>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'image')->fileInput(['class' => 'img_preview']) ?>
            </div>
        </div>

        <?= $form->field($model, 'url')->textInput() ?>

        <?= Yii::$app->getModule('video')->video_text ? $form->field($model, 'text')->widget(Widget::className(), [
            'settings' => [
                'lang' => 'ru',
                'minHeight' => 500,
                'imageUpload' => Url::to(['/admin/video/video-image-upload']),
                'plugins' => [
                    'table',
                    'fontcolor',
                    'video',
                    'fullscreen',
                    'imagemanager'
                ]
            ]
        ]) : null ?>

        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>