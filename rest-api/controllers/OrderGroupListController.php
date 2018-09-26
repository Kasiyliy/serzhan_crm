<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 23.01.2018
 * Time: 11:15
 */

namespace api\controllers;


use api\functions\Functions;
use api\models\MobileUser;
use api\models\OrderGroup;
use Yii;
use yii\web\Controller;

class OrderGroupListController extends Controller
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
            $mUser = new MobileUser;
            if ($mUser = $mUser->getIdentityByAuthKey($auth_key)) {
                $model = new OrderGroup;
                $orderGroup = $model->find()
                    ->select('`id`, `created_date`, `count`, `overall_summ`')
                    ->asArray()
                    ->where(['mobile_user_id' => $mUser->id])
                    ->all();
                if (count($orderGroup) > 0) {
                    return $orderGroup;
                } else {
                    $response['status'] = '404';
                    $response['message'] = 'There is no orders for this user.';
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