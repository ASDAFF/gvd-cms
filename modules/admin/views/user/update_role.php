<?php

$this->title = 'Редактирование роли | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Редактирование роли: <?= $role->description ?></h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li><a href="<?= Url::to(['/admin/user']) ?>">Пользователи</a></li>
    <li><a href="<?= Url::to(['/admin/user/roles']) ?>">Управление ролями</a></li>
    <li class="active">Редактирование роли: <?= $role->description ?></li>
</ol>
<?php if (Yii::$app->user->can($role->name) && (!Yii::$app->user->can('root'))) { ?>
    <div class="alert alert-warning">Вы не можете редактировать свои же права!</div>
<?php } ?>
<?php if (Yii::$app->session->hasFlash('updated_role')) { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= Yii::$app->session->getFlash('updated_role') ?>
    </div>
<?php } ?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-gears"></i> Панель управления</div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="checkbox">
                        <?= $form->field($model, 'accessAdmin')->checkbox() ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-user"></i> Пользователи и доступ</div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="checkbox">
                        <?= $form->field($model, 'viewUsers')->checkbox() ?>
                    </div>
                    <div class="checkbox">
                        <?= $form->field($model, 'createUser')->checkbox() ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <?= $form->field($model, 'viewRoles')->checkbox() ?>
                    </div>
                    <div class="checkbox">
                        <?= $form->field($model, 'createRole')->checkbox() ?>
                    </div>
                    <div class="checkbox">
                        <?= $form->field($model, 'updateRole')->checkbox() ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <?= $form->field($model, 'changeUserRole')->checkbox() ?>
                    </div>
                    <div class="checkbox">
                        <?= $form->field($model, 'changeUserStatus')->checkbox() ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-file-text"></i> Страницы</div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="checkbox">
                        <?= $form->field($model, 'viewPages')->checkbox() ?>
                        <?= $form->field($model, 'createUpdatePages')->checkbox() ?>
                        <?= $form->field($model, 'deletePages')->checkbox() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php if (Yii::$app->getModule('news')->status) { ?>
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-newspaper-o"></i> Новости</div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="checkbox">
                        <?= $form->field($model, 'viewNews')->checkbox() ?>
                        <?= $form->field($model, 'createUpdateNews')->checkbox() ?>
                        <?= $form->field($model, 'deleteNews')->checkbox() ?>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if (Yii::$app->getModule('video')->status) { ?>
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-play-circle"></i> Видеогалерея</div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="checkbox">
                        <?= $form->field($model, 'viewVideo')->checkbox() ?>
                        <?= $form->field($model, 'createUpdateVideo')->checkbox() ?>
                        <?= $form->field($model, 'deleteVideo')->checkbox() ?>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if (Yii::$app->getModule('photo')->status) { ?>
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-camera"></i> Фотогалерея</div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="checkbox">
                            <?= $form->field($model, 'viewPhoto')->checkbox() ?>
                            <?= $form->field($model, 'createUpdatePhoto')->checkbox() ?>
                            <?= $form->field($model, 'deletePhoto')->checkbox() ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (Yii::$app->getModule('sliders')->status) { ?>
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-cubes"></i> Слайдеры</div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="checkbox">
                            <?= $form->field($model, 'viewSliders')->checkbox() ?>
                            <?= $form->field($model, 'createUpdateSliders')->checkbox() ?>
                            <?= $form->field($model, 'deleteSliders')->checkbox() ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-male"></i> Логи</div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="checkbox">
                        <?= $form->field($model, 'enterLog')->checkbox() ?>
                        <?= Yii::$app->getModule('news')->status ? $form->field($model, 'newsLog')->checkbox() : null ?>
                        <?= Yii::$app->getModule('video')->status &&  Yii::$app->getModule('video')->categories ? $form->field($model, 'videoCategoryLog')->checkbox() : null ?>
                        <?= Yii::$app->getModule('video')->status ? $form->field($model, 'videoLog')->checkbox() : null ?>
                        <?= Yii::$app->getModule('photo')->status &&  Yii::$app->getModule('photo')->categories ? $form->field($model, 'photoCategoryLog')->checkbox() : null ?>
                        <?= Yii::$app->getModule('photo')->status ? $form->field($model, 'photoLog')->checkbox() : null ?>
                        <?= Yii::$app->getModule('pages')->status ? $form->field($model, 'pagesLog')->checkbox() : null ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= !Yii::$app->user->can($role->name) || Yii::$app->user->can('root') ? Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) : null ?>
<?php ActiveForm::end(); ?>