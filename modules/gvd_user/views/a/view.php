<?php

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->title = 'Пользователь '.$model->name.' ('.$model->email.')';

?>

<?= $this->render('_menu') ?>


    <?= $model->role == 20 ? '<a href="'.Url::to(['changerole', 'id' => $model->id]).'" class="btn btn-primary"><i class="glyphicon glyphicon-lock"></i>&nbsp;&nbsp;Отобрать права администрирования</a>' : '<a href="'.Url::to(['changerole', 'id' => $model->id]).'" class="btn btn-success"><i class="glyphicon glyphicon-wrench"></i>&nbsp;&nbsp;Сделать администратором</a>' ?>
    <?= $model->status == 0 ? '<a href="'.Url::to(['changestatus', 'id' => $model->id]).'" class="btn btn-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;&nbsp;Разбанить</a>' : '<a href="'.Url::to(['changestatus', 'id' => $model->id]).'" class="btn btn-warning"><i class="glyphicon glyphicon-ban-circle"></i>&nbsp;&nbsp;Забанить</a>' ?>
    <a href="<?= Url::to(['delete', 'id' => $model->id]) ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;Удалить</a>
    <br><br>


    <div class="row">
        <div class="col-xs-12 col-md-7">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'email:ntext',
                    'name:ntext',
                    'last_name:ntext',
                    'phone:ntext',
                    [
                        'label' => 'Статус',
                        'value' => $model->status == 10 ? 'активен' : 'забанен',
                    ],
                    [
                        'label' => 'Права',
                        'value' => $model->role == 20 ? 'администратор' : 'пользователь',
                    ],
                ],
            ]) ?>

        </div>

    </div>