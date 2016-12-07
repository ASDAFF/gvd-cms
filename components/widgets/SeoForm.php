<?php

namespace app\components\widgets;

use yii\base\Widget;

class SeoForm extends Widget
{
    public $form;
    public $model;

    public function init(){
        parent::init();

    }

    public function run(){

        return $this->render('/admin/_seo_form', [
            'form' => $this->form,
            'model' => $this->model
        ]);
    }
}

?>