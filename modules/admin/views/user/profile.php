<?php

$this->title = 'Мой профиль | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Мой Профиль</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li class="active">Профиль</li>
</ol>
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1 class="page-header small">Информация</h1>
            </div>
            <div class="col-md-12">
                <?php if (Yii::$app->session->hasFlash('data_changed')) { ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <?= Yii::$app->session->getFlash('data_changed') ?>
                    </div>
                <?php } ?>
                <div class="userprofile text-center">
                    <div class="userpic"> <img src="<?= Yii::$app->user->identity->avatar ?>" alt="" class="userpicimg profile_avatar"> <a href="" id="change_ava" class="btn btn-primary settingbtn"><i class="fa fa-upload"></i></a> </div>
                </div>
                <?php $form = ActiveForm::begin(); ?>
                <div class="form-group">
                    <?= $form->field($data_form, 'email')->textInput() ?>
                </div>
                <div class="form-group">
                    <?= $form->field($data_form, 'name')->textInput() ?>
                </div>
                <div class="form-group">
                    <?= $form->field($data_form, 'last_name')->textInput() ?>
                </div>
                <div class="form-group">
                    <?= $form->field($data_form, 'phone')->textInput() ?>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1 class="page-header small">Смена пароля</h1>
            </div>
            <div class="col-md-12">
                <?php if (Yii::$app->session->hasFlash('password_changed')) { ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <?= Yii::$app->session->getFlash('password_changed') ?>
                </div>
                <?php } ?>
                <?php $form = ActiveForm::begin(); ?>
                    <div class="form-group">
                        <?= $form->field($pass_form, 'old_password')->passwordInput() ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($pass_form, 'password')->passwordInput() ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($pass_form, 'password_rep')->passwordInput() ?>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Изменить пароль</button>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1 class="page-header small">Выбор темы оформления</h1>
            </div>
            <div class="col-md-12">
                <?php $form = ActiveForm::begin(); ?>
                <div class="form-group">
                    <?= $form->field($theme_form, 'theme')->dropDownList($themes)->label(false) ?>
                </div>
                <?= $form->field($theme_form, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>
                <br>
                <button type="submit" class="btn btn-primary">Применить тему</button>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'dropzone hidden'], 'id' => 'change_ava_form', 'action' => Url::to(['/admin/user/change-avatar'])]); ?>
    <?= $form->field($ava_form, 'avatar')->fileInput() ?>
<?php ActiveForm::end(); ?>