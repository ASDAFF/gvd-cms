<?php

$this->title = 'Логи входов | Панель управления';

use yii\helpers\Url;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Логи входов в панель управления</h1>
        <p class="page-subtitle">Информация о входах пользователей в панель управления сайтом.</p>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li>Логи</li>
    <li class="active">Входы</li>
</ol>

<div class="row">
    <div class="col-md-12">

                <div id="dataTables-log-enter_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table  dataTable no-footer dtr-inline" id="dataTables-log-enter" role="grid" aria-describedby="dataTables-log-enter_info" style="width: 966px;">
                                <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="dataTables-log-enter" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 139px;">
                                        Пользователь
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTables-log-enter" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 143px;">
                                        Права
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTables-log-enter" rowspan="1" colspan="1" aria-label="status: activate to sort column ascending" style="width: 103px;">
                                        Дата
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTables-log-enter" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending" style="width: 130px;">
                                        IP
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTables-log-enter" rowspan="1" colspan="1" aria-label="status: activate to sort column ascending" style="width: 103px;">
                                        Клиент
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($logs as $key => $log) { ?>
                                    <?php $log_u = $log->user; ?>
                                    <tr class="<?= ($key%2) ? 'odd' : 'even' ?>" role="row">
                                        <td>
                                            <img src="<?= $log_u->avatar ?>" alt="" class="gridpic"><a href="<?= Url::to(['/admin/user/view', 'id' => $log->user_id]) ?>" target="_blank"><?= $log_u->name || $log_u->last_name ? $log_u->name.' '.$log_u->last_name : $log_u->email ?></a>
                                        </td>
                                        <td>
                                            <?php
                                            foreach (Yii::$app->authManager->getRolesByUser($log->user_id) as $key => $roles) {
                                                if ($key != 0) echo ', ';
                                                echo $roles->description;
                                            }
                                            ?>
                                        </td>
                                        <td class="center">
                                            <?= Yii::$app->formatter->asDate($log->time, 'php:d.m.Y H:i') ?>
                                        </td>
                                        <td>
                                            <?= $log->user_ip ?>
                                        </td>
                                        <td>
                                            <?= $log->oS.', '.$log->browser ?>
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