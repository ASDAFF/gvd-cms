<?php

$this->title = 'Управление пользователем | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\api\AccessAPI;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Управление пользователем</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li><a href="<?= Url::to(['/admin/user']) ?>">Пользователи</a></li>
    <li class="active">Управление пользователем</li>
</ol>

<div class="row">
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1 class="page-header small">Профиль</h1>
                <p class="page-subtitle small">Информация о пользователе</p>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12">
                <div class="userprofile text-center">
                    <div class="userpic"> <img src="<?= $u->avatar ?>" alt="" class="userpicimg"></div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-5">
                        <p>Имя и фамилия</p>
                    </div>
                    <div class="col-md-7">
                        <p><strong><?= $u->name || $u->last_name ? $u->name.' '.$u->last_name : '-' ?></strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <p>E-mail</p>
                    </div>
                    <div class="col-md-7">
                        <p><strong><?= $u->email ?></strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <p>Телефон</p>
                    </div>
                    <div class="col-md-7">
                        <p><strong><?= $u->phone ? $u->phone : '-' ?></strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <p>Дата регистрации</p>
                    </div>
                    <div class="col-md-7">
                        <p><strong><?= Yii::$app->formatter->asDate($u->created_at, 'php: j F Y') ?> г.</strong></p>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="page-header small">Права</h1>
                        <p class="page-subtitle small">Разрешение редактирования различных частей сайта</p>
                    </div>
                    <div class="col-md-12">
                        <div class="list-group ">
                            <?php foreach ($roles as $role) { ?>
                                <div class="list-group-item withswitch">
                                    <h5 class="list-group-item-heading" style="line-height: 1.8;"><?= $role->description ?></h5>
                                    <div class="switch" id="user_role_switch">
                                        <?php
                                            
                                        ?>
                                        <input id="<?= $role->name ?>" data-user="<?= $u->id ?>" class="cmn-toggle cmn-toggle-round" type="checkbox"<?= $u->hasRole($role->name) ? ' checked' : null ?><?= !AccessAPI::can('changeUserRole') ? ' disabled' : ($u->id > 1 ? null : ' disabled') ?>>
                                        <label for="<?= $role->name ?>"></label>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="page-header small">Статус</h1>
                        <p class="page-subtitle small">Неактивные пользователи не могут авторизоваться</p>
                    </div>
                    <div class="col-md-12">
                        <div class="list-group ">
                            <div class="list-group-item withswitch">
                                <h5 class="list-group-item-heading" style="line-height: 1.8;">Активен</h5>
                                <div class="switch">
                                    <input id="user_status_switch" data-user="<?= $u->id ?>" class="cmn-toggle cmn-toggle-round" type="checkbox"<?= Yii::$app->user->identity->status ? ' checked' : null ?><?= !AccessAPI::can('changeUserStatus') ? ' disabled' : ($u->id > 1 ? null : ' disabled') ?>>
                                    <label for="user_status_switch"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <?php if (AccessAPI::can('root')) { ?>
                <form action="<?= Url::to(['/admin/user/delete']) ?>" method="post">
                    <input type="hidden" name="id" value="<?= $u->id ?>">
                    <p class="text-center"><button type="submit" class="btn btn-danger"><i class="fa fa-trash-o"></i> Удалить пользователя</button></p>
                </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>