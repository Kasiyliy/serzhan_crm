<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 19.01.2018
 * Time: 10:35
 */

namespace api\controllers;


use api\functions\Functions;
use api\models\MobileUser;
use api\models\ProductRating;
use backend\models\Products;
use Yii;
use yii\web\Controller;

class ProductRatingController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @return array|mixed
     */
    public function actionIndex()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $mUser = new MobileUser;
        $product_rating = new ProductRating;
        if(Yii::$app->request->isPost) {
            $auth_key = Yii::$app->request->post('auth_key');
            $product_id = Yii::$app->request->post('product_id');
            $rating_value = Yii::$app->request->post('rating_value');

            if ($mUser = $mUser->getIdentityByAuthKey($auth_key)) {
                if(!ProductRating::isRatedBefore($mUser->id, $product_id)) {
                    $product_rating->mobile_user_id = $mUser->id;
                    $product_rating->product_id = $product_id;
                    $product_rating->value = $rating_value;
                    if($product_rating->validate()) {
                        if($product_rating->save()) {
                            $rows_count = ProductRating::find()->where(['product_id' => $product_id])->count();
                            if($product = Products::setProductRating($product_rating->product_id, $product_rating->value, $rows_count)) {
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
                            $response["errors"] = $product_rating->getErrors();
                            return $response;
                        }
                    } else {
                        \Yii::$app->response->statusCode = 400;
                        $response["status"] = "400";
                        $response["message"] = "Validation failed";
                        $response["errors"] = $product_rating->getErrors();
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
                return Functions::notAuthorizedResponse();
            }
        }
    }
}