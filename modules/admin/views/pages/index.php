<?php

$this->title = 'Страницы | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\api\AccessAPI;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Страницы</h1>
        <p class="page-subtitle">Управление страницами с информацией на сайте.</p>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li class="active">Страницы</li>
</ol>

<?php if (Yii::$app->session->hasFlash('pages_flash')) { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= Yii::$app->session->getFlash('pages_flash') ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">

        <?php if (AccessAPI::can('createUpdatePages')) { ?>
            <a class="btn btn-success" href="<?= Url::to(['/admin/pages/create']) ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить страницу</a>
        <?php } else { ?>
            <a class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить страницу</a>
        <?php } ?>
        <br><br>

        <div id="dataTables-pages_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table  dataTable no-footer dtr-inline" id="dataTables-pages" role="grid" aria-describedby="dataTables-pages_info" style="width: 966px;">
                        <thead>
                        <tr role="row">
                            <th tabindex="0" aria-controls="dataTables-pages" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 946px;">
                                Заголовок
                            </th>
                            <th tabindex="0" aria-controls="dataTables-pages" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 20px;">

                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pages as $key => $p) { ?>
                            <tr role="row">
                                <td>
                                    <?= $p->title ?>
                                </td>
                                <td style="cursor: pointer;">
                                    <div class="btn-group">
                                        <button type="button" class="dotbtn dropdown-toggle" data-toggle="dropdown"> <span class="dots"></span> </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li>
                                                <?php if (AccessAPI::can('createUpdatePages')) { ?>
                                                    <a href="<?= Url::to(['/admin/pages/update', 'id' => $p->page_id]) ?>">Редактировать</a>
                                                <?php } else { ?>
                                                    <a data-toggle="tooltip" data-placement="left" title="" data-original-title="У вас недостаточно прав!">Редактировать</a>
                                                <?php } ?>
                                            </li>
                                            <li>
                                                <?php if (AccessAPI::can('deletePages')) { ?>
                                                    <a href="#delete_pages_modal" data-toggle="modal" data-target="delete_pages_modal" data-name="<?= $p->title ?>" data-id="<?= $p->page_id ?>">Удалить</a>
                                                <?php } else { ?>
                                                    <a data-toggle="tooltip" data-placement="left" title="" data-original-title="У вас недостаточно прав!">Удалить</a>
                                                <?php } ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php if (AccessAPI::can('deletePages')) { ?>
    <div class="modal fade" id="delete_pages_modal" tabindex="-1" role="dialog" aria-labelledby="delete_pages_modal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Удалить страницу?</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="delete_pages_button">Удалить</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
