<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 22.01.2018
 * Time: 11:28
 */

namespace api\controllers;


use api\functions\Functions;
use api\models\MobileUser;
use Yii;
use yii\web\Controller;

class UserInfoController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionChange()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(Yii::$app->request->isPost) {
            $auth_key = Yii::$app->request->post('auth_key');

            $mUser = new MobileUser;
            if ($mUser = $mUser->getIdentityByAuthKey($auth_key)) {
                if (!empty(Yii::$app->request->post('username'))) {
                    $mUser->username = Yii::$app->request->post('username');
                }
                if (!empty(Yii::$app->request->post('phone'))) {
                    $mUser->phone = Yii::$app->request->post('phone');
                }
                if (!empty(Yii::$app->request->post('old_password')) && !empty(Yii::$app->request->post('new_password'))) {
                    if ($mUser->validatePassword(Yii::$app->request->post('old_password'))) {
                        $mUser->password = Yii::$app->request->post('new_password');
                        $mUser->password = Yii::$app->getSecurity()->generatePasswordHash($mUser->password);
                    } else {
                        Yii::$app->response->statusCode = 400;
                        $response['status'] = "400";
                        $response['message'] = "Incorrect password";
                        return $response;
                    }
                }
                if(empty(Yii::$app->request->post('old_password')) && !empty(Yii::$app->request->post('new_password')) OR !empty(Yii::$app->request->post('old_password')) && empty(Yii::$app->request->post('new_password'))) {
                    \Yii::$app->response->statusCode = 400;
                    $response['status'] = "400";
                    $response['message'] = "New password or Old password not sent";
                    return $response;
                }

                if (!empty(Yii::$app->request->post('city_id'))) {
                    $mUser->city_id = Yii::$app->request->post('city_id');
                }

                if ($mUser->validate()) {
                    if ($mUser->save()) {
                        \Yii::$app->response->statusCode = 200;
                        return [
                            "status" => "200",
                            "message" => "Successfully edited"
                        ];
                    } else {
                        \Yii::$app->response->statusCode = 400;
                        $response['status'] = "400";
                        $response['message'] = "User info edit failed";
                        $response['errors'] = $mUser->getErrors();
                        return $response;
                    }
                } else {
                    \Yii::$app->response->statusCode = 400;
                    $response['status'] = "400";
                    $response['message'] = "Validation failed";
                    $response['errors'] = $mUser->getErrors();
                    return $response;
                }
            } else {
                return Functions::authKeyNotFound();
            }
        } else {
            return Functions::methodNotAllowedResponse();
        }
    }
}