<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 18.01.2018
 * Time: 15:34
 */

namespace api\controllers;


use api\functions\Functions;
use api\models\MobileUser;
use api\models\OrderGroup;
use api\models\Orders;
use backend\models\Products;
use backend\models\Shops;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use backend\models\ProductsList;

class OrderController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     *
     */
    public function actionIndex()
    {
        $mUser = new MobileUser;
        $order_group = new OrderGroup;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(Yii::$app->request->isPost) {
            $auth_key = Yii::$app->request->post('auth_key');
            if ($mUser = $mUser->getIdentityByAuthKey($auth_key)) {
                $products = Yii::$app->request->post('products');
                $overall_summ = 0;
                foreach ($products as $key => $value) {
                    $orders[$key] = new Orders;
                }
                foreach ($products as $key => $value) {
                    $orders[$key]->product_id = $value['product_id'];
                    $orders[$key]->product_price = $value['product_price'];
                    $orders[$key]->product_count = $value['product_count'];
                    $orders[$key]->product_summ = $orders[$key]->product_price * $orders[$key]->product_count;
                    if($value['product_parameters']) {
                        $orders[$key]->product_parameter = $value['product_parameters'];
                        $orders[$key]->product_parameter = serialize($orders[$key]->product_parameter);
                    } else {
                        $orders[$key]->product_parameter = NULL;
                    }

                    $orders[$key]->status = Orders::STATUS_ORDERED;
                    $overall_summ += $orders[$key]->product_price * $orders[$key]->product_count;
                    if(!$orders[$key]->validate()) {
                        return $orders[$key]->getErrors();
                    }
                }
                $order_group->mobile_user_id = $mUser->id;
                $order_group->address = Yii::$app->request->post('address');
                $order_group->description = Yii::$app->request->post('description');
                $order_group->count = count($products);
                $order_group->overall_summ = $overall_summ;
                $order_group->status = Orders::STATUS_ORDERED;
                $order_group->updated_date =
                $order_group->shop_id = ProductsList::getShopByProductId($orders[0]->product_id);
                if($order_group->validate()) {
                    if($order_group->save()) {
                        foreach ($orders as $order) {
                            $order->order_group_id = $order_group->id;
                            if(!$order->save()) {
                                return $order->getErrors();
                            }
                        }
                        \Yii::$app->response->statusCode = 201;
                        return [
                            "status" => "201",
                            "message" => "Successfully ordered"
                        ];
                    } else {
                        return $order_group->getErrors();
                    }
                } else {
                    return $order_group->getErrors();
                }
            } else {
                return Functions::authKeyNotFound();
            }
        } else {
            return Functions::methodNotAllowedResponse();
        }
    }
}