<?php

$this->title = ($cat ? $cat->title : 'Видеогалерея'). ' | Панель управления';

use yii\helpers\Url;
use yii\widgets\Pjax;
use app\components\api\AccessAPI;

$this->registerCss("
    .nav-tabs {
        display: inline-block;
    }
    .nav-tabs span {
        margin-right: 0;
    }
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
        <h1 class="page-header"><?= $cat ? 'Категория "'.$cat->title.'". ' : null ?>Видеогалерея</h1>
        <p class="page-subtitle">Управление видео<?= $cat ? ' в категории '.$cat->title : null ?>.</p>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <?php if ($cat) { ?>
    <li><a href="<?= Url::to(['/admin/video/index']) ?>">Видеогалерея</a></li>
    <li class="active"><?= $cat->title ?></li>
    <?php } else { ?>
    <li class="active">Видеогалерея</li>
    <?php } ?>
</ol>

<?php if (Yii::$app->session->hasFlash('video_flash')) { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= Yii::$app->session->getFlash('video_flash') ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-sm-6">
                <?php if (AccessAPI::can('createUpdateVideo')) { ?>
                <a class="btn btn-success" href="<?= Url::to(['/admin/video/create-video', 'parent' => $cat ? $cat->video_category_id : null]) ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить видео</a>
                <?php } else { ?>
                    <a class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить видео</a>
                <?php } ?>
            </div>
            <div class="col-sm-6 text-right">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#video_table_view" data-toggle="tab" aria-expanded="true"> <span class="fa fa-list-alt icon"></span></a></li>
                    <li class=""><a href="#video_block_view" data-toggle="tab" aria-expanded="false>"> <span class="fa fa-th icon"></span></a></li>
                </ul>
            </div>
        </div>
        <br>

        <div class="tab-content">
            <div class="tab-pane fade" id="video_table_view">

                <?= $this->render('_video_table', [
                    'videos' => $cat ? $cat->videos : $all_videos
                ]) ?>

            </div>
            <div class="tab-pane fade" id="video_block_view">



                <?= $this->render('_video_block', [
                    'videos' => $videos,
                    'pages' => $pages,
                    'search' => $search,
                    'cat' => $cat
                ]) ?>

                

            </div>
        </div>

    </div>
</div>

<?php if (AccessAPI::can('deleteVideo')) { ?>
    <div class="modal fade" id="delete_video_modal" tabindex="-1" role="dialog" aria-labelledby="delete_video_modal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Удалить видео?</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="delete_video_button" data-parent="<?= $cat->video_category_id ?>">Удалить</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>