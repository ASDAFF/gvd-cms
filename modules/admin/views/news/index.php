<?php

$this->title = 'Новости | Панель управления';

use yii\helpers\Url;
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
    .desc {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
        padding-top: 33%;
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
        <h1 class="page-header">Новости</h1>
        <p class="page-subtitle">Управление новостями сайта: добавление, редактирование, удаление.</p>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li class="active">Новости</li>
</ol>

<?php if (Yii::$app->session->hasFlash('news_flash')) { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= Yii::$app->session->getFlash('news_flash') ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-sm-6">
                <?php if (AccessAPI::can('createUpdateNews')) { ?>
                    <a class="btn btn-success" href="<?= Url::to(['/admin/news/create']) ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить новость</a>
                <?php } else { ?>
                    <a class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить новость</a>
                <?php } ?>
            </div>
            <div class="col-sm-6 text-right">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#table_view" data-toggle="tab" aria-expanded="true"> <span class="fa fa-list-alt icon"></span></a></li>
                    <li class=""><a href="#block_view" data-toggle="tab" aria-expanded="false"> <span class="fa fa-th icon"></span></a></li>
                </ul>
            </div>
        </div>
        <br>

        <div class="tab-content">
            <div class="tab-pane fade active in" id="table_view">

                <?= $this->render('_index_table', [
                    'news' => $all_news
                ]) ?>

            </div>
            <div class="tab-pane fade" id="block_view">

                <?= $this->render('_index_block', [
                    'news' => $news,
                    'pages' => $pages,
                    'search' => $search
                ]) ?>

            </div>
        </div>

    </div>
</div>

<?php if (AccessAPI::can('deleteNews')) { ?>
<div class="modal fade" id="delete_news_modal" tabindex="-1" role="dialog" aria-labelledby="delete_news_modal" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Удалить новость?</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-danger" id="delete_news_button">Удалить</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>