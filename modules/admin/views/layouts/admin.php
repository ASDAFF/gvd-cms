<?php

use app\modules\admin\assets\AdminBlueAsset;
use app\modules\admin\assets\AdminClassicAsset;
use app\modules\admin\assets\AdminDarkAsset;
use app\modules\admin\assets\AdminWhiteAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\admin\models\UserAdminTheme;
use app\modules\news\News;
use app\components\api\AccessAPI;

if (Yii::$app->user->isGuest) {
    AdminBlueAsset::register($this);
}
else {
    $user_theme = Yii::$app->cache->get('user_admin_theme_'.Yii::$app->user->id);
    if (!$user_theme) {
        $user_theme = UserAdminTheme::findOne(['user_id' => Yii::$app->user->id]);
        Yii::$app->cache->set('user_admin_theme_'.Yii::$app->user->id, $user_theme);
    }
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
    <link rel="shortcut icon" href="/admin_favicon.ico" type="image/x-icon" />
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- loader -->
<div class="loader"><h1 class="loadingtext">GVDProject</h1><p>Загрузка информации...</p><br><img src="/img/admin/loader2.gif" alt=""> </div>
<!-- loader ends -->

<div id="wrapper">
    <div class="navbar-default sidebar" >
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" > <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a class="navbar-brand" href="http://gvdproject.ru" target="_blank">GVDProject</a> </div>
        <div class="clearfix"></div>
        <div class="sidebar-nav navbar-collapse">

            <!-- user profile pic -->
            <div class="userprofile text-center">
                <div class="userpic"> <img src="<?= Yii::$app->user->identity->avatar ?>" alt="" class="userpicimg"> <a href="<?= Url::to(['/admin/user/profile']) ?>" class="btn btn-primary settingbtn"><i class="fa fa-gear"></i></a> </div>
                <h3 class="username"><?= Yii::$app->user->identity->name ? Yii::$app->user->identity->name : Yii::$app->user->identity->email ?></h3>
                <p>
                    <?php foreach(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id) as $key => $r) {
                        echo $key != 0 ? ', ' : null;
                        echo $r->description;
                    } ?>
                </p>
            </div>
            <div class="clearfix"></div>
            <!-- user profile pic -->

            <ul class="nav" id="side-menu">
                <li> <a href="<?= Url::to(['/admin']) ?>"<?= Yii::$app->controller->id == 'admin' ? ' class="active"' : null ?>><i class="fa fa-dashboard fa-fw"></i> Панель управления</a> </li>
                <li>
                    <?php if (AccessAPI::can('viewUsers')) { ?>
                    <a href="<?= Url::to(['/admin/user/index']) ?>"<?= Yii::$app->controller->id == 'user' ? ' class="active"' : null ?>><i class="fa fa-user fa-fw"></i> Пользователи</a>
                    <?php } else { ?>
                        <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!"><i class="fa fa-user fa-fw"></i> Пользователи</a>
                    <?php } ?>
                </li>
                <?php if (Yii::$app->getModule('news')->status) { ?>
                <li>
                    <?php if (AccessAPI::can('viewNews')) { ?>
                    <a href="<?= Url::to(['/admin/news/index']) ?>"<?= Yii::$app->controller->id == 'news' ? ' class="active"' : null ?>><i class="fa fa-newspaper-o fa-fw"></i> Новости</a>
                    <?php } else { ?>
                        <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!"><i class="fa fa-newspaper-o fa-fw"></i> Новости</a>
                    <?php } ?>
                </li>
                <?php } ?>
                <?php if (Yii::$app->getModule('photo')->status) { ?>
                    <li>
                        <?php if (AccessAPI::can('viewPhoto')) { ?>
                            <a href="<?= Url::to(['/admin/photo/index']) ?>"<?= Yii::$app->controller->id == 'photo' ? ' class="active"' : null ?>><i class="fa fa-camera fa-fw"></i> Фотогалерея</a>
                        <?php } else { ?>
                            <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!"><i class="fa fa-camera fa-fw"></i> Фотогалерея</a>
                        <?php } ?>
                    </li>
                <?php } ?>
                <?php if (Yii::$app->getModule('video')->status) { ?>
                <li>
                    <?php if (AccessAPI::can('viewVideo')) { ?>
                    <a href="<?= Url::to(['/admin/video/index']) ?>"<?= Yii::$app->controller->id == 'video' ? ' class="active"' : null ?>><i class="fa fa-play-circle fa-fw"></i> Видеогалерея</a>
                    <?php } else { ?>
                        <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!"><i class="fa fa-play-circle fa-fw"></i> Видеогалерея</a>
                    <?php } ?>
                </li>
                <?php } ?>
                <?php if (Yii::$app->getModule('pages')->status) { ?>
                    <li>
                        <?php if (AccessAPI::can('viewPages')) { ?>
                            <a href="<?= Url::to(['/admin/pages/index']) ?>"<?= Yii::$app->controller->id == 'pages' ? ' class="active"' : null ?>><i class="fa fa-file-text fa-fw"></i> Страницы</a>
                        <?php } else { ?>
                            <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!"><i class="fa fa-file-text fa-fw"></i> Страницы</a>
                        <?php } ?>
                    </li>
                <?php } ?>
                <li> <a href="javascript:void(0)" class="menudropdown"><i class="fa fa-male fa-fw"></i> Логи<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <?php if (AccessAPI::can('enterLog')) { ?>
                            <a href="<?= Url::to(['/admin/log/enter']) ?>">Входы</a>
                            <?php } else { ?>
                                <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Входы</a>
                            <?php } ?>
                        </li>
                        <?php if (Yii::$app->getModule('news')->status) { ?>
                        <li>
                            <?php if (AccessAPI::can('newsLog')) { ?>
                            <a href="<?= Url::to(['/admin/log/news']) ?>">Работа с новостями</a>
                            <?php } else { ?>
                                <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Работа с новостями</a>
                            <?php } ?>
                        </li>
                        <?php } ?>
                        <?php if (Yii::$app->getModule('video')->status) { ?>
                            <li>
                                <?php if (Yii::$app->getModule('video')->categories) { ?>
                                    <a href="javascript:void(0)" class="menudropdown2">Видеогалерея <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level" style="display: none;">
                                        <li>
                                        <?php if (AccessAPI::can('videoCategoryLog')) { ?>
                                            <a href="<?= Url::to(['/admin/log/video-category']) ?>">Работа с категориями</a>
                                        <?php } else { ?>
                                            <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Работа с категориями</a>
                                        <?php } ?>
                                        </li>
                                        <li>
                                            <?php if (AccessAPI::can('videoLog')) { ?>
                                            <a href="<?= Url::to(['/admin/log/video']) ?>">Работа с видео</a>
                                            <?php } else { ?>
                                                <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Работа с видео</a>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                <?php } else { ?>
                                    <?php if (AccessAPI::can('videoLog')) { ?>
                                        <a href="<?= Url::to(['/admin/log/video']) ?>">Работа с видео</a>
                                    <?php } else { ?>
                                        <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Работа с видео</a>
                                    <?php } ?>
                                <?php } ?>
                                <!-- /.nav-third-level -->
                            </li>
                        <?php } ?>
                        <?php if (Yii::$app->getModule('photo')->status) { ?>
                            <li>
                                <?php if (Yii::$app->getModule('photo')->categories) { ?>
                                    <a href="javascript:void(0)" class="menudropdown2">Фотогалерея <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level" style="display: none;">
                                        <li>
                                            <?php if (AccessAPI::can('photoCategoryLog')) { ?>
                                                <a href="<?= Url::to(['/admin/log/photo-category']) ?>">Работа с категориями</a>
                                            <?php } else { ?>
                                                <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Работа с категориями</a>
                                            <?php } ?>
                                        </li>
                                        <li>
                                            <?php if (AccessAPI::can('photoLog')) { ?>
                                                <a href="<?= Url::to(['/admin/log/photo']) ?>">Работа с фото</a>
                                            <?php } else { ?>
                                                <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Работа с фото</a>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                <?php } else { ?>
                                    <?php if (AccessAPI::can('photoLog')) { ?>
                                        <a href="<?= Url::to(['/admin/log/photo']) ?>">Работа с фото</a>
                                    <?php } else { ?>
                                        <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Работа с фото</a>
                                    <?php } ?>
                                <?php } ?>
                                <!-- /.nav-third-level -->
                            </li>
                        <?php } ?>
                        <?php if (Yii::$app->getModule('pages')->status) { ?>
                            <li>
                                <?php if (AccessAPI::can('pagesLog')) { ?>
                                    <a href="<?= Url::to(['/admin/log/pages']) ?>">Страницы</a>
                                <?php } else { ?>
                                    <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Страницы</a>
                                <?php } ?>
                            </li>
                        <?php } ?>
                        <li>
                            <a href="javascript:void(0)" class="menudropdown2">Элементы сайта <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level" style="display: none;">
                                <?php if (Yii::$app->getModule('sliders')->status) { ?>
                                <li>
                                    <?php if (AccessAPI::can('slidersLog')) { ?>
                                        <a href="<?= Url::to(['/admin/log/slider-item']) ?>">Работа со слайдами</a>
                                    <?php } else { ?>
                                        <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="У вас недостаточно прав!">Работа со слайдами</a>
                                    <?php } ?>
                                </li>
                                <?php } ?>
                            </ul>
                            <!-- /.nav-third-level -->
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li> <a href="javascript:void(0)" class="menudropdown"><i class="fa fa-cubes fa-fw"></i> Элементы сайта<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <?php if (Yii::$app->getModule('sliders')->status) { ?>
                                <a href="<?= Url::to(['/admin/sliders/index']) ?>">Слайдеры</a>
                            <?php } ?>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <?php if (AccessAPI::can('root')) { ?>
                <li> <a href="<?= Url::to(['/admin/settings/index']) ?>"<?= Yii::$app->controller->id == 'settings' ? ' class="active"' : null ?>><i class="fa fa-gears fa-fw"></i> Настройки</a> </li>
                <?php } ?>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->

    <div id="page-wrapper">
        <div class="row">
            <nav class="navbar navbar-default navbar-static-top" style="margin-bottom: 0">
                <button class="menubtn pull-left btn "><i class="glyphicon  glyphicon-th"></i></button>
                <div class="searchwarpper">
                    <div class="input-group searchglobal">
                        <input type="text" class="form-control" placeholder="Search for..." autofocus>
            <span class="input-group-btn">
            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
            </span> </div>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)"> <i class="fa fa-envelope fa-fw"></i> </a>
                        <ul class="dropdown-menu dropdown-messages">
                            <li> <a href="javascript:void(0)">
                                    <div> <strong>John Smith</strong> <span class="pull-right text-muted"> <em>Yesterday</em> </span> </div>
                                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                                </a> </li>
                            <li> <a href="javascript:void(0)">
                                    <div> <strong>John Smith</strong> <span class="pull-right text-muted"> <em>Yesterday</em> </span> </div>
                                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                                </a> </li>
                            <li> <a href="javascript:void(0)">
                                    <div> <strong>John Smith</strong> <span class="pull-right text-muted"> <em>Yesterday</em> </span> </div>
                                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                                </a> </li>
                            <li> <a class="text-center" href="javascript:void(0)"> <strong>Read All Messages</strong> <i class="fa fa-angle-right"></i> </a> </li>
                        </ul>
                        <!-- /.dropdown-messages -->
                    </li>
                    <!-- /.dropdown -->
                    <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)"> <i class="fa fa-tasks fa-fw"></i> <span class="count">9+</span> </a>
                        <ul class="dropdown-menu dropdown-tasks">
                            <li> <a href="javascript:void(0)">
                                    <div>
                                        <p> <strong>Task 1</strong> <span class="pull-right text-muted">40% Complete</span> </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                        </div>
                                    </div>
                                </a> </li>
                            <li> <a href="javascript:void(0)">
                                    <div>
                                        <p> <strong>Task 2</strong> <span class="pull-right text-muted">20% Complete</span> </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"> <span class="sr-only">20% Complete</span> </div>
                                        </div>
                                    </div>
                                </a> </li>
                            <li> <a href="javascript:void(0)">
                                    <div>
                                        <p> <strong>Task 3</strong> <span class="pull-right text-muted">60% Complete</span> </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"> <span class="sr-only">60% Complete (warning)</span> </div>
                                        </div>
                                    </div>
                                </a> </li>
                            <li> <a href="javascript:void(0)">
                                    <div>
                                        <p> <strong>Task 4</strong> <span class="pull-right text-muted">80% Complete</span> </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%"> <span class="sr-only">80% Complete (danger)</span> </div>
                                        </div>
                                    </div>
                                </a> </li>
                            <li> <a class="text-center" href="javascript:void(0)"> <strong>See All Tasks</strong> <i class="fa fa-angle-right"></i> </a> </li>
                        </ul>
                        <!-- /.dropdown-tasks -->
                    </li>
                    <!-- /.dropdown -->
                    <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)"> <i class="fa fa-bell fa-fw"></i> <span class="count">1</span> </a>
                        <ul class="dropdown-menu dropdown-alerts">
                            <li> <a href="javascript:void(0)">
                                    <div> <i class="fa fa-comment fa-fw"></i> New Comment <span class="pull-right text-muted small">4 minutes ago</span> </div>
                                </a> </li>
                            <li> <a href="javascript:void(0)">
                                    <div> <i class="fa fa-twitter fa-fw"></i> 3 New Followers <span class="pull-right text-muted small">12 minutes ago</span> </div>
                                </a> </li>
                            <li> <a href="javascript:void(0)">
                                    <div> <i class="fa fa-envelope fa-fw"></i> Message Sent <span class="pull-right text-muted small">4 minutes ago</span> </div>
                                </a> </li>
                            <li> <a href="javascript:void(0)">
                                    <div> <i class="fa fa-tasks fa-fw"></i> New Task <span class="pull-right text-muted small">4 minutes ago</span> </div>
                                </a> </li>
                            <li> <a href="javascript:void(0)">
                                    <div> <i class="fa fa-upload fa-fw"></i> Server Rebooted <span class="pull-right text-muted small">4 minutes ago</span> </div>
                                </a> </li>
                            <li> <a class="text-center" href="javascript:void(0)"> <strong>See All Alerts</strong> <i class="fa fa-angle-right"></i> </a> </li>
                        </ul>
                        <!-- /.dropdown-alerts -->
                    </li>
                    <!-- /.dropdown -->
                    <li class="dropdown"> <a class="dropdown-toggle userdd" data-toggle="dropdown" href="javascript:void(0)">
                            <div class="userprofile small "> <span class="userpic"> <img src="<?= Yii::$app->user->identity->avatar ?>" alt="" class="userpicimg"> </span>
                                <div class="textcontainer">
                                    <h3 class="username"><?= Yii::$app->user->identity->name ? Yii::$app->user->identity->name : Yii::$app->user->identity->email ?></h3>
                                    <p>
                                        <?php foreach(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id) as $key => $r) {
                                            echo $key != 0 ? ', ' : null;
                                            echo $r->description;
                                        } ?>
                                    </p>
                                </div>
                            </div>
                            <i class="caret"></i> </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li> <a href="<?= Url::to(['/admin/user/profile']) ?>"><i class="fa fa-user fa-fw"></i> Профиль</a> </li>
                            <li> <a href="<?= Url::to(['/admin/admin/logout']) ?>"><i class="fa fa-sign-out fa-fw"></i> Выход</a> </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
                <!-- /.navbar-top-links -->

            </nav>
        </div>

        <?= $content ?>

    </div>
    <!-- /#page-wrapper -->

</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
