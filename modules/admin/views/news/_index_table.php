<?php

use yii\helpers\Url;
use app\components\api\AccessAPI;

?>

<div id="dataTables-news_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row">
        <div class="col-sm-12">
            <table class="table  dataTable no-footer dtr-inline" id="dataTables-news" role="grid" aria-describedby="dataTables-news_info" style="width: 966px;" data-delperm="<?= AccessAPI::can('deleteNews') ?>" data-newsperm="<?= AccessAPI::can('createUpdateNews') ?>">
                <thead>
                <tr role="row">
                    <th tabindex="0" aria-controls="dataTables-news" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 20px;">

                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTables-news" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 139px;">
                        Название
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTables-news" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 143px;">
                        Краткое описание
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTables-news" rowspan="1" colspan="1" aria-label="status: activate to sort column ascending" style="width: 103px;">
                        Статус
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTables-news" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending" style="width: 130px;">
                        Просмотры
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTables-news" rowspan="1" colspan="1" aria-label="status: activate to sort column ascending" style="width: 103px;">
                        Дата
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($news as $key => $n) { ?>
                    <tr class="<?= ($key%2) ? 'odd' : 'even' ?>" role="row" data-id="<?= $n->news_id ?>" data-publish="<?= $n->published ?>" data-popular="<?= $n->popular ?>" data-name="<?= $n->title ?>" data-url="<?= Url::to(['/news/item', 'id' => $n->news_id]) ?>">
                        <td class="manage" style="cursor: pointer;"><i class="fa fa-angle-down"></i> </td>
                        <td class="sorting_1"><?= $n->title ?></td>
                        <td><?= $n->description ?></td>
                        <td class="center">
                            <?php if ($n->published) { ?>
                                <span class="status active">опубликовано</span>
                            <?php } else { ?>
                                <span class="status inactive">не опубликовано</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?= $n->views ?>
                        </td>
                        <td>
                            <?= Yii::$app->formatter->asDate($n->date, 'php:d.m.Y H:i') ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>