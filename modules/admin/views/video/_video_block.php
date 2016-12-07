<?php

use yii\helpers\Url;
use app\components\widgets\LinkPagerAdmin;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\api\AccessAPI;

?>

<?php $form = ActiveForm::begin(['method' => 'get']); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($search, 'sort')->dropDownList([
            'dateDesc' => 'по дате (от последнего)',
            'dateAsc' => 'по дате (от первого)',
            'nameAsc' => 'по названию (А-Я)',
            'nameDesc' => 'по названию (Я-А)'
        ]) ?>
    </div>
    <div class="col-md-4 text-center">
        <?= Html::submitButton('<i class="fa fa-search"></i>&nbsp;&nbsp;Применить', ['class' => 'btn btn-info']) ?>
        <a href="<?= Url::to(['/admin/video/view-category', 'id' => ($cat ? $cat->video_category_id : null)]) ?>" class="btn btn-danger"><i class="fa fa-times"></i>&nbsp;&nbsp;Очистить</a>
    </div>
    <div class="col-md-4">
        <?= $form->field($search, 'name')->textInput() ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<div class="row">
    <?= !$videos ? '<p>Ничего не найдено по данному запросу.</p>' : null ?>
    <?php foreach ($videos as $v) { ?>
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12 text-left">
                            <div class="colorlight pull-left">
                                <strong><?= Yii::$app->formatter->asDate($v->time, 'php:d.m.Y H:i') ?></strong>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="dotbtn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="dots"></span> </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="<?= $v->url ?>" target="_blank">Посмотреть на Youtube</a> </li>
                                        <li>
                                            <?php if (AccessAPI::can('createUpdateVideo')) { ?>
                                                <a href="<?= Url::to(['/admin/video/update-video', 'id' => $v->video_id]) ?>">Редактировать</a>
                                            <?php } else { ?>
                                                <a data-toggle="tooltip" data-placement="right" title="" data-original-title="У вас недостаточно прав!">Редактировать</a>
                                            <?php } ?>
                                        </li>
                                        <li>
                                            <?php if (AccessAPI::can('deleteVideo')) { ?>
                                                <a href="#delete_video_modal" data-toggle="modal" data-target="delete_video_modal" data-name="<?= $v->title ? $v->title : '<Без названия>' ?>" data-id="<?= $v->video_id ?>">Удалить</a>
                                            <?php } else { ?>
                                                <a data-toggle="tooltip" data-placement="right" title="" data-original-title="У вас недостаточно прав!">Удалить</a>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="huge"><?= $v->title ? $v->title : '<Без названия>' ?></div>
                            <br>
                        </div>
                        <div class="col-xs-12 text-center" style="height: 200px;">
                            <?php if ($v->cover) { ?>
                            <div class="news_img" style="background-image: url(<?= $v->cover ?>);"></div>
                            <?php } else { ?>
                                <iframe width="100%" height="200" src="<?= $v->iframeUrl ?>" frameborder="0" style="margin: 0;"></iframe>
                            <?php } ?>
                        </div>
                    </div>
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