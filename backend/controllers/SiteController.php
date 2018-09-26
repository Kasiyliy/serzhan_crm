<?php
namespace backend\controllers;

use backend\models\Roles;
use Yii;
use backend\models\Users;
use yii\web\Controller;
use yii\web\Response;
use backend\components\Helpers;
use backend\models\UsersRoles;



class SiteController extends Controller
{

    public function behaviors()
    {
        return [];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAuthentication() {
        Helpers::CheckAuth("redirect", "/profile/");
        return $this->render('index');
    }


    public function actionLogin() {
        if (Yii::$app->request->isAjax) {

               $profile = Users::find()->where(['login' => $_POST['email']])->one();
               if($profile != null){
                   $request['message'] = "Вы успешно авторизовались";
                   $request['type'] = "success";

                   Yii::$app->session->set('profile_auth', 'OK');
                   Yii::$app->session->set('profile_id', $profile->id);
                   Yii::$app->session->set('profile_ip', $_SERVER['REMOTE_ADDR']);
                   $users_role = UsersRoles::find()->where(['user_id' => $profile->id])->one();
                   $role = Roles::find()->where(['id' => $users_role->role_id])->one();
                   Yii::$app->session->set('profile_role', $role->name);

               }else{
                   $request['message'] = "User not found";
                   $request['type'] = "error";

               }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $request;
        }
    }

    public function actionLogout()
    {
        Yii::$app->session->set('profile_auth', 'NONE');
        Yii::$app->session->set('profile_id', 0);
        Yii::$app->session->set('profile_ip', null);
        Yii::$app->session->set('profile_role', null);
        Yii::$app->session->set('navigation_back',  null);
        Yii::$app->session->set('filtr',  null);
        Yii::$app->session->set('filtrTables',  null);
        return $this->goHome();
    }

    public function actionDelete() {
        if (Yii::$app->request->isAjax) {
            $id = $_POST['id'];
            $table = $_POST['table'];
            if ($id != null) {
                Yii::$app->db->createCommand()->delete($table, ['id' => $id])->execute();
                $request['message'] = "Удаление прошло успешно";
                $request['type'] = "success";
            } else {
                $request['message'] = "Неизвестная ошибка, попробуйте позже";
                $request['type'] = "error";
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $request;
        }
    }




}
