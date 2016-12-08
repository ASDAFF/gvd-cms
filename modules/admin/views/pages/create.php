<?php

$this->title = 'Добавление страницы | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use vova07\imperavi\Widget;
use app\components\widgets\SeoForm;

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
    .icon_preview:before {
        padding-top: 100%;
    }
");

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Добавление страницы</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li><a href="<?= Url::to(['/admin/pages/index']) ?>">Страницы</a></li>
    <li class="active">Добавление страницы</li>
</ol>

<div class="row">
    <div class="col-md-12">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'title')->textInput() ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="preview_img icon_preview well memberblock" data-input="page-icon_img">
                            <div><a class="memmbername delete_img_preview" href="#" data-input="page-icon_img">удалить</a></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'icon_img')->fileInput(['class' => 'img_preview']) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <?php if (Yii::$app->getModule('pages')->cover) { ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="preview_img well memberblock" data-input="page-image">
                                <div><a class="memmbername delete_img_preview" href="#" data-input="page-image">удалить</a></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'image')->fileInput(['class' => 'img_preview']) ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <?= $form->field($model, 'root_page_id')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'text')->widget(Widget::className(), [
            'settings' => [
                'lang' => 'ru',
                'minHeight' => 500,
                'imageUpload' => Url::to(['/admin/pages/page-image-upload']),
                'plugins' => [
                    'table',
                    'fontcolor',
                    'video',
                    'fullscreen',
                    'imagemanager'
                ]
            ]
        ]) ?>

        <?= SeoForm::widget(['form' => $form, 'model' => $model]);  ?>

        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>