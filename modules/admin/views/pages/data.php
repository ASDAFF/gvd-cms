<?php

$this->title = 'Поля | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\api\AccessAPI;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Поля</h1>
        <p class="page-subtitle">Управление полями страницы <?= $page->title ?>.</p>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li><a href="<?= Url::to(['/admin/pages/index']) ?>">Страницы</a></li>
    <li class="active">Поля страницы "<?= $page->title ?>"</li>
</ol>

<?php if (Yii::$app->session->hasFlash('pages_data_flash')) { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= Yii::$app->session->getFlash('pages_data_flash') ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">

        <?php if (AccessAPI::can('root')) { ?>
            <a class="btn btn-success" href="<?= Url::to(['/admin/pages/create-data', 'id' => $page->primaryKey]) ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить поле</a>
        <?php } ?>
        <br><br>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Метка</th>
                    <th>Поле</th>
                    <th>Тип</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php if ($page->dataObj) { ?>
                <?php $i = 1; foreach ($page->dataObj as $key => $data) { ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $key ?></td>
                    <td><?= $data->title ?></td>
                    <td><?= $data->type ?></td>
                    <td style="width: 20px; cursor: pointer;">
                        <div class="btn-group">
                            <button type="button" class="dotbtn dropdown-toggle" data-toggle="dropdown"> <span class="dots"></span> </button>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li>
                                    <?php if (AccessAPI::can('root')) { ?>
                                        <a href="<?= Url::to(['/admin/pages/update-data', 'key' => $key, 'id' => $page->primaryKey]) ?>">Редактировать</a>
                                    <?php } ?>
                                </li>
                                <li>
                                    <?php if (AccessAPI::can('root')) { ?>
                                        <a href="#delete_pages_data_modal" data-toggle="modal" data-target="delete_pages_data_modal" data-name="<?= $data->title ?>" data-key="<?= $key ?>" data-page="<?= $page->primaryKey ?>">Удалить</a>
                                    <?php } ?>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php $i++; } ?>
                <?php } ?>
                </tbody>
            </table>
            <br><br><br><br>
        </div>

    </div>
</div>

<?php if (AccessAPI::can('root')) { ?>
    <div class="modal fade" id="delete_pages_data_modal" tabindex="-1" role="dialog" aria-labelledby="delete_pages_data_modal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Удалить поле?</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="delete_pages_data_button">Удалить</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
