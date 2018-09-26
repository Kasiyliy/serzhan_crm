<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 23.01.2018
 * Time: 11:42
 */

namespace api\controllers;


use api\functions\Functions;
use api\models\MobileUser;
use api\models\Orders;
use Yii;
use yii\web\Controller;

class OrdersListController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @return array|mixed
     */
    public function actionIndex()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $auth_key = Yii::$app->request->post('auth_key');
            $order_group_id = Yii::$app->request->post('order_group_id');
            if (!empty($order_group_id)) {
                $mUser = new MobileUser;
                if ($mUser = $mUser->getIdentityByAuthKey($auth_key)) {
                    $model = new Orders;
                    $orderGroup = $model->find()
                        ->select(
                            '`products`.`product_name`,
                            `orders`.`id`,
                            `orders`.`product_price`,
                            `shops`.`shop_name`,
                            `products`.`product_id`,
                            `products`.`product_main_img`,
                            `shops`.`shop_id`,
                            `orders`.`product_count`'
                        )
                        ->innerJoin('products', '`orders`.`product_id` = `products`.`product_id`')
                        ->innerJoin('order_group', '`orders`.`order_group_id` = `order_group`.`id`')
                        ->innerJoin('shops', '`order_group`.`shop_id` = `shops`.`shop_id`')
                        ->asArray()
                        ->where([
                            'mobile_user_id' => $mUser->id,
                            'order_group_id' => $order_group_id
                        ])
                        ->all();
                    if (count($orderGroup) > 0) {
                        return $orderGroup;
                    } else {
                        \Yii::$app->response->statusCode = 404;
                        $response['status'] = '404';
                        $response['message'] = 'There is no orders for this user.';
                        return $response;
                    }
                } else {
                    return Functions::authKeyNotFound();
                }
            } else {
                \Yii::$app->response->statusCode = 404;
                $response['status'] = '400';
                $response['message'] = 'Missing required parameter. Required: "order_group_id"';
                return $response;
            }
        } else {
            return Functions::methodNotAllowedResponse();
        }
    }
}