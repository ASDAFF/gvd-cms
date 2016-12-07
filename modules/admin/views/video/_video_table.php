<?php

use yii\helpers\Url;
use app\components\api\AccessAPI;

?>

<div id="dataTables-video_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row">
        <div class="col-sm-12">
            <table class="table  dataTable no-footer dtr-inline" id="dataTables-video" role="grid" aria-describedby="dataTables-video_info" style="width: 966px;" data-delperm="<?= AccessAPI::can('deleteVideo') ?>" data-videoperm="<?= AccessAPI::can('createUpdateVideo') ?>">
                <thead>
                <tr role="row">
                    <th tabindex="0" aria-controls="dataTables-video" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 20px;">

                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTables-video" rowspan="1" colspan="1" aria-label="User : activate to sort column descending" style="width: 139px;">
                        Название
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTables-video" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 143px;">
                        URL
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="dataTables-video" rowspan="1" colspan="1" aria-label="status: activate to sort column ascending" style="width: 103px;">
                        Дата
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($videos as $key => $v) { ?>
                    <tr class="<?= ($key%2) ? 'odd' : 'even' ?>" role="row" data-id="<?= $v->video_id ?>" data-name="<?= $v->title ?>">
                        <td class="manage" style="cursor: pointer;"><i class="fa fa-angle-down"></i> </td>
                        <td class="sorting_1"><?= $v->title ?></td>
                        <td><a href="<?= $v->url ?>" target="_blank"><?= $v->url ?></a></td>
                        <td>
                            <?= Yii::$app->formatter->asDate($v->time, 'php:d.m.Y H:i') ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>