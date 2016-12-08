<?php

namespace app\modules\admin\models;

use app\modules\gvd_user\models\User;
use Yii;
use yii\base\Model;

class PermissionsForm extends Model
{
    public $accessAdmin;

    // Пользователи

    public $viewUsers; // просмотр информации пользователей
    public $createUser; // добавление пользователя

    // Роли

    public $viewRoles; // просмотр списка ролей
    public $createRole; // добавление роли пользователей
    public $updateRole; // редактирование ролей

    // Редактирование пользователей

    public $changeUserRole; // раздача ролей пользователям
    public $changeUserStatus; // смена статуса пользователя

    // Новости

    public $viewNews; // просмотр новостей
    public $createUpdateNews; // добавление новости
    public $deleteNews; // удаление новости

    // Фотогалерея

    public $viewPhoto; // просмотр фотогалереи
    public $createUpdatePhoto; // добавление и редактирование категорий и фото
    public $deletePhoto; // удаление категорий и фото

    // Видеогалерея

    public $viewVideo; // просмотр видеогалереи
    public $createUpdateVideo; // добавление и редактирование категорий и видео
    public $deleteVideo; // удаление категорий и видео

    // Страницы

    public $viewPages; // просмотр страниц
    public $createUpdatePages; // добавление и редактирование страниц
    public $deletePages; // удаление страницы

    // Слайдеры

    public $viewSliders; // просмотр слайдеров
    public $createUpdateSliders; // добавление и редактирование слайдов
    public $deleteSliders; // удаление слайда

    // Текстовая информация

    public $viewTexts; // просмотр текстовой информации
    public $updateTexts; // редактирование текстовой информации

    // Логи

    public $enterLog; // просмотр логов входов в панель управления
    public $newsLog; // просмотр логов работы с новостями
    public $videoLog; // просмотр логов работы с видео
    public $videoCategoryLog; // просмотр логов работы с категориями видео
    public $photoLog; // просмотр логов работы с фото
    public $photoCategoryLog; // просмотр логов работы с категориями фото
    public $pagesLog; // просмотр логов работы со страницами
    public $slidersLog; // просмотр логов работы со слайдами
    public $textsLog; // просмотр логов работы с текстовой информацией


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [array_keys($this->toArray()), 'integer'],

            [array_keys($this->toArray()), 'default', 'value' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'accessAdmin' => 'Доступ в панель управления',

            'viewUsers' => 'Просмотр информации пользователей',
            'createUser' => 'Добавление пользователя',

            'viewRoles' => 'Просмотр списка ролей',
            'createRole' => 'Добавление роли пользователей',
            'updateRole' => 'Редактирование ролей',

            'changeUserStatus' => 'Смена статуса пользователя',
            'changeUserRole' => 'Раздача ролей пользователям',

            'viewNews' => 'Просмотр новостей',
            'createUpdateNews' => 'Добавление и редактирование новости',
            'deleteNews' => 'Удаление новости',

            'viewPhoto' => 'Просмотр фотогалереи',
            'createUpdatePhoto' => Yii::$app->getModule('photo')->categories ? 'Добавление и редактирование категорий и фото' : 'Добавление и редактирование фото',
            'deletePhoto' => Yii::$app->getModule('photo')->categories ? 'Удаление категорий и фото' : 'Удаление фото',

            'viewVideo' => 'Просмотр видеогалереи',
            'createUpdateVideo' => Yii::$app->getModule('video')->categories ? 'Добавление и редактирование категорий и видео' : 'Добавление и редактирование видео',
            'deleteVideo' => Yii::$app->getModule('video')->categories ? 'Удаление категорий и видео' : 'Удаление видео',

            'viewPages' => 'Просмотр страниц',
            'createUpdatePages' => 'Добавление и редактирование страниц',
            'deletePages' => 'Удаление страницы',

            'viewSliders' => 'Просмотр слайдеров',
            'createUpdateSliders' => 'Добавление и редактирование слайдов',
            'deleteSliders' => 'Удаление слайда',

            'viewTexts' => 'Просмотр текстовой информации',
            'updateTexts' => 'Редактирование текстовой информации',

            'enterLog' => 'Просмотр логов входов в панель управления',
            'newsLog' => 'Просмотр логов работы с новостями',
            'videoLog' => 'Просмотр логов работы с видео',
            'videoCategoryLog' => 'Просмотр логов работы с категориями видео',
            'photoLog' => 'Просмотр логов работы с фото',
            'photoCategoryLog' => 'Просмотр логов работы с категориями фото',
            'pagesLog' => 'Просмотр логов работы со страницами',
            'slidersLog' => 'Просмотр логов работы со слайдами',
            'textsLog' => 'Просмотр логов работы с текстовой информацией'
        ];
    }

    public function ReAssign($role) {
        $role = Yii::$app->authManager->getRole($role);
        $users = User::find()->all();

        foreach (array_keys($this->toArray()) as $r) {
            $this->ReAssign_item($role, $r, $this->{$r});
            foreach ($users as $u) {
                Yii::$app->cache->delete('user_'.$u->primaryKey.'_perm_'.$r);
            }
        }
    }

    private function ReAssign_item($role, $permission, $value) {
        $permission = Yii::$app->authManager->getPermission($permission);
        if (Yii::$app->authManager->hasChild($role, $permission)) {
            if (!$value)
                Yii::$app->authManager->removeChild($role, $permission);
        }
        else if ($value) {
            Yii::$app->authManager->addChild($role, $permission);
        }
    }
}
