<?php

$this->title = 'Фотогалерея | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\api\AccessAPI;

function index_tree($c, $lvl) {
    $res = null;
    foreach ($c->subcategories as $s) {
        $res .= '<tr role="row">';
        $res .= '<td style="padding-left: '.(15*($lvl+1)).'px!important;">'.
            ($s->isSubcategories ? '<i class="fa fa-caret-down"></i> ' : null).$s->title.
            (AccessAPI::can('createUpdatePhoto') ? '<a href="#" data-cat="'.($c ? $c->photo_category_id : null).'" class="upload_few_photos status active pull-right" style="cursor: pointer; margin-left: 20px;">Добавить несколько фото</a><a href="'.Url::to(['/admin/photo/create-photo', 'parent' => $s->photo_category_id]).'" class="status active pull-right" style="cursor: pointer; margin-left: 20px;">Добавить 1 фото</a><a href="'.Url::to(['/admin/photo/create-category', 'parent' => $s->photo_category_id]).'" class="status active pull-right" style="cursor: pointer;">Добавить подкатегорию</a></td>' : '<a class="status active pull-right" style="cursor: pointer; margin-left: 20px;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Добавить несколько фото</a><a class="status active pull-right" style="cursor: pointer; margin-left: 20px;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Добавить 1 фото</a><a class="status active pull-right" style="cursor: pointer;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Добавить подкатегорию</a></td>');
        $res .= '<td style="cursor: pointer;">'.
            '<div class="btn-group">'.
            '<button type="button" class="dotbtn dropdown-toggle" data-toggle="dropdown"> <span class="dots"></span> </button>'.
            '<ul class="dropdown-menu pull-right" role="menu">'.
            '<li><a href="'.Url::to(['/admin/photo/view-category', 'id' => $s->photo_category_id]).'">Перейти в категорию</a> </li>'.
            '<li>'.
            (AccessAPI::can('createUpdatePhoto') ? '<a href="'.Url::to(['/admin/photo/update-category', 'id' => $s->photo_category_id]).'">Редактировать</a>' : '<a data-toggle="tooltip" data-placement="left" title="" data-original-title="У вас недостаточно прав!">Редактировать</a>').
            '</li>'.
            '<li>'.
            (AccessAPI::can('deletePhoto') ? '<a href="#delete_photo_category_modal" data-toggle="modal" data-target="delete_photo_category_modal" data-name="'.$s->title.'" data-id="'.$s->photo_category_id.'">Удалить</a>' : '<a data-toggle="tooltip" data-placement="left" title="" data-original-title="У вас недостаточно прав!">Удалить</a>').
            '</li>'.
            '</ul>'.
            '</div>'.
            '</td>';
        $res .= '</tr>';
        $res .= index_tree($s, $lvl+1);
    }
    return $res;
}

?>

    <div class="row">
        <div class="col-md-12  header-wrapper" >
            <h1 class="page-header">Фотогалерея</h1>
            <p class="page-subtitle">Управление категориями и фото в галерее на сайте.</p>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

    <ol class="breadcrumb">
        <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
        <li class="active">Фотогалерея</li>
    </ol>

<?php if (Yii::$app->session->hasFlash('photo_category_flash')) { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= Yii::$app->session->getFlash('photo_category_flash') ?>
    </div>
<?php } ?>

    <div class="row">
        <div class="col-md-12">

            <?php if (AccessAPI::can('createUpdatePhoto')) { ?>
                <a class="btn btn-success" href="<?= Url::to(['/admin/photo/create-category', 'parent' => 0]) ?>"><i class="fa fa-folder"></i>&nbsp;&nbsp;&nbsp; Добавить корневую категорию</a>
            <?php } else { ?>
                <a class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!"><i class="fa fa-folder"></i>&nbsp;&nbsp;&nbsp; Добавить корневую категорию</a>
            <?php } ?>
            <br><br>

            <div id="dataTables-photo-cat_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table  dataTable no-footer dtr-inline" id="dataTables-photo-cat" role="grid" aria-describedby="dataTables-photo-cat_info" style="width: 966px;">
                            <thead>
                            <tr role="row">
                                <th tabindex="0" aria-controls="dataTables-photo-cat" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 946px;">
                                    Название
                                </th>
                                <th tabindex="0" aria-controls="dataTables-photo-cat" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 20px;">

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($cats as $key => $c) { ?>
                                <tr role="row">
                                    <td>
                                        <?= $c->isSubcategories ? '<i class="fa fa-caret-down"></i> ' : null ?><?= $c->title ?>

                                        <?php if (AccessAPI::can('createUpdatePhoto')) { ?>
                                            <a href="#" data-cat="<?= $c ? $c->photo_category_id : null ?>" class="upload_few_photos status active pull-right" style="cursor: pointer; margin-left: 20px;">Добавить несколько фото</a>
                                            <a href="<?= Url::to(['/admin/photo/create-photo', 'parent' => $c->photo_category_id]) ?>" class="status active pull-right" style="cursor: pointer; margin-left: 20px;">Добавить 1 фото</a>
                                            <a href="<?= Url::to(['/admin/photo/create-category', 'parent' => $c->photo_category_id]) ?>" class="status active pull-right" style="cursor: pointer;">Добавить подкатегорию</a>
                                        <?php } else { ?>
                                            <a class="status active pull-right" style="cursor: pointer; margin-left: 20px;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Добавить несколько фото</a>
                                            <a class="status active pull-right" style="cursor: pointer; margin-left: 20px;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Добавить 1 фото</a>
                                            <a class="status active pull-right" style="cursor: pointer;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Добавить подкатегорию</a>
                                        <?php } ?>
                                    </td>
                                    <td style="cursor: pointer;">
                                        <div class="btn-group">
                                            <button type="button" class="dotbtn dropdown-toggle" data-toggle="dropdown"> <span class="dots"></span> </button>
                                            <ul class="dropdown-menu pull-right" role="menu">
                                                <li><a href="<?= Url::to(['/admin/photo/view-category', 'id' => $c->photo_category_id]) ?>">Перейти в категорию</a> </li>
                                                <li>
                                                    <?php if (AccessAPI::can('createUpdatePhoto')) { ?>
                                                        <a href="<?= Url::to(['/admin/photo/update-category', 'id' => $c->photo_category_id]) ?>">Редактировать</a>
                                                    <?php } else { ?>
                                                        <a data-toggle="tooltip" data-placement="left" title="" data-original-title="У вас недостаточно прав!">Редактировать</a>
                                                    <?php } ?>
                                                </li>
                                                <li>
                                                    <?php if (AccessAPI::can('deletePhoto')) { ?>
                                                        <a href="#delete_all_photo_category_modal" data-toggle="modal" data-target="delete_all_photo_category_modal" data-count="<?= $c->count ?>" data-name="<?= $c->title ?>" data-id="<?= $c->photo_category_id ?>">Удалить все фото</a>
                                                    <?php } else { ?>
                                                        <a data-toggle="tooltip" data-placement="left" title="" data-original-title="У вас недостаточно прав!">Удалить все фото</a>
                                                    <?php } ?>
                                                </li>
                                                <li>
                                                    <?php if (AccessAPI::can('deletePhoto')) { ?>
                                                        <a href="#delete_photo_category_modal" data-toggle="modal" data-target="delete_photo_category_modal" data-name="<?= $c->title ?>" data-id="<?= $c->photo_category_id ?>">Удалить</a>
                                                    <?php } else { ?>
                                                        <a data-toggle="tooltip" data-placement="left" title="" data-original-title="У вас недостаточно прав!">Удалить</a>
                                                    <?php } ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?= $c->isSubcategories ? index_tree($c, 1) : null ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php if (AccessAPI::can('deletePhoto')) { ?>
    <div class="modal fade" id="delete_photo_category_modal" tabindex="-1" role="dialog" aria-labelledby="delete_photo_category_modal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Удалить категорию?</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="delete_photo_category_button">Удалить</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (AccessAPI::can('deletePhoto')) { ?>
    <div class="modal fade" id="delete_all_photo_category_modal" tabindex="-1" role="dialog" aria-labelledby="delete_all_photo_category_modal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Удалить все фото?</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="delete_all_photo_category_button">Удалить</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (AccessAPI::can('deletePhoto')) { ?>
    <?php $form1 = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'hidden']]); ?>
    <?= $form1->field($photos_form, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
    <?= $form1->field($photos_form, 'cat')->hiddenInput() ?>
    <?php ActiveForm::end(); ?>
<?php } ?>
