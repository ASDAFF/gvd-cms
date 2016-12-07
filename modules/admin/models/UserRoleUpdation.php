<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "user_role_updation".
 *
 * @property integer $user_role_upd_id
 * @property string $role_name
 * @property integer $user_id
 * @property string $action
 * @property string $time
 */
class UserRoleUpdation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_role_updation';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            $this->time = time();

            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_name', 'user_id', 'action'], 'required'],
            [['user_id', 'time'], 'integer'],
            [['role_name', 'action'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_role_upd_id' => 'User Role Upd ID',
            'role_name' => 'Role Name',
            'user_id' => 'User ID',
            'action' => 'Action',
            'time' => 'Time',
        ];
    }
}
