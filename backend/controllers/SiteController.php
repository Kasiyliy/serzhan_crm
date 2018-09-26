<?php
namespace backend\controllers;

use backend\components\SendMail;
use backend\components\Stats;

use PHPExcel_Settings;
use Yii;
use backend\models\Users;
use backend\models\Privileges;
use backend\models\Services;
use backend\models\TaxiParkPrivileges;
use backend\models\TaxiPark;
use yii\web\Controller;
use yii\web\Response;
use backend\components\Helpers;


class SiteController extends Controller
{

    public function behaviors()
    {
        return [];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAuthentication() {
        Helpers::CheckAuth("redirect", "/profile/");
        return $this->render('index');
    }


    public function actionLogin() {
        if (Yii::$app->request->isAjax) {



            $profile = Users::find()->where(['email' => $_POST['email']])->andWhere(['role_id' => [3, 4, 5, 6]])->one();
           if($profile != null){
               $request['message'] = "Вы успешно авторизовались";
               $request['type'] = "success";

               Yii::$app->session->set('profile_auth', 'OK');
               Yii::$app->session->set('profile_id', $profile->id);
               Yii::$app->session->set('profile_tp', $profile->taxi_park_id);
               Yii::$app->session->set('profile_ip', $_SERVER['REMOTE_ADDR']);
               Yii::$app->session->set('profile_fio', $profile->name);
               Yii::$app->session->set('profile_role', $profile->role_id);

               Yii::$app->session->set('profile_avatar', $profile->avatar_path);

           }else{
               $request['message'] = "User not found";
               $request['type'] = "error";

           }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $request;
        }
    }

    public function actionLogout()
    {
        Yii::$app->session->set('profile_auth', 'NONE');
        Yii::$app->session->set('profile_id', 0);
        Yii::$app->session->set('profile_ip', null);
        Yii::$app->session->set('profile_role', null);
        Yii::$app->session->set('navigation_back',  null);
        Yii::$app->session->set('filtr',  null);
        Yii::$app->session->set('filtrTables',  null);
        return $this->goHome();
    }

    public function actionDelete() {
        if (Yii::$app->request->isAjax) {
            $id = $_POST['id'];
            $table = $_POST['table'];
            if ($id != null) {
                Yii::$app->db->createCommand()->delete($table, ['id' => $id])->execute();
                $request['message'] = "Удаление прошло успешно";
                $request['type'] = "success";
            } else {
                $request['message'] = "Неизвестная ошибка, попробуйте позже";
                $request['type'] = "error";
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $request;
        }
    }


    public function actionBalance() {
        if (Yii::$app->request->isAjax) {

            $me = \backend\models\Users::find()->where(['id' => Yii::$app->session->get("profile_id")])->one();
            $taxi_park = \backend\models\TaxiPark::find()->where(['id' => $me->taxi_park_id])->one();

            $request['balance'] = $taxi_park->balance;

            Yii::$app->response->format = Response::FORMAT_JSON;
            return $request;
        }
    }

    public function actionChangePrice() {
        if (Yii::$app->request->isAjax) {

            $prices = $_POST['price'];
            foreach ($prices as $key => $value){
                $service = Services::find()->where(['id' => $key+1])->one();
                $service->access_price = $value;
                $service->save();
            }

            $request['type'] = 'success';
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $request;
        }
    }
    public function actionBuyAccess() {
        if (Yii::$app->request->isAjax) {


            $me = Users::find()->where(['id' => Yii::$app->session->get("profile_id")])->one();
            $tp = TaxiPark::find()->where(['id' => $me->taxi_park_id])->one();

            $total_sum = 0;
            foreach ($_POST['amount'] as $k => $v){
                $price = Services::find()->where(['id' => $k])->one();
                $total_sum += $price->access_price * $v;
            }

            if($tp->balance < $total_sum){
                $request['type'] = 'error';
            }else{
                foreach ($_POST['amount'] as $k => $v){
                    if($v != null){
                        $model = TaxiParkPrivileges::find()->where(['taxi_park_id' => $me->taxi_park_id])->andWhere(['service_id' => $k])->one();
                        if($model == null){
                            $model = new TaxiParkPrivileges();
                            $model->taxi_park_id = $me->taxi_park_id;
                            $model->service_id = $k;
                            $model->amount = $v;
                        }else{
                            $model->amount = $v + $model->amount;
                        }
                        $model->save();

                    }
                }

                $tp->balance = $tp->balance - $total_sum;
                $tp->save();

                $request['type'] = 'success';
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            return $request;
        }
    }



}
