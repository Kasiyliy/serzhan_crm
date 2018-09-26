<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 23.01.2018
 * Time: 13:43
 */

namespace api\controllers;


use api\functions\Functions;
use api\models\MobileUser;
use api\models\OrderGroup;
use api\models\Orders;
use Yii;
use yii\web\Controller;

class OrderedProductController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     *
     */
    public function actionIndex()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isPost) {
            $auth_key = Yii::$app->request->post('auth_key');
            $order_id = Yii::$app->request->post('order_id');
            if (!empty($order_id)) {
                $mUser = new MobileUser;
                if ($mUser = $mUser->getIdentityByAuthKey($auth_key)) {
                    $order_group_id = Orders::getOrderGroupIdByOrderId($order_id);
                    if ($order_group_id || OrderGroup::isSelfOrder($mUser->id, $order_group_id)) {
                        $model = new Orders;
                        $order = $model->find()
                            ->select(
                                '`products`.`product_id`,
                                `products`.`product_name`,
                                `products`.`product_main_img`,
                                `products`.`product_imgs`,
                                `products`.`product_rating`,
                                `products`.`product_description`,
                                `shops`.`shop_id`,
                                `shops`.`shop_name`,
                                `orders`.`product_price`,
                                `orders`.`product_parameter`,
                                `orders`.`product_count`'
                            )
                            ->innerJoin('products', '`orders`.`product_id` = `products`.`product_id`')
                            ->innerJoin('order_group', '`orders`.`order_group_id` = `order_group`.`id`')
                            ->innerJoin('shops', '`order_group`.`shop_id` = `shops`.`shop_id`')
                            ->where("`orders`.`id` = $order_id")
                            ->asArray()
                            ->all();
                        if (count($order) > 0) {
                            $order = Functions::prepareSerializedData($order);
                            return $order[0];
                        } else {
                            $response['status'] = '404';
                            $response['message'] = 'There is no orders for this user.';
                            return $response;
                        }
                    } else {
                        Yii::$app->response->statusCode = 403;
                        $response['status'] = '403';
                        $response['message'] = 'Order ID is not belong to current User';
                        return $response;
                    }
                } else {
                    return Functions::authKeyNotFound();
                }
            } else {
                $response['status'] = '400';
                $response['message'] = 'Missing required parameters. Required parameters: $order_id';
                return $response;
            }
        } else {
            return Functions::methodNotAllowedResponse();
        }
    }
}
