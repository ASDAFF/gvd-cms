<?php

namespace app\modules\admin\controllers;

use app\components\api\AccessAPI;
use app\modules\gvd_user\models\UserAvatarForm;
use app\modules\gvd_user\models\UserDataForm;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use app\modules\gvd_user\models\User;
use app\modules\gvd_user\models\UserForm;
use yii\filters\VerbFilter;
use app\modules\admin\models\RoleForm;
use app\modules\admin\models\PermissionsForm;
use app\modules\admin\models\UserRoleUpdation;
use app\modules\gvd_user\models\ChangePasswordForm;
use app\modules\admin\models\UserAdminTheme;


class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create-permission', 'delete'],
                        'allow' => true,
                        'roles' => ['root'],
                    ],
                    // Профиль пользователя
                    [
                        'actions' => ['profile', 'change-avatar', 'get-avatar'],
                        'allow' => true,
                        'roles' => ['accessAdmin']
                    ],
                    // Просмотр пользователей
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['viewUsers']
                    ],
                    // Добавление пользователя
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['createUser']
                    ],
                    // Просмотр списка ролей
                    [
                        'actions' => ['roles'],
                        'allow' => true,
                        'roles' => ['viewRoles'],
                    ],
                    // Добавление роли пользователей
                    [
                        'actions' => ['create-role'],
                        'allow' => true,
                        'roles' => ['createRole']
                    ],
                    // Редактирование ролей
                    [
                        'actions' => ['update-role'],
                        'allow' => true,
                        'roles' => ['updateRole'],
                    ],
                    // Раздача ролей пользователям
                    [
                        'actions' => ['change-role'],
                        'allow' => true,
                        'roles' => ['changeUserRole'],
                    ],
                    // Смена статуса пользователя
                    [
                        'actions' => ['change-status'],
                        'allow' => true,
                        'roles' => ['changeUserStatus'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'change-role' => ['post'],
                    'change-status' => ['post'],
                    'delete' => ['post'],
                    'change-avatar' => ['post'],
                    'get-avatar' => ['post']
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'change-avatar' => [
                'class' => 'app\modules\gvd_user\actions\ChangeAvatarAction',
            ],
            'get-avatar' => [
                'class' => 'app\modules\gvd_user\actions\GetAvatarAction',
            ],
        ];
    }

    public function beforeAction($action)
    {

        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        return parent :: beforeAction($action);
    }

    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        $users = User::find()->all();

        return $this->render('index', [
            'users' => $users
        ]);
    }

    public function actionProfile() {
        $profile = User::findOne(['id' => Yii::$app->user->id]);
        $pass_form = new ChangePasswordForm();

        $data_form = new UserDataForm();
        $data_form->SetData();

        $ava_form = new UserAvatarForm();

        $themes = [
            'blue' => 'Тёмно-синяя',
            'classic' => 'Классическая',
            'white' => 'Светлая',
            'dark' => 'Тёмная'
        ];
        $theme_form = UserAdminTheme::findOne(['user_id' => Yii::$app->user->id]);
        if (!$theme_form)
            $theme_form = new UserAdminTheme();

        if ($data_form->load(Yii::$app->request->post()) && $data_form->ChangeData()) {
            Yii::$app->session->setFlash('data_changed', 'Новые данные успешно сохранены.');
            $data_form = new UserDataForm();
            $data_form->SetData();
        }
        if ($pass_form->load(Yii::$app->request->post()) && $pass_form->changePassword()) {
            Yii::$app->session->setFlash('password_changed', 'Пароль был успешно изменён.');
            $pass_form = new ChangePasswordForm();
        }
        if ($theme_form->load(Yii::$app->request->post())) {
            $theme_form->save();
            Yii::$app->cache->delete('user_admin_theme_'.Yii::$app->user->id);
        }

        return $this->render('profile', [
            'profile' => $profile,
            'pass_form' => $pass_form,
            'theme_form' => $theme_form,
            'themes' => $themes,
            'data_form' => $data_form,
            'ava_form' => $ava_form
        ]);
    }

    public function actionRoles() {
        $roles = Yii::$app->authManager->getRoles();

        return $this->render('roles', [
            'all_roles' => $roles
        ]);
    }

    public function actionCreate() {
        Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex,nofollow']);

        $model = new UserForm();

        if ($model->load(Yii::$app->request->post())) {
            $id = $model->Adduser();
            if ($id) {
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionView($id) {
        if (Yii::$app->user->id == $id && !Yii::$app->user->can('root')) {
            return $this->redirect(['/admin/user/profile']);
        }
        $u = User::findOne(['id' => $id]);
        $roles = Yii::$app->authManager->getRoles();

        return $this->render('view', [
            'u' => $u,
            'roles' => $roles
        ]);
    }

    public function actionDelete() {
        $id = Yii::$app->request->post('id');
        if ($id != 1) {
            $u = User::findOne(['id' => $id]);
            $roles = Yii::$app->authManager->getRolesByUser($id);
            foreach ($roles as $role) {
                $userRole = Yii::$app->authManager->getRole($role->name);
                Yii::$app->authManager->revoke($userRole, $id);
            }
             if ($u->delete()) Yii::$app->session->set('user_manage_flash', 'Пользователь успешно удален.');
        }
        else {
            Yii::$app->session->set('user_manage_flash', 'Невозможно удалить пользователя с ролью Разработчика!');
        }
        return $this->redirect(['index']);
    }

    public function actionChangeStatus() {
        $id = Yii::$app->request->post('id');
        if ($id != 1) {
            $u = User::findOne(['id' => $id]);
            if ($u->status == 10) {
                $u->status = 0;
            } else $u->status = 10;
            $u->save();
        }
    }

    public function actionChangeRole() {
        $user = Yii::$app->request->post('user');
        $role = Yii::$app->request->post('role');
        $u = User::findOne(['id' => $user]);
        if ($user != 1) {
            $userRole = Yii::$app->authManager->getRole($role);
            if ($u->hasRole($role)) {
                Yii::$app->authManager->revoke($userRole, $user);
            } else {
                Yii::$app->authManager->assign($userRole, $user);
            }
        }
    }

    public function actionCreateRole() {
        $model = new RoleForm();

        if ($model->load(Yii::$app->request->post())) {
            $role = $model->AddRole();
            if ($role) {
                $upd = new UserRoleUpdation();
                $upd->role_name = $role;
                $upd->user_id = Yii::$app->user->id;
                $upd->action = 'create';
                $upd->save();
                return $this->redirect(['update-role', 'name' => $role]);
            }
        }

        return $this->render('create_role', [
            'model' => $model
        ]);
    }

    public function actionUpdateRole($name) {
        $model = new PermissionsForm();
        $role = Yii::$app->authManager->getRole($name);

        if ($name != 'root' || AccessAPI::can('root')) {

            foreach (array_keys($model->toArray()) as $r) {
                $model->{$r} = $this->hasAssign($role, $r);
            }

            if ($model->load(Yii::$app->request->post())) {
                if (!Yii::$app->user->can($name) || Yii::$app->user->can('root')) {
                    $model->ReAssign($name);

                    $upd = new UserRoleUpdation();
                    $upd->role_name = $name;
                    $upd->user_id = Yii::$app->user->id;
                    $upd->action = 'update';
                    $upd->save();

                    Yii::$app->session->setFlash('updated_role', 'Права роли успешно изменены.');
                    return $this->refresh();
                }
            }
        }

        return $this->render('update_role', [
            'model' => $model,
            'role' => $role
        ]);
    }
    
    private function hasAssign($role, $perm_name) {
        $permission = Yii::$app->authManager->getPermission($perm_name);
        return Yii::$app->authManager->hasChild($role, $permission);
    }

    public function actionCreatePermission() {
        $model = new RoleForm();

        if ($model->load(Yii::$app->request->post())) {
            $role = $model->AddPermission();
            if ($role) {
                return $this->redirect(['roles']);
            }
        }

        return $this->render('create_permission', [
            'model' => $model
        ]);
    }
}
