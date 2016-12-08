<?php

$this->title = 'Пользователи | Панель управления';

use yii\helpers\Url;
use app\components\api\AccessAPI;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Пользователи</h1>
        <p class="page-subtitle">Управление пользователями, распределение ролей для доступа к редактированию различных частей сайта.</p>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li class="active">Пользователи</li>
</ol>

<?php if (Yii::$app->session->hasFlash('user_manage_flash')) { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= Yii::$app->session->getFlash('user_manage_flash') ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active"><a href="<?= Url::to(['/admin/user/index']) ?>" aria-expanded="true">Пользователи</a> </li>
            <?php if (AccessAPI::can('viewRoles')) { ?>
            <li><a href="<?= Url::to(['/admin/user/roles']) ?>">Управление ролями</a> </li>
            <?php } ?>
        </ul>
        <br>
        <div class="tab-content">
            <div class="tab-pane fade padding active in" id="users">
                <a class="btn btn-success" href="<?= Url::to(['/admin/user/create']) ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Добавить пользователя</a>
                <br><br>

                <div id="dataTables-users_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table  dataTable no-footer dtr-inline" id="dataTables-users" role="grid" aria-describedby="dataTables-users_info" style="width: 966px;" data-root="<?= AccessAPI::can('root') ?>">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="dataTables-users" rowspan="1" colspan="1" aria-sort="ascending" aria-label="User : activate to sort column descending" style="width: 20px;">

                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="dataTables-users" rowspan="1" colspan="1" aria-sort="ascending" aria-label="User : activate to sort column descending" style="width: 139px;">
                                            Пользователь
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTables-users" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 143px;">
                                            E-mail
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTables-users" rowspan="1" colspan="1" aria-label="status: activate to sort column ascending" style="width: 103px;">
                                            Статус
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTables-users" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending" style="width: 130px;">
                                            Права
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTables-users" rowspan="1" colspan="1" aria-label="status: activate to sort column ascending" style="width: 103px;">
                                            Дата регистрации
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $key => $u) { ?>
                                    <tr class="<?= ($key%2) ? 'odd' : 'even' ?>" role="row" data-phone="<?= $u->phone ?>" data-id="<?= $u->id ?>">
                                        <td class="manage" style="cursor: pointer;"><i class="fa fa-angle-down"></i> </td>
                                        <td class="sorting_1"><img src="<?= $u->avatar ?>" alt="" class="gridpic"><?= $u->name.' '.$u->last_name ?></td>
                                        <td><?= $u->email ?></td>
                                        <td class="center">
                                            <?php if ($u->status) { ?>
                                                <span class="status active">активен</span>
                                            <?php } else { ?>
                                                <span class="status inactive">неактивен</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php
                                            foreach (Yii::$app->authManager->getRolesByUser($u->id) as $key => $roles) {
                                                if ($key != 0) echo ', ';
                                                echo $roles->description;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?= Yii::$app->formatter->asDate($u->created_at, 'php:d.m.Y') ?>
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
    </div>
</div>