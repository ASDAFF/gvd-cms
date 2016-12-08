<?php

$this->title = 'Текстовая информация | Панель управления';

use yii\helpers\Url;
use app\components\api\AccessAPI;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Текстовая информация</h1>
        <p class="page-subtitle">Управление текстовой информацией на сайте.</p>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li>Элементы сайта</li>
    <li class="active">Текстовая информация</li>
</ol>

<?php if (Yii::$app->session->hasFlash('texts_flash')) { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= Yii::$app->session->getFlash('texts_flash') ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">

        <?php if (AccessAPI::can('root')) { ?>
            <a class="btn btn-success" href="<?= Url::to(['/admin/texts/create']) ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить</a>
            <br><br>
        <?php } ?>

        <div id="dataTables-texts_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table  dataTable no-footer dtr-inline" id="dataTables-texts" role="grid" aria-describedby="dataTables-texts_info" style="width: 966px;">
                        <thead>
                        <tr role="row">
                            <th tabindex="0" aria-controls="dataTables-texts" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 473px;">
                                Название
                            </th>
                            <th tabindex="0" aria-controls="dataTables-texts" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 473px;">
                                Значение
                            </th>
                            <th tabindex="0" aria-controls="dataTables-texts" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 20px;">

                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($texts as $key => $t) { ?>
                            <tr role="row">
                                <td>
                                    <?= $t->title ?>
                                </td>
                                <td>
                                    <?= $t->value ?>
                                </td>
                                <td style="cursor: pointer;">
                                    <div class="btn-group">
                                        <button type="button" class="dotbtn dropdown-toggle" data-toggle="dropdown"> <span class="dots"></span> </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li>
                                                <?php if (AccessAPI::can('updateTexts')) { ?>
                                                    <a href="<?= Url::to(['/admin/texts/update', 'id' => $t->primaryKey]) ?>">Редактировать</a>
                                                <?php } ?>
                                            </li>
                                            <li>
                                                <?php if (AccessAPI::can('root')) { ?>
                                                    <a href="#delete_texts_modal" data-toggle="modal" data-target="delete_texts_modal" data-name="<?= $t->title ?>" data-value="<?= $t->value ?>" data-id="<?= $t->primaryKey ?>">Удалить</a>
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
    <div class="modal fade" id="delete_texts_modal" tabindex="-1" role="dialog" aria-labelledby="delete_texts_modal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Удалить?</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="delete_texts_button">Удалить</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
