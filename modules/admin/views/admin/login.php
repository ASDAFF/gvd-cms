<?php

use app\modules\admin\assets\AdminBlueAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

AdminBlueAsset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body class="loginpages">
<?php $this->beginBody() ?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="userpic"><img src="/img/admin/default_profile.png" alt="" ></div>
                <div class="panel-body">
                    <h2 class="text-center">Авторизация</h2>
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                        <fieldset>
                            <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'E-mail'])->label(false) ?>
                            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Пароль'])->label(false) ?>
                            <br>
                            <!-- Change this to a button or input when using this as a form -->
                            <?= Html::submitButton('Войти', ['name' => 'login-button', 'class' => 'btn btn-lg btn-primary btn-block']) ?>
                        </fieldset>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
