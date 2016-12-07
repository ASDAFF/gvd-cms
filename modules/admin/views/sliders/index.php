<?php

$this->title = 'Слайдеры | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\api\AccessAPI;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Слайдеры</h1>
        <p class="page-subtitle">Управление слайдерами с фото и информацией на сайте.</p>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li>Элементы сайта</li>
    <li class="active">Слайдеры</li>
</ol>

<?php if (Yii::$app->session->hasFlash('sliders_flash')) { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= Yii::$app->session->getFlash('sliders_flash') ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">

        <?php if (AccessAPI::can('root')) { ?>
            <a class="btn btn-success" href="<?= Url::to(['/admin/sliders/create']) ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить слайдер</a>
            <br><br>
        <?php } ?>

        <div id="dataTables-sliders_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table  dataTable no-footer dtr-inline" id="dataTables-sliders" role="grid" aria-describedby="dataTables-sliders_info" style="width: 966px;">
                        <thead>
                        <tr role="row">
                            <th tabindex="0" aria-controls="dataTables-sliders" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 946px;">
                                Название
                            </th>
                            <th tabindex="0" aria-controls="dataTables-sliders" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 20px;">

                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($sliders as $key => $s) { ?>
                            <tr role="row">
                                <td>
                                    <?= $s->title ?>
                                </td>
                                <td style="cursor: pointer;">
                                    <div class="btn-group">
                                        <button type="button" class="dotbtn dropdown-toggle" data-toggle="dropdown"> <span class="dots"></span> </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li>
                                                <?php if (AccessAPI::can('viewSliders')) { ?>
                                                    <a href="<?= Url::to(['/admin/sliders/view-slider', 'id' => $s->primaryKey]) ?>">Перейти к слайдам</a>
                                                <?php } else { ?>
                                                    <a data-toggle="tooltip" data-placement="left" title="" data-original-title="У вас недостаточно прав!">Перейти к слайдам</a>
                                                <?php } ?>
                                            </li>
                                            <li>
                                                <?php if (AccessAPI::can('root')) { ?>
                                                    <a href="<?= Url::to(['/admin/sliders/update', 'id' => $s->primaryKey]) ?>">Редактировать</a>
                                                <?php } ?>
                                            </li>
                                            <li>
                                                <?php if (AccessAPI::can('root')) { ?>
                                                    <a href="#delete_sliders_modal" data-toggle="modal" data-target="delete_sliders_modal" data-name="<?= $s->title ?>" data-id="<?= $s->primaryKey ?>">Удалить</a>
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

<?php if (AccessAPI::can('root')) { ?>
    <div class="modal fade" id="delete_sliders_modal" tabindex="-1" role="dialog" aria-labelledby="delete_sliders_modal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Удалить слайдер?</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="delete_sliders_button">Удалить</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
