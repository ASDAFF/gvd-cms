<?php

namespace app\modules\admin\models;

use yii\base\Model;

class SearchItemForm extends Model
{
    public $sort;
    public $name;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['sort'], 'in', 'range' => ['dateAsc','dateDesc','nameAsc','nameDesc']],
            [['name'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'sort' => 'Сортировать',
            'name' => 'Поиск по названию'
        ];
    }
}
