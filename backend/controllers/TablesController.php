<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use backend\components\Helpers;

class TablesController extends Controller
{
    public function actionGettable() {
        if (Yii::$app->request->isAjax) {
            $table = $_POST['table'];
            $name = $_POST['name'];
            $other = (array)$_POST['other'];
            $config = Helpers::GetConfig($name, "select_fields");


            $filtr = Yii::$app->session->get('filtr');

            /* -------------- ВНЕДРЕНИЕ */
            if (Yii::$app->session->get('profile_role') != "admin" AND Yii::$app->session->get('profile_role') != "superadmin") {
                if ($name == "sellers") {
                    $query = "rod_id = " . Yii::$app->session->get('profile_id'); //Видят только дилеры
                } else if ($name == "shops") {
                    if (Yii::$app->session->get('profile_role') == "dealer") {
                        $query = "dealer_id = " . Yii::$app->session->get('profile_id');
                    } else if (Yii::$app->session->get('profile_role') == "seller") {
                        $query = "user_id = " . Yii::$app->session->get('profile_id');
                    }
                }
            }

            $arr_date = array();
            $condition[1] = 1;
            if ($filtr[$name] != null) {
                $condition = array();
                foreach ($filtr[$name] as $key => $value) {
                    if (count($value) <= 1) {
                        $condition[$key] = $value;
                    } else {
                        foreach ($value as $d => $date) {
                            if ($arr_date[$key]['start'] == null) {
                                $query .= " AND " . $key . " >= " . $date;
                                $arr_date[$key]['start'] = $date;
                            } else {
                                $query .= " AND " . $key . " <= " . $date;
                                $arr_date[$key]['end'] = $date;
                            }
                        }
                    }
                }
            }
            if ($other != null) {
                foreach ($other as $key => $value) {
                    $condition[$key] = $value;
                }
            }


//            print_r($condition);
//            die();

            if ($name == "moderators") { //Producty
                $model = (new \yii\db\Query())
                    ->select('`id`,
                       `name`,
                         `phone`,
                         `last_edit`,
                         `created`,
                         `email`
                        '
                    )
                    ->from($table)
                    ->where(['role_id' => 4])
                   ->all();
            }
    }

    public function actionFiltr() {
        if (Yii::$app->request->isAjax) {
            $page = $_POST['page'];
            $field = $_POST['field'];
            $value = $_POST['value'];

            $array = Yii::$app->session->get('filtr');
            if ($value == "all") {
                unset($array[$page][$field]);
            } else {
                $array[$page][$field] = $value;
            }
            if (count($array[$page]) <= 0) {
                unset($array[$page]);
            }
            Yii::$app->session->set('filtr', $array);
        }
    }

    public function actionFiltrdate() {
        if (Yii::$app->request->isAjax) {
            $page = $_POST['page'];
            $field = $_POST['field'];
            $start =$_POST['start'];
            $end = $_POST['end'];

            $array = Yii::$app->session->get('filtr');
            $array[$page][$field] = array("start" => strtotime($start), "end" => strtotime($end));
            if (count($array[$page]) <= 0) {
                unset($array[$page]);
            }
            Yii::$app->session->set('filtr', $array);
        }
    }

    public function actionDelfiltr() {
        if (Yii::$app->request->isAjax) {
            $page = $_POST['page'];
            $field = $_POST['field'];

            $array = Yii::$app->session->get('filtr');
            unset($array[$page][$field]);
            if (count($array[$page]) <= 0) {
                unset($array[$page]);
            }
            Yii::$app->session->set('filtr', $array);
        }
    }
}
?>
