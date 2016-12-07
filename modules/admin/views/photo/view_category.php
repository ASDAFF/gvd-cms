<?php

$this->title = ($cat ? $cat->title : 'Фотоогалерея'). ' | Панель управления';

use yii\helpers\Url;
use app\components\widgets\LinkPagerAdmin;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\api\AccessAPI;

$this->registerCss("
    .colorlight {
        padding-top: 8px;
    }
    
    .news_img {
        width: 100%;
        position: relative;
        overflow: hidden;
        padding: 0!important;
    }
    .news_img:before {
        content: '';
        display: block;
        position: relative;
        padding-top: 100%;
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
    
    .grid .grid-item:before {
        display: none;
    }
    
    .panel-photo {
        margin-bottom: 0;
    }
");

?>

    <div class="row">
        <div class="col-md-12  header-wrapper" >
            <h1 class="page-header"><?= $cat ? 'Категория "'.$cat->title.'". ' : null ?>Фотогалерея</h1>
            <p class="page-subtitle">Управление фото<?= $cat ? ' в категории '.$cat->title : null ?>.</p>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

    <ol class="breadcrumb">
        <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
        <?php if ($cat) { ?>
            <li><a href="<?= Url::to(['/admin/photo/index']) ?>">Фотогалерея</a></li>
            <li class="active"><?= $cat->title ?></li>
        <?php } else { ?>
            <li class="active">Фотогалерея</li>
        <?php } ?>
    </ol>

<?php if (Yii::$app->session->hasFlash('photo_flash')) { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= Yii::$app->session->getFlash('photo_flash') ?>
    </div>
<?php } ?>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6">
                    <?php if (AccessAPI::can('createUpdatePhoto')) { ?>
                        <a class="btn btn-success" href="<?= Url::to(['/admin/photo/create-photo', 'parent' => $cat ? $cat->photo_category_id : null]) ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить фото</a>
                        <a class="btn btn-success upload_few_photos" href="#" data-cat="<?= $cat ? $cat->photo_category_id : null ?>"><i class="fa fa-copy"></i>&nbsp;&nbsp;&nbsp; Загрузить несколько фото</a>
                    <?php } else { ?>
                        <a class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить фото</a>
                        <a class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Загрузить несколько фото</a>
                    <?php } ?>
                </div>
                <ul class="nav nav-pills pull-right" role="tablist">
                    <li role="presentation"><a href="javascript:void(0)" class="open-panel link icon-only galleryone"><i class="fa fa-bars"></i></a></li>
                    <li role="presentation"><a href="javascript:void(0)" class="open-panel link icon-only gallerytwo"><i class="fa fa-th-large"></i></a></li>
                    <li role="presentation"><a href="javascript:void(0)" class="open-panel link icon-only gallerythree"><i class="fa fa-th"></i></a></li>
                </ul>
            </div>
            <br>


            <div class="row">
                <?php $form = ActiveForm::begin(['method' => 'get']); ?>
                <div class="col-md-4">
                    <?= $form->field($search, 'sort')->dropDownList([
                        'dateDesc' => 'по дате (от последней)',
                        'dateAsc' => 'по дате (от первой)',
                    ]) ?>
                </div>
                <div class="col-md-4 text-center">
                    <?= Html::submitButton('<i class="fa fa-search"></i>&nbsp;&nbsp;Применить', ['class' => 'btn btn-info']) ?>
                    <a href="<?= Url::to(['/admin/photo/view-category', 'id' => ($cat ? $cat->photo_category_id : null)]) ?>" class="btn btn-danger"><i class="fa fa-times"></i>&nbsp;&nbsp;Очистить</a>
                </div>
                <?php ActiveForm::end(); ?>
                <div class="col-md-4 text-right">
                    <?php if (AccessAPI::can('deletePhoto')) { ?>
                    <a href="#" class="btn btn-danger" style="display: none;" id="delete_chosen_photo"><i class="fa fa-trash"></i>&nbsp;&nbsp;Удалить выбранные</a>
                    <?php } else { ?>
                        <a class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" style="display: none;" title="" data-original-title="У вас недостаточно прав!" id="delete_chosen_photo"><i class="fa fa-trash"></i>&nbsp;&nbsp;Удалить выбранные</a>
                    <?php } ?>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <?= !$photos ? '<p>Ничего не найдено по данному запросу.</p>' : null ?>
                    <div class="grid three">
                        <?php foreach ($photos as $p) { ?>
                        <div class="grid-item">
                            <div class="panel panel-photo">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 text-left">
                                            <div class="colorlight pull-left">
                                                <input type="checkbox" data-id="<?= $p->photo_id ?>" class="checked_photo">
                                            </div>
                                            <div class="pull-right">
                                                <div class="btn-group">
                                                    <button type="button" class="dotbtn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="dots"></span> </button>
                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                        <li><a href="<?= $p->photo ?>" target="_blank">Открыть в новом окне</a> </li>
                                                        <li>
                                                            <?php if (AccessAPI::can('createUpdatePhoto')) { ?>
                                                                <a href="<?= Url::to(['/admin/photo/update-photo', 'id' => $p->photo_id]) ?>">Редактировать</a>
                                                            <?php } else { ?>
                                                                <a data-toggle="tooltip" data-placement="right" title="" data-original-title="У вас недостаточно прав!">Редактировать</a>
                                                            <?php } ?>
                                                        </li>
                                                        <li>
                                                            <?php if (AccessAPI::can('deletePhoto')) { ?>
                                                                <a href="#delete_photo_modal" data-toggle="modal" data-target="delete_photo_modal" data-img="<?= $p->photo ?>" data-id="<?= $p->photo_id ?>">Удалить</a>
                                                            <?php } else { ?>
                                                                <a data-toggle="tooltip" data-placement="right" title="" data-original-title="У вас недостаточно прав!">Удалить</a>
                                                            <?php } ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 text-center">
                                            <div class="news_img">
                                                <div style="background-image: url(<?= $p->photo ?>);"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= LinkPagerAdmin::widget([
                        'pagination' => $pages,
                    ]) ?>
                </div>
            </div>

        </div>
    </div>

<?php if (AccessAPI::can('deletePhoto')) { ?>
    <div class="modal fade" id="delete_photo_modal" tabindex="-1" role="dialog" aria-labelledby="delete_photo_modal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Удалить фото?</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="delete_photo_button" data-parent="<?= $cat->photo_category_id ?>">Удалить</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (AccessAPI::can('deletePhoto')) { ?>
    <div class="modal fade" id="delete_chosen_photo_modal" tabindex="-1" role="dialog" aria-labelledby="delete_chosen_photo_modal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Удалить выбранные фото?</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="delete_chosen_photo_button" data-parent="<?= $cat->photo_category_id ?>">Удалить</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (AccessAPI::can('createUpdatePhoto')) { ?>
    <?php $form1 = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'hidden']]); ?>
        <?= $form1->field($photos_form, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
        <?= $form1->field($photos_form, 'cat')->hiddenInput() ?>
    <?php ActiveForm::end(); ?>
<?php } ?>