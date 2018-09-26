<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 17.01.2018
 * Time: 19:57
 */

namespace api\controllers;


use api\functions\Functions;
use api\models\MobileUser;
use backend\models\SignupForm;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class SignUpController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     *
     */
    public function actionIndex()
    {
        $model = new MobileUser;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(Yii::$app->request->isPost) {
            $model->username = Yii::$app->request->post('username');
            $model->email = Yii::$app->request->post('email');
            $model->phone = Yii::$app->request->post('phone');
            $model->password = Yii::$app->request->post('password');
            $model->city_id = Yii::$app->request->post('city_id');;
            if($model->validate()) {
                if($model->register()) {
                    \Yii::$app->response->statusCode = 201;
                    return [
                        "status" => "201",
                        "message" => "Успешно зарегистрирован",
                        "auth_key" => $model->auth_key
                    ];
                } else {
                    \Yii::$app->response->statusCode = 400;
                    $response['status'] = "400";
                    $response['message'] = "Register failed";
                    $response['errors'] = $model->getErrors();
                    return $response;
                }
            } else {
                \Yii::$app->response->statusCode = 400;
                $response['status'] = "400";
                $response['message'] = "Validation failed";
                $response['errors'] = $model->getErrors();
                return $response;
            }
        } else {
            return Functions::methodNotAllowedResponse();
        }
    }

}