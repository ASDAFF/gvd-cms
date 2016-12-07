<?php

$this->title = 'Настройки проекта | Панель управления';

use yii\helpers\Url;

$this->registerCss("
    .accordian a:hover, .accordian a:focus {
        text-decoration: none;
    }
");

?>

    <div class="row">
        <div class="col-md-12  header-wrapper" >
            <h1 class="page-header">Настройки проекта</h1>
            <p class="page-subtitle">Управление модулями и их опциями.</p>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

    <ol class="breadcrumb">
        <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
        <li class="active">Настройки проекта</li>
    </ol>

    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-primary" href=""><i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp; Обновить</a><br><br>

            <div class="panel panel-default">
                <div class="panel-heading"> <i class="fa fa-cubes fa-fw"></i> Модули </div>
                <!-- .panel-heading -->
                <div class="panel-body">
                    <div class="panel-group accordian" id="accordian">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordian" href="#collapseOne" aria-expanded="false" class="collapsed"><i class="fa fa-newspaper-o fa-fw"></i>&nbsp;&nbsp;&nbsp;Новости <span class="fa fa-angle-down pull-right"></span></a> </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    <?php foreach ($news as $it) { ?>
                                        <p><input type="checkbox" class="module_setting" data-id="<?= $it->primaryKey ?>" value="<?= $it->value ?>" <?= $it->value ? 'checked' : null ?>> <?= $it->name ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordian" href="#collapseTwo" aria-expanded="false" class="collapsed"><i class="fa fa-play-circle fa-fw"></i>&nbsp;&nbsp;&nbsp;Видеогалерея <span class="fa fa-angle-down pull-right"></span></a> </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    <?php foreach ($videos as $it) { ?>
                                        <p><input type="checkbox" class="module_setting" data-id="<?= $it->primaryKey ?>" value="<?= $it->value ?>" <?= $it->value ? 'checked' : null ?>> <?= $it->name ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordian" href="#collapseThree" aria-expanded="false" class="collapsed"><i class="fa fa-camera fa-fw"></i>&nbsp;&nbsp;&nbsp;Фотогалерея <span class="fa fa-angle-down pull-right"></span></a> </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    <?php foreach ($photos as $it) { ?>
                                        <p><input type="checkbox" class="module_setting" data-id="<?= $it->primaryKey ?>" value="<?= $it->value ?>" <?= $it->value ? 'checked' : null ?>> <?= $it->name ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordian" href="#collapseFour" aria-expanded="false" class="collapsed"><i class="fa fa-file-text fa-fw"></i>&nbsp;&nbsp;&nbsp;Страницы <span class="fa fa-angle-down pull-right"></span></a> </h4>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    <?php foreach ($pages as $it) { ?>
                                        <p><input type="checkbox" class="module_setting" data-id="<?= $it->primaryKey ?>" value="<?= $it->value ?>" <?= $it->value ? 'checked' : null ?>> <?= $it->name ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordian" href="#collapseFive" aria-expanded="false" class="collapsed"><i class="fa fa-cubes fa-fw"></i>&nbsp;&nbsp;&nbsp;Слайдеры <span class="fa fa-angle-down pull-right"></span></a> </h4>
                            </div>
                            <div id="collapseFive" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    <?php foreach ($sliders as $it) { ?>
                                        <p><input type="checkbox" class="module_setting" data-id="<?= $it->primaryKey ?>" value="<?= $it->value ?>" <?= $it->value ? 'checked' : null ?>> <?= $it->name ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- .panel-body -->
            </div>
        </div>
    </div>
