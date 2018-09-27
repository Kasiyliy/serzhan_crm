<?php
namespace backend\controllers;
use backend\models\Customers;
use DateTime;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use yii\web\Controller;
use backend\models\Users;
use backend\models\UsersRoles;
use yii\web\Response;


class UsersController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {

            $id = $_POST['id'];
            if ($id != null) {
                $model = Users::find()->where(['id' => $id])->one();
            } else {
                $model = new Users();
            }
            $model->attributes = $_POST['Information'];

            if ($model->save()) {
                try {
                    (new Query)
                        ->createCommand()
                        ->delete('users_roles', ['user_id' => $model->id])
                        ->execute();
                } catch (Exception $e) {
                    $response['message'] = "Неизвестная ошибка, попробуйте позже.";
                    $response['type'] = "error";
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return $response;
                }
                $roles = explode(",", $_POST['roles']);
                foreach ($roles as $id){
                    $users_roles = new UsersRoles();
                    $users_roles->role_id = $id;
                    $users_roles->user_id = $model->id;
                    $users_roles->save();
                }

                if($id != null){
                    $response['message'] = "Пользователь изменен";
                }else{
                    $response['message'] = "Пользователь успешно добавлен";
                }

                $response['type'] = "success";

            } else {
                $response['message'] = "Неизвестная ошибка, попробуйте позже.";
                $response['type'] = "error";
            }


            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }

    }




    public function actionCustomers()
    {
        if (Yii::$app->request->isAjax) {

            $id = $_POST['id'];
            if ($id != null) {
                $model = Customers::find()->where(['id' => $id])->one();
            } else {
                $model = new Customers();
            }
            $model->attributes = $_POST['Information'];

            if ($model->save()) {
                if($id != null){
                    $response['message'] = "Клиент изменен";
                }else{
                    $response['message'] = "Клиент успешно добавлен";
                }

                $response['type'] = "success";

            } else {
                $response['message'] = "Неизвестная ошибка, попробуйте позже.";
                $response['type'] = "error";
            }


            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }

    }



}

?>