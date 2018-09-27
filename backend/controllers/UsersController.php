<?php
namespace backend\controllers;
use DateTime;
use Yii;
use yii\web\Controller;
use backend\models\Users;
use backend\models\UsersRoles;


class UsersController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {

            foreach ($_POST as $k  => $v){
                print_r(' key ' . $k . ' value ' . $v);
            }
            die();
            $id = $_POST['id'];
            if ($id != null) {
                Users::find()->where(['id' => $id])->one();
            } else {
                $model = new Users();
            }
            $model->attributes = $_POST['Information'];
            $model->save();

            if ($model->save()) {

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



}

?>