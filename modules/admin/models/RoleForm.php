<?php

namespace app\modules\admin\models;

use app\modules\gvd_user\models\User;
use Yii;
use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $description;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['description'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название (НА АНГЛИЙСКОМ!)',
            'description' => 'Описание'
        ];
    }

    public function AddRole() {
        if (!$this->validate()) {
            return false;
        }

        $role = Yii::$app->authManager->createRole($this->name);
        $role->description = $this->description;
        Yii::$app->authManager->add($role);
        return $role->name;
    }

    public function AddPermission() {
        if (!$this->validate()) {
            return false;
        }

        $permission = Yii::$app->authManager->createPermission($this->name);
        $permission->description = $this->description;
        Yii::$app->authManager->add($permission);
        return $permission->name;
    }
}
