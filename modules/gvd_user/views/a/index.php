<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = Yii::t('easyii', 'Пользователи');

?>

<?= $this->render('_menu') ?>

<?php if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th width="50">#</th>
            <th>Email</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th width="30"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data->models as $user) : ?>
            <tr>
                <td><?= $user->id ?></td>
                <td><a href="<?= Url::to(['/admin/user/a/view', 'id' => $user->id]) ?>"><?= $user->email ?></a></td>
                <td><?= $user->name ?></td>
                <td><?= $user->last_name ?></td>
                <td><a href="<?= Url::to(['/admin/user/a/delete', 'id' => $user->id]) ?>" class="glyphicon glyphicon-remove confirm-delete" title="<?= Yii::t('easyii', 'Delete item') ?>"></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <?= yii\widgets\LinkPager::widget([
            'pagination' => $data->pagination
        ]) ?>
    </table>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>