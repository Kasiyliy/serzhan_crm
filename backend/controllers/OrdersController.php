<?php
namespace backend\controllers;
use backend\models\Customers;
use backend\models\Debts;
use backend\models\Goods;
use backend\models\Orders;
use backend\models\OrdersGoods;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use yii\web\Controller;
use backend\models\Users;
use backend\models\UsersRoles;
use yii\web\Response;


class OrdersController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {

            if($_POST['goods'] == null){
                $response['message'] = "Вы не выбрали товары";
                $response['type'] = "error";
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;
            }
            $id = $_POST['id'];
            if ($id != null) {
                $model = Orders::find()->where(['id' => $id])->one();
            } else {
                $model = new Orders();
            }
            $model->user_id = Yii::$app->session->get("profile_id");
            $model->attributes = $_POST['Information'];
            $model->customer_id= $_POST['client'];

            if ($model->save()) {

                if($_POST['debt'] != null){
                    $debt = new Debts();
                    $debt->order_id = $model->id;
                    $debt->amount = $_POST['debt'];
                    $debt->debt_status_id = 1;
                    $debt->save();
                }
                (new Query)
                    ->createCommand()
                    ->delete('orders_goods', ['order_id' => $model->id])
                    ->execute();

                foreach ($_POST['goods'] as $key => $value){
                    $orders_goods = new OrdersGoods();
                    $orders_goods->order_id = $model->id;
                    $orders_goods->good_id = $value;
                    $orders_goods->quantity = $_POST['amount'][$key];
                    $orders_goods->save();
                    $good = Goods::find()->where(['id' => $value])->one();
                    $good->quantity -= $_POST['amount'][$key];
                    $good->save();
                }

                if($id != null){
                    $response['message'] = "Заказ изменен";
                }else{
                    $response['message'] = "Заказ успешно создан";
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


public function actionGetGoods(){

    $goods = Goods::find()->all();
    $response["goods"] = $goods;
    Yii::$app->response->format = Response::FORMAT_JSON;
    return $response;
}



}

?>