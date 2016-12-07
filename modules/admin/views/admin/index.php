<?php

use yii\helpers\Url;

$this->title = 'Панель управления';

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Панель управления сайтом <?= Yii::$app->params['sitename'] ?></h1>
        <p class="page-subtitle">Здесь осуществляется полное администрирование контента сайта и его пользователей, включая модераторов.</p>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
   <li class="active">Панель управления</li>
</ol>

<div class="row">
    <div class="col-md-3">

        <div class="panel panel-default">
            <div class="panel-heading"> <i class="fa fa-bar-chart-o fa-fw"></i> Donut Chart Example </div>
            <div class="panel-body">
                <div id="morris-donut-chart" data-content="<?= htmlspecialchars($pop_news) ?>">

                </div>
            </div>
            <!-- /.panel-body -->
        </div>

    </div>
</div>