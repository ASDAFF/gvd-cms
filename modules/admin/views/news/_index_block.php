<?php

use yii\helpers\Url;
use app\components\api\AccessAPI;
use app\components\widgets\LinkPagerAdmin;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin(['method' => 'get']); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($search, 'sort')->dropDownList([
            'dateDesc' => 'по дате (от последней)',
            'dateAsc' => 'по дате (от первой)',
            'nameAsc' => 'по названию (А-Я)',
            'nameDesc' => 'по названию (Я-А)'
        ]) ?>
    </div>
    <div class="col-md-4 text-center">
        <?= Html::submitButton('<i class="fa fa-search"></i>&nbsp;&nbsp;Применить', ['class' => 'btn btn-info']) ?>
        <a href="<?= Url::to(['/admin/news/index']) ?>" class="btn btn-danger"><i class="fa fa-times"></i>&nbsp;&nbsp;Очистить</a>
    </div>
    <div class="col-md-4">
        <?= $form->field($search, 'name')->textInput() ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<div class="row">
    <?php foreach ($news as $n) { ?>
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12 text-left">
                            <div class="colorlight pull-left">
                                <strong><?= Yii::$app->formatter->asDate($n->date, 'php:d.m.Y H:i') ?></strong><br>
                                <i>Просмотров: <?= $n->views ?></i>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="dotbtn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="dots"></span> </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="<?= Url::to(['/news/item', 'id' => $n->news_id]) ?>" target="_blank">Посмотреть на сайте</a> </li>
                                        <li>
                                            <?php if (AccessAPI::can('createUpdateNews')) { ?>
                                                <a href="" data-id="<?= $n->news_id ?>" class="publishing_news"><?= $n->published ? 'Убрать из публикации' : 'Опубликовать' ?></a>
                                            <?php } else { ?>
                                                <a data-toggle="tooltip" data-placement="right" title="" data-original-title="У вас недостаточно прав!"><?= $n->published ? 'Убрать из публикации' : 'Опубликовать' ?></a>
                                            <?php } ?>
                                        </li>
                                        <li>
                                            <?php if (AccessAPI::can('createUpdateNews')) { ?>
                                                <a href="" data-id="<?= $n->news_id ?>" class="popularing_news"><?= $n->popular ? 'Убрать из популярных' : 'Сделать популярной' ?></a>
                                            <?php } else { ?>
                                                <a data-toggle="tooltip" data-placement="right" title="" data-original-title="У вас недостаточно прав!"><?= $n->popular ? 'Убрать из популярных' : 'Сделать популярной' ?></a>
                                            <?php } ?>
                                        </li>
                                        <li>
                                            <?php if (AccessAPI::can('createUpdateNews')) { ?>
                                                <a href="<?= Url::to(['/admin/news/update', 'id' => $n->news_id]) ?>">Редактировать</a>
                                            <?php } else { ?>
                                                <a data-toggle="tooltip" data-placement="right" title="" data-original-title="У вас недостаточно прав!">Редактировать</a>
                                            <?php } ?>
                                        </li>
                                        <li>
                                        <?php if (AccessAPI::can('deleteNews')) { ?>
                                            <a href="#delete_news_modal" data-toggle="modal" data-target="delete_news_modal" data-name="<?= $n->title ?>" data-id="<?= $n->news_id ?>">Удалить</a>
                                        <?php } else { ?>
                                            <a data-toggle="tooltip" data-placement="right" title="" data-original-title="У вас недостаточно прав!">Удалить</a>
                                        <?php } ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="huge"><?= $n->title ?></div>
                            <div class="colorlight desc"><?= $n->description ?></div>
                            <br>
                        </div>
                        <div class="col-xs-12 text-center">
                            <div class="news_img">
                                <div style="background-image: url(<?= $n->coverPath ?>);"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer <?= $n->published ? 'panel-green' : 'panel-red' ?>">
                    <span class="pull-left"><?= $n->published ? 'Опубликовано' : 'Не опубликовано' ?></span>
                    <span class="pull-right"><i class="fa <?= $n->published ? 'fa-check' : 'fa-warning' ?>"></i></span>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div class="row">
    <div class="col-md-12">
        <?= LinkPagerAdmin::widget([
            'pagination' => $pages,
        ]) ?>
    </div>
</div>