<?php
namespace backend\controllers;
use backend\models\Categories;
use backend\models\Customers;
use backend\models\Goods;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use yii\web\Controller;
use backend\models\Users;
use backend\models\UsersRoles;
use yii\web\Response;


class GoodsController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {

            $id = $_POST['id'];
            if ($id != null) {
                $model = Goods::find()->where(['id' => $id])->one();
            } else {
                $model = new Goods();
            }
            $model->attributes = $_POST['Information'];
            $model->status_id = $_POST['status'];
            $model->category_id = $_POST['category'];

            if ($model->save()) {


                if($id != null){
                    $response['message'] = "Товар изменен";
                }else{
                    $response['message'] = "Товар успешно добавлен";
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





    public function actionCategory()
    {
        if (Yii::$app->request->isAjax) {

            $id = $_POST['id'];
            if ($id != null) {
                $model = Categories::find()->where(['id' => $id])->one();
            } else {
                $model = new Categories();
            }
            $model->attributes = $_POST['Information'];

            if ($model->save()) {
                if($id != null){
                    $response['message'] = "Категория изменена";
                }else{
                    $response['message'] = "Категория успешно добавлена";
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


    public function actionAddCategory()
    {
        if (Yii::$app->request->isAjax) {

            $name = $_POST['name'];

            $model = new Categories();
            $model->name = $name;

            if ($model->save()) {

                $response['type'] = "success";

            } else {
                $response['type'] = "error";
            }


            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }

    }


}

?>