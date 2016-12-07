<?php

$this->title = 'Логи работы со слайдами | Панель управления';

use yii\helpers\Url;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Логи работы со слайдами</h1>
        <p class="page-subtitle">Информация о создании, редактировании и удалении слайдов в слайдерах.</p>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li>Логи</li>
    <li>Элементы сайта</li>
    <li class="active">Работа со слайдами</li>
</ol>

<?php if (Yii::$app->session->hasFlash('log_flash') && Yii::$app->user->can('root')) { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= Yii::$app->session->getFlash('log_flash') ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">

        <?php if (Yii::$app->user->can('root')) { ?>
        <a class="btn btn-danger" href="<?= Url::to(['/admin/log/clear', 'cl' => \app\modules\sliders\models\SliderItem::className()]) ?>"><i class="fa fa-trash-o"></i>&nbsp;&nbsp;&nbsp; Очистить логи</a><br><br>
        <?php } ?>

                <div id="dataTables-log-news_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table  dataTable no-footer dtr-inline" id="dataTables-log-news" role="grid" aria-describedby="dataTables-log-news_info" style="width: 966px;">
                                <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="dataTables-log-news" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 139px;">
                                        Слайд
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTables-log-news" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 139px;">
                                        Действие
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTables-log-news" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 139px;">
                                        Пользователь
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTables-log-news" rowspan="1" colspan="1" aria-label="status: activate to sort column ascending" style="width: 103px;">
                                        Дата
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($logs as $key => $log) { ?>
                                    <?php $log_u = $log->user; ?>
                                    <tr class="<?= ($key%2) ? 'odd' : 'even' ?>" role="row">
                                        <td>
                                            <?php if ($log->item_id != null && $log->slide != null) { ?>
                                                <?= $log->slide ? '<a href="'.Url::to(['/admin/sliders/update-item', 'id' => $log->item_id]).'" target="_blank"><img src="'.$log->slide->photo.'" alt="" class="img-responsive"></a>' : null ?>
                                            <?php } else { ?>
                                                <img src="<?= $log->item_name ?>" alt="" class="img-responsive">
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php switch ($log->action) {
                                                case 'create' : {
                                                    echo '<span class="status active">создание</span>';
                                                    break;
                                                }
                                                case 'update' : {
                                                    echo '<span class="status pending">редактирование</span>';
                                                    break;
                                                }
                                                case 'delete' : {
                                                    echo '<span class="status inactive">удаление</span>';
                                                    break;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <img src="<?= $log_u->avatar ?>" alt="" class="gridpic"><a href="<?= Url::to(['/admin/user/view', 'id' => $log->user_id]) ?>" target="_blank"><?= $log_u->name || $log_u->last_name ? $log_u->name.' '.$log_u->last_name : $log_u->email ?></a>
                                        </td>
                                        <td class="center">
                                            <?= Yii::$app->formatter->asDate($log->time, 'php:d.m.Y H:i') ?>
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