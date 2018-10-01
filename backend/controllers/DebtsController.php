<?php
namespace backend\controllers;
use backend\models\Customers;
use DateTime;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use backend\models\Debts;
use backend\models\DebtReturnings;
use yii\web\Controller;
use backend\models\Users;
use backend\models\UsersRoles;
use yii\web\Response;


class DebtsController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {

            //print_r($_GET['id'] . '   ' . $_GET['sum']);
            $id = $_POST['id'];
            $model = Debts::find()->where(['id' => $id])->one();
            $model->amount  -= $_POST['sum'];
            if(($model->amount - $_POST['sum']) < 1){
                $model->deleted = 1;
                $model->status_id = 2;
            }
            if($model->save()){
                $return = new DebtReturnings();
                $return->debt_id = $model->id;
                $return->amount = $_POST['sum'];
                $return->save();
                $response["type"] = "success";
            }else{
                $response["type"] = "fail";
            }



            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }

    }




}

?>