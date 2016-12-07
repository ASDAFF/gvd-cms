<?php

$this->title = 'Редактирование слайдера | Панель управления';

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use vova07\imperavi\Widget;
use app\components\widgets\SeoForm;
use app\components\api\AccessAPI;

?>

<div class="row">
    <div class="col-md-12  header-wrapper" >
        <h1 class="page-header">Редактирование слайдера</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<ol class="breadcrumb">
    <li><a href="<?= Url::to(['/admin']) ?>">Панель управления</a></li>
    <li>Элементы сайта</li>
    <li><a href="<?= Url::to(['/admin/sliders/index']) ?>">Слайдеры</a></li>
    <li class="active">Редактирование слайдера</li>
</ol>

<?php if (AccessAPI::can('root')) { ?>
    <a class="btn btn-info" href="<?= Url::to(['/admin/pages/data', 'id' => $model->primaryKey]) ?>"><i class="fa fa-tasks"></i>&nbsp;&nbsp;&nbsp; Поля</a>
<?php } ?>

<div class="row">
    <div class="col-md-12">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'slider_key')->textInput() ?>
        <?= $form->field($model, 'title')->textInput() ?>

        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>