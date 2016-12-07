<?php

namespace app\modules\pages\models;

use Yii;
use yii\base\Model;

class DataForm extends Model
{
    public $key;
    public $title;
    public $type;
    public $page_id;

    public $value;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['key', 'title', 'type', 'page_id'], 'required'],
            [['key', 'title'], 'string', 'max' => 200],
            [['type', 'value'], 'string'],
            [['page_id'], 'integer'],
            [['type'], 'in', 'range' => ['string', 'text']],

            [['value'], 'default', 'value' => null]
        ];
    }

    public function attributeLabels()
    {
        return [
            'key' => 'Метка (НА АНГЛИЙСКОМ!)',
            'title' => 'Название поля',
            'type' => 'Тип поля',
            'new_key' => 'Метка (НА АНГЛИЙСКОМ!)'
        ];
    }

    public function addField() {
        $p = Page::findOne(['page_id' => $this->page_id]);
        $fields = json_decode($p->data);
        if (!$fields) $fields = [];
        $fields->{$this->key} = [
            'title' => $this->title,
            'type' => $this->type,
            'value' => ''
        ];
        $p->data = json_encode($fields);
        return $p->save();
    }

    public function editField() {
        $p = Page::findOne(['page_id' => $this->page_id]);
        $fields = json_decode($p->data);
        $fields->{$this->key}->title = $this->title;
        $fields->{$this->key}->type = $this->type;
        $p->data = json_encode($fields);
        return $p->save();
    }
}
