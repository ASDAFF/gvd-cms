<?php

$this->title = 'Слайды | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\api\AccessAPI;

$this->registerCss("
    .huge {
        font-size: 20px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .news_img {
        width: 100%;
        height: 200px;
        position: relative;
        overflow: hidden;
        padding: 0!important;
        background: center no-repeat;
        background-size: cover;
    }
    .colorlight {
        padding-top: 8px;
    }
");

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Слайды "<?= $slider->title ?>"</h1>
        <p class="page-subtitle">Управление слайдами в слайдере "<?= $slider->title ?>".</p>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li>Элементы сайта</li>
    <li><a href="<?= Url::to(['/admin/sliders/index']) ?>">Слайдеры</a></li>
    <li class="active"><?= $slider->title ?></li>
</ol>

<?php if (Yii::$app->session->hasFlash('slide_flash')) { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= Yii::$app->session->getFlash('slide_flash') ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">

        <?php if (AccessAPI::can('createUpdateSliders')) { ?>
            <a class="btn btn-success" href="<?= Url::to(['/admin/sliders/create-item', 'id' => $slider->primaryKey]) ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить слайд</a>
        <?php } else { ?>
            <a class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить слайд</a>
        <?php } ?>
        <br><br>

        <div class="row">
            <?php foreach ($slider->slides as $key => $s) { ?>
                <div class="col-md-4">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 text-left">
                                    <div class="colorlight pull-left">
                                        Номер слайда: <strong><?= $s->order_num ?></strong>
                                    </div>
                                    <div class="pull-right">
                                        <div class="btn-group">
                                            <button type="button" class="dotbtn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="dots"></span> </button>
                                            <ul class="dropdown-menu pull-right" role="menu">
                                                <?php if ($key > 0) { ?>
                                                <li>
                                                    <?php if (AccessAPI::can('createUpdateSliders')) { ?>
                                                        <a href="<?= Url::to(['/admin/sliders/item-to-left', 'id' => $s->primaryKey]) ?>">Переместить влево</a>
                                                    <?php } else { ?>
                                                        <a data-toggle="tooltip" data-placement="right" title="" data-original-title="У вас недостаточно прав!">Редактировать</a>
                                                    <?php } ?>
                                                </li>
                                                <?php } ?>
                                                <?php if ($key < $slider->count-1) { ?>
                                                <li>
                                                    <?php if (AccessAPI::can('createUpdateSliders')) { ?>
                                                        <a href="<?= Url::to(['/admin/sliders/item-to-right', 'id' => $s->primaryKey]) ?>">Переместить вправо</a>
                                                    <?php } else { ?>
                                                        <a data-toggle="tooltip" data-placement="right" title="" data-original-title="У вас недостаточно прав!">Редактировать</a>
                                                    <?php } ?>
                                                </li>
                                                <?php } ?>
                                                <li>
                                                    <?php if (AccessAPI::can('createUpdateSliders')) { ?>
                                                        <a href="<?= Url::to(['/admin/sliders/update-item', 'id' => $s->primaryKey]) ?>">Редактировать</a>
                                                    <?php } else { ?>
                                                        <a data-toggle="tooltip" data-placement="right" title="" data-original-title="У вас недостаточно прав!">Редактировать</a>
                                                    <?php } ?>
                                                </li>
                                                <li>
                                                    <?php if (AccessAPI::can('deleteSliders')) { ?>
                                                        <a href="#delete_slide_modal" data-toggle="modal" data-target="delete_slide_modal" data-img="<?= $s->photo ?>" data-id="<?= $s->primaryKey ?>">Удалить</a>
                                                    <?php } else { ?>
                                                        <a data-toggle="tooltip" data-placement="right" title="" data-original-title="У вас недостаточно прав!">Удалить</a>
                                                    <?php } ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="huge"><?= $s->title ? $s->title : '<Без названия>' ?></div>
                                    <br>
                                </div>
                                <div class="col-xs-12 text-center" style="height: 200px;">
                                    <div class="news_img" style="background-image: url(<?= $s->photo ?>);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</div>

<?php if (AccessAPI::can('deleteSliders')) { ?>
    <div class="modal fade" id="delete_slide_modal" tabindex="-1" role="dialog" aria-labelledby="delete_slide_modal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Удалить слайд?</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="delete_slide_button">Удалить</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
