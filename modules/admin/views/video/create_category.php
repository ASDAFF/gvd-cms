<?php

$this->title = 'Добавление категории в видеогалерее | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\datetime\DateTimePicker;
use vova07\imperavi\Widget;
use app\components\widgets\SeoForm;

$this->registerCss("
    .datetimepicker th.switch {
        color: black;
    }
    .datetimepicker thead tr:first-child th, .datetimepicker tfoot tr:first-child th {
        color: black;
    }
    .datetimepicker td, .datetimepicker th {
        color: black;
    }
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
    .icon_preview:before {
        padding-top: 100%;
    }
");

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Добавление категории в видеогалерее</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li><a href="<?= Url::to(['/admin/video/index']) ?>">Видеогалерея</a></li>
    <li class="active">Добавление категории</li>
</ol>

<div class="row">
    <div class="col-md-12">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'title')->textInput() ?>

                <?= $form->field($model, 'parent_id')->hiddenInput()->label(false) ?>

                <?php if (Yii::$app->getModule('video')->category_date) { ?>

                    <?= $form->field($model, 'date')->widget(DateTimePicker::classname(), [
                        'options' => ['placeholder' => 'Выберите дату'],
                        'language' => 'ru',
                        'layout' => '{picker}{input}{remove}',
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]) ?>

                <?php } else { ?>
                    <?= $form->field($model, 'date')->hiddenInput()->label(false) ?>
                <?php } ?>

                <?= Yii::$app->getModule('video')->category_description ? $form->field($model, 'description')->textarea() : null ?>
            </div>
            <div class="col-md-6">
                <?php if (Yii::$app->getModule('video')->category_cover) { ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="preview_img well memberblock" data-input="videocategory-image">
                                <div><a class="memmbername delete_img_preview" href="#" data-input="videocategory-image">удалить</a></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'image')->fileInput(['class' => 'img_preview']) ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (Yii::$app->getModule('video')->category_icon) { ?>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="preview_img icon_preview well memberblock" data-input="videocategory-icon_img">
                                <div><a class="memmbername delete_img_preview" href="#" data-input="videocategory-icon_img">удалить</a></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'icon_img')->fileInput(['class' => 'img_preview']) ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <?= Yii::$app->getModule('video')->category_text ? $form->field($model, 'text')->widget(Widget::className(), [
            'settings' => [
                'lang' => 'ru',
                'minHeight' => 500,
                'imageUpload' => Url::to(['/admin/video/category-image-upload']),
                'plugins' => [
                    'table',
                    'fontcolor',
                    'video',
                    'fullscreen',
                    'imagemanager'
                ]
            ]
        ]) : null ?>

        <?= SeoForm::widget(['form' => $form, 'model' => $model]);  ?>

        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>