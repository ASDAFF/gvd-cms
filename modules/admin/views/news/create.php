<?php

$this->title = 'Добавление новости | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\datetime\DateTimePicker;
use vova07\imperavi\Widget;
use app\modules\news\News;
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
    .news_img {
        width: 100%;
        position: relative;
        overflow: hidden;
        padding: 0!important;
        cursor: pointer;
    }
    #news_cover:before {
        content: '';
        display: block;
        position: relative;
        padding-top: 33%;
    }
    #news_cover div {
        background-image: url(/img/admin/news_cover.jpg);
    }
    
    #news_main:before, #news_extra:before {
        content: '';
        display: block;
        position: relative;
        padding-top: 100%;
    }
    
    #news_main div {
        background-image: url(/img/admin/news_main.jpg);
    }
    #news_extra div {
        background-image: url(/img/admin/news_extra.jpg);
    }
    
    .news_img div {
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
        <h1 class="page-header">Добавление новости</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li><a href="<?= Url::to(['/admin/news/index']) ?>">Новости</a></li>
    <li class="active">Добавление новости</li>
</ol>

<div class="row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

        <div id="news_cover" class="news_img well memberblock"><div><a class="memmbername delete_news_photo" href="#" data-id="">удалить</a></div></div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'title')->textInput(['placeholder' => 'Напишите здесь заголовок новой новости']) ?>
                <?= $form->field($model, 'description')->textarea(['placeholder' => 'Краткое описание новости выводится под её названием в перечне новостей на сайте.']) ?>
                <?= $form->field($model, 'epigraph')->textarea(['placeholder' => 'Эпиграф новости выводится под её названием на странице самой новости и представляет собой краткое введение в статью.']) ?>
            </div>
            <?php if ($indexImages) { ?>
            <div class="col-sm-3">
                <div id="news_main" class="news_img well memberblock">
                    <div><a class="memmbername delete_news_photo" href="#" data-id="">удалить</a></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div id="news_extra" class="news_img well memberblock">
                    <div><a class="memmbername delete_news_photo" href="#" data-id="">удалить</a></div>
                </div>
            </div>
            <?php } ?>
        </div>

        <?= $form->field($model, 'date')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => 'Выберите дату новости'],
            'language' => 'ru',
            'layout' => '{picker}{input}{remove}',
            'pluginOptions' => [
                'autoclose' => true
            ]
        ]) ?>

        <?= $form->field($model, 'text')->widget(Widget::className(), [
            'settings' => [
                'lang' => 'ru',
                'minHeight' => 500,
                'plugins' => [
                    'table',
                    'fontcolor',
                    'video',
                    'gvdslider',
                    'fullscreen'
                ]
            ]
        ]) ?>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="list-group-item withswitch">
                            <h5 class="list-group-item-heading" style="line-height: 1.8;">Включить слайдер с фотографиями</h5>
                            <div class="switch">
                                <input id="news_slider_photo_switch" class="cmn-toggle cmn-toggle-round" type="checkbox" name="NewsForm[slider]" value="0">
                                <label for="news_slider_photo_switch"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 panel-body" id="upload_slider_photo_block" style="display: none;">
                        <div class="btn btn-primary" id="upload_slider_photo"><span class="glyphicon glyphicon-camera"></span>&nbsp;&nbsp;&nbsp;Добавить фото</div>
                        <div class="memberblock">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'published')->checkbox() ?></div>
            <div class="col-md-6"><?= $form->field($model, 'popular')->checkbox() ?></div>
        </div>

        <?= SeoForm::widget(['form' => $form, 'model' => $model]);  ?>

        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'dropzone hidden'], 'id' => 'slider_photo_form', 'action' => Url::to(['/admin/news/upload-photo'])]); ?>
    <?= $form->field($slider_form, 'news_id')->hiddenInput(['value' => $item->news_id]) ?>
    <?= $form->field($slider_form, 'label')->hiddenInput(['value' => 'slider']) ?>
    <?= $form->field($slider_form, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
<?php ActiveForm::end(); ?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'dropzone hidden'], 'id' => 'news_img_form', 'action' => Url::to(['/admin/news/upload-photo'])]); ?>
<?= $form->field($cover_form, 'news_id')->hiddenInput(['value' => $item->news_id]) ?>
<?= $form->field($cover_form, 'label')->hiddenInput(['value' => '']) ?>
<?= $form->field($cover_form, 'image')->fileInput() ?>
<?php ActiveForm::end(); ?>
