<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "user_admin_theme".
 *
 * @property integer $user_theme_id
 * @property integer $user_id
 * @property string $theme
 */
class UserAdminTheme extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_admin_theme';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'theme'], 'required'],
            [['user_id'], 'integer'],
            [['theme'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_theme_id' => 'User Theme ID',
            'user_id' => 'User ID',
            'theme' => 'Theme',
        ];
    }
}
