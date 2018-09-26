<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 18.01.2018
 * Time: 13:16
 */

namespace api\controllers;


use api\models\MobileUser;
use backend\models\Cities;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class LoginController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $model = new MobileUser;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(Yii::$app->request->isPost) {
            $model->email = Yii::$app->request->post('email');
            $model->password = Yii::$app->request->post('password');

            if ($mUser = $model->getIdentityByEmail($model->email)) {
                if($mUser->validatePassword($model->password)) {
                    if ($mUser->generateAuthKey()) {
                        Yii::$app->response->statusCode = 200;
                        unset($mUser->password);
                        $response = ArrayHelper::toArray($mUser);
                        $response[key(Cities::getCityNameById($mUser->city_id))] = reset(Cities::getCityNameById($mUser->city_id));
                        return $response;
                    } else {
                        return "error occurred while generating and saving auth key";
                    }
                } else {
                    Yii::$app->response->statusCode = 400;
                    $response['status'] = "400";
                    $response['message'] = "Incorrect email or password";
                    return $response;
                }
            } else {
                Yii::$app->response->statusCode = 400;
                $response['status'] = "400";
                $response['message'] = "Incorrect email or password";
                return $response;
            }
        } else {
            Yii::$app->response->statusCode = 405;
            return [
                "status" => "405",
                "message" => "Method not Allowed"
            ];
        }
    }
}