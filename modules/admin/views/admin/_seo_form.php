<?php 

use yii\widgets\ActiveForm;

?>

<div class="row">
    <div class="col-md-12">
        <div class="panel-group accordian" id="accordian">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title"> <a aria-expanded="false" data-toggle="collapse" data-parent="#accordian" href="#collapseOne" class="collapsed">SEO атрибуты <span class="fa fa-angle-down pull-right"></span></a> </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?= $form->field($model, 'seo_title')->textInput() ?>
                        <?= $form->field($model, 'seo_keywords')->textInput() ?>
                        <?= $form->field($model, 'seo_description')->textarea() ?>
                        <?= $form->field($model, 'seo_robots')->dropDownList([
                            'noindex, nofollow' => 'noindex, nofollow',
                            'noindex, follow' => 'noindex, follow',
                            'index, nofollow' => 'index, nofollow',
                            'index, follow' => 'index, follow',
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br>