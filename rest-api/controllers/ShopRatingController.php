<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 19.01.2018
 * Time: 10:36
 */

namespace api\controllers;


use api\functions\Functions;
use api\models\MobileUser;
use api\models\ShopRating;
use backend\models\Shops;
use Yii;
use yii\web\Controller;

class ShopRatingController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $mUser = new MobileUser;
        $shop_rating = new ShopRating;
        if(Yii::$app->request->isPost) {
            $auth_key = Yii::$app->request->post('auth_key');
            $shop_id = Yii::$app->request->post('shop_id');
            $rating_value = Yii::$app->request->post('rating_value');
            if (!empty($auth_key) && !empty($shop_id) && !empty($rating_value)) {
                if ($mUser = $mUser->getIdentityByAuthKey($auth_key)) {
                    if (!ShopRating::isRatedBefore($mUser->id, $shop_id)) {
                        $shop_rating->mobile_user_id = $mUser->id;
                        $shop_rating->shop_id = $shop_id;
                        $shop_rating->value = $rating_value;
                        if($shop_rating->validate()) {
                            if($shop_rating->save()) {
                                if($product = Shops::setShopRating($shop_rating->shop_id, $shop_rating->value)) {
                                    \Yii::$app->response->statusCode = 200;
                                    return [
                                        "status" => "200",
                                        "message" => "Successfully rated"
                                    ];
                                } else {
                                    \Yii::$app->response->statusCode = 400;
                                    $response["status"] = "400";
                                    $response["message"] = "Error encountered while trying to save rating";
                                    return $response;
                                }
                            } else {
                                \Yii::$app->response->statusCode = 400;
                                $response["status"] = "400";
                                $response["message"] = "Error encountered while trying to save rating";
                                $response["errors"] = $shop_rating->getErrors();
                                return $response;
                            }
                        } else {
                            \Yii::$app->response->statusCode = 400;
                            $response["status"] = "400";
                            $response["message"] = "Validation failed";
                            $response["errors"] = $shop_rating->getErrors();
                            return $response;
                        }
                    } else {
                        \Yii::$app->response->statusCode = 400;
                        return [
                            "status" => "400",
                            "message" => "Already rated"
                        ];
                    }
                } else {
                    Functions::authKeyNotFound();
                }
            } else {
                \Yii::$app->response->statusCode = 400;
                $response["status"] = "400";
                $response["message"] = "Missing parameters. Required parameters: 'auth_key', 'shop_id', 'rating_value'";
                return $response;
            }
        } else {
            return Functions::methodNotAllowedResponse();
        }
    }
}