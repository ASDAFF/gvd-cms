<?php

use yii\helpers\Html;
use app\modules\admin\assets\AdminBlueAsset;
use app\modules\admin\assets\AdminDarkAsset;
use app\modules\admin\assets\AdminWhiteAsset;
use app\modules\admin\assets\AdminClassicAsset;
use app\modules\admin\models\UserAdminTheme;

$this->context->layout = false;

switch ($exception->statusCode) {
    case '404': {
        $this->title = 'Страница не найдена';
        break;
    }
    case '405': {
        $this->title = 'Недопустимый метод';
        break;
    }
    case '403': {
        $this->title = 'Недостаточно прав';
        break;
    }
    case '500': {
        $this->title = 'Ошибка сервера';
        break;
    }
    default: {
        $this->title = 'Ошибка';
        break;
    }
}
$this->title .= ' | Панель управления';

if (Yii::$app->user->isGuest) {
    AdminBlueAsset::register($this);
}
else {
    $user_theme = UserAdminTheme::findOne(['user_id' => Yii::$app->user->id]);
    if ($user_theme) {
        switch ($user_theme->theme) {
            case 'white': {
                AdminWhiteAsset::register($this);
                break;
            }
            case 'dark': {
                AdminDarkAsset::register($this);
                break;
            }
            case 'classic': {
                AdminClassicAsset::register($this);
                break;
            }
            default: {
                AdminBlueAsset::register($this);
                break;
            }
        }
    }
    else {
        AdminBlueAsset::register($this);
    }
}

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
        <div class="col-md-6 col-md-offset-3">
            <div class="login-panel panel panel-danger">
                <div class="panel-heading">
                    Произошла ошибка :(
                </div>
                <div class="panel-body">
                    <br>
                    <p>
                        <?php
                        switch ($exception->statusCode) {
                            case '404': {
                                echo 'Запрашиваемой страницы не существует. Проверьте правильность набранного адреса.';
                                break;
                            }
                            case '405': {
                                echo 'Вы использовали метод, недопустимый для данного действия.';
                                break;
                            }
                            case '403': {
                                echo 'К сожалению, у вас недостаточно прав для просмотра данной страницы. Обратитесь к администратору.';
                                break;
                            }
                            case '500': {
                                echo 'Наши сайты работают идеально! Такое случается раз в сто лет, но не повезло именно вам. Мы приносим свои извинения и просим обратиться к разработчикам.';
                                break;
                            }
                            default: {
                                echo 'Что-то пошло не так...';
                                break;
                            }
                        }
                        ?>
                    </p>
                    <br>
                    <div class="text-center"><a href="<?= Yii::$app->request->referrer ?>" class="btn btn-outline btn-default">Вернуться</a></div>
                    <br>
                </div>
                <div class="panel-footer">
                    <small>Если вы считаете, что ошибка произошла по вине разработчиков, обратитесь к нам: <a href="http://gvdproject.ru" target="_blank">GVDProject</a>.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
