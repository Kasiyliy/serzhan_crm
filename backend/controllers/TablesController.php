<?php
namespace backend\controllers;
use api\models\Orders;
use backend\models\Company;
use backend\models\Users;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use backend\components\Helpers;
use backend\models\MonetsTraffic;

class TablesController extends Controller
{

    public function actionGettable()
    {
        if (Yii::$app->request->isAjax) {
            $table = $_POST['table'];
            $name = $_POST['name'];
            $other = (array)$_POST['other'];
            $config = Helpers::GetConfig($name, "select_fields");
            $draw = $_GET['draw'];      //Текущая страница
            $start = $_GET['start'];    //С какой записи
            $length = $_GET['length'];  //Количество записей на страницу
            $search = $_GET['search']['value'];  //Поиск
            $order = $_GET['order'][0]; //Сортировка


//            $query = null;
            $filtr = Yii::$app->session->get('filtr');

            /* -------------- ВНЕДРЕНИЕ */
            if (Yii::$app->session->get('profile_role') != 3) {

                if (Yii::$app->session->get('profile_role') == 5) {
                    if ($name == "taxi-parks") {
                        $query = "taxi_park.id = " . Yii::$app->session->get('profile_tp'); //Видят только дилеры
                    }
                }
            }

            $arr_date = array();


            if ($filtr[$name] != null) {
                $condition = null;
                foreach ($filtr[$name] as $key => $value) {
                    if (count($value) <= 1) {
                        if ($condition == null) {
                            $condition .= $table . "." . $key . " = '" . $value . "'";
                        } else {
                            $condition .= " AND " . $table . "." . $key . " = '" . $value . "'";
                        }
                    } else {
                        foreach ($value as $d => $date) {
                            if ($arr_date[$key]['start'] == null) {
                                $query .= " " . $table . "." . $key . " >= " . $date;
                                $arr_date[$key]['start'] = $date;
                            } else {
                                $query .= " AND " . $table . "." . $key . " <= " . $date;
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
            if ($condition == null) {
                $condition = $table . ".id IS NOT NULL";
            }

//            print_r('q' . $query . ' c ' . $condition); die();
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
            } else if ($name == "admins") { //Producty
                $model = (new \yii\db\Query())
                    ->select('`id`,
                         `name`,
                         `phone`,
                         `last_edit`,
                         `created`,
                         `email`'
                    )
                    ->from($table)
                    ->andWhere($query)
                    ->where(['role_id' => 3])
                    ->all();
            }
            else if ($name == "cadmins") { //Producty
                $model = (new \yii\db\Query())
                    ->select('`users`.`id`,
                         `users`.`name`,
                         `users`.`phone`,
                         `company`.`name` AS cname,
                         `users`.`created`'
                    )
                    ->from($table)
                    ->andWhere($query)
                    ->where(['role_id' => 7])
                    ->innerJoin('company', 'users.company_id = company.id')
                    ->all();
            }
            else if ($name == "users") { //Producty
                $model = (new \yii\db\Query())
                    ->select('`id`,
                         `name`,
                         `phone`,
                         `last_edit`,
                         `created`,
                         `email`,
                         `is_active`'
                    )
                    ->from($table)
                    ->where(['role_id' => 1])
                    ->andWhere($condition)
                    ->all();
            }
            else if ($name == "coworkers") { //Producty
                $myId = Yii::$app->session->get('profile_id');
                $me = Users::find()->where(['id' => $myId])->one();
                $model = (new \yii\db\Query())
                    ->select('`id`,
                         `name`,
                         `phone`,
                         `last_edit`,
                         `created`,
                         `email`,
                         `is_active`,
                         `company_id`'
                    )
                    ->from($table)
                    ->where(['role_id' => 1])
                    ->andWhere(['company_id' => $me->company_id])
                    ->andWhere($condition)
                    ->all();
            }
            else if ($name == "aworkers") { //Producty
                $myId = Yii::$app->session->get('profile_id');
                $me = Users::find()->where(['id' => $myId])->one();
                $model = (new \yii\db\Query())
                    ->select('`id`,
                         `name`,
                         `phone`,
                         `last_edit`,
                         `created`,
                         `email`,
                         `is_active`,
                         `company_id`'
                    )
                    ->from($table)
                    ->where(['role_id' => 1])
                    ->andWhere(['company_id' => NULL])
                    ->andWhere($condition)
                    ->all();
            }

            else if ($name == "dispatchers") { //Producty
                $model = (new \yii\db\Query())
                    ->select('`users`.`id`,
                         `users`.`name`,
                         `users`.`phone`,
   
                         `taxi_park`.`name` AS tname,
                         `users`.`created`'
                    )
                    ->from($table)
                    ->where(['role_id' => 8])
                    ->andWhere($condition)
                    ->innerJoin('taxi_park', '`taxi_park`.`id` = `users`.`taxi_park_id`')
                    ->all();
            }

            else if ($name == "tadmins") { //Producty
                $model = (new \yii\db\Query())
                    ->select('`users`.`id`,
                         `users`.`name`,
                         `users`.`phone`,
                         `users`.`last_edit`,
                         `users`.`created`,
                         `users`.`email`,
                         `taxi_park`.`name` AS tname'
                    )
                    ->from($table)
                    ->where(['role_id' => 5])
                    //   ->andWhere($condition)
                    ->innerJoin('taxi_park', '`taxi_park`.`id` = `users`.`taxi_park_id`')
                    ->all();
            } else if ($name == "cashiers") { //Producty

                $model = (new \yii\db\Query())
                    ->select('`users`.`id`,
                         `users`.`name`,
                         `users`.`phone`,
                         `users`.`last_edit`,
                         `users`.`created`,
                         `users`.`email`,
                         `taxi_park`.`name` AS tname'
                    )
                    ->from($table)
                    ->where(['role_id' => 6])
                    ->innerJoin('taxi_park', '`taxi_park`.`id` = `users`.`taxi_park_id`')
                    ->all();
            } else if ($name == "taxi-parks" OR $name == "cashier") {


                $model = (new \yii\db\Query())
                    ->select('`taxi_park`.`id`,
                         `taxi_park`.`name`,
                         `taxi_park`.`balance`,
                         `cities`.`cname`,
                         `working_types`.`description`'
                    )
                    ->from($table)
                    ->where($query)
                    ->andWhere($condition)
                    ->innerJoin('cities', '`taxi_park`.`city_id` = `cities`.`id`')
                    ->innerJoin('working_types', '`working_types`.`id` = `taxi_park`.`type`')
                    ->all();
            } else if ($name == "drivers") { //Producty
                $model = (new \yii\db\Query())
                    ->select('`users`.`id`,
                         `users`.`name`,
                         `users`.`phone`,
                         `users`.`created`,
                         `users`.`email`,
                         `users`.`is_active`,
                         `users`.`balance`,
                         `taxi_park`.`name` AS tname'
                    )
                    ->from($table)
                    ->where(['role_id' => 2])
                    // ->andWhere($condition)
                    ->innerJoin('taxi_park', '`taxi_park`.`id` = `users`.`taxi_park_id`')
                    ->all();
            } else if ($name == "traffic") { //Producty
                $model = (new \yii\db\Query())
                    ->select('`monets_traffic`.`id`,
                         `u1`.`name` AS sname,
                         `u2`.`name` AS rname,
                         `tp1`.`name` AS tps,
                         `tp2`.`name` AS tpr,
                         `monets_traffic`.`date`,
                         `monets_traffic`.`sender_user_id`,
                         `monets_traffic`.`sender_tp_id`,
                         `monets_traffic`.`reciever_tp_id`,
                         `monets_traffic`.`reciever_user_id`,
                         `monets_traffic`.`amount`,
                         `monets_traffic`.`process`'
                    )
                    ->from($table)
                    ->where($query)
                    ->innerJoin('users u1', '`u1`.`id` = `monets_traffic`.`sender_user_id`')
                    ->innerJoin('users u2', '`u2`.`id` = `monets_traffic`.`reciever_user_id`')
                    ->innerJoin('taxi_park tp1', '`tp1`.`id` = `monets_traffic`.`sender_tp_id`')
                    ->innerJoin('taxi_park tp2', '`tp2`.`id` = `monets_traffic`.`reciever_tp_id`')
                    ->limit($length)
                    ->offset($start)
                    ->all();
            }
            else if ($name == "companies") { //Producty

                $companies = Company::find()->all();
                $ar = [];
                foreach ($companies as $key => $value){
                    $arr['id'] = $value->id;
                    $arr['name'] = $value->name;
                    $arr['balance'] = $value->balance;
                    $arr['created'] = $value->created;
                    $user = Users::find()->where(['role_id' => 7])->andWhere(['company_id' => $value->id])->one();
                    $arr['username'] = $user->name;
                    array_push($ar, $arr);
                }

//                $model = (new \yii\db\Query())
//                    ->select('`company`.`id`,
//                         `company`.`name`,
//                         `company`.`balance`,
//                         `company`.`created`,
//                         `users`.`name`'
//                    )
//                    ->from($table)
//                    ->innerJoin('users', 'users.company_id = company.')
//                    ->all();
                $model = $ar;

            }
            else {

                $model = (new \yii\db\Query())
                    ->select($config)
                    ->from($table)
                    ->andWhere($condition)
                    ->andWhere($query)
                    ->all();
            }
            $data['data'] = array_map('array_values', $model);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $data;
        }
    }



    public function actionFiltr()
    {
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

    public function actionFiltrdate()
    {
        if (Yii::$app->request->isAjax) {
            $page = $_POST['page'];
            $field = $_POST['field'];
            $start = $_POST['start'];
            $end = $_POST['end'];

            $array = Yii::$app->session->get('filtr');
            $array[$page][$field] = array("start" => strtotime($start), "end" => strtotime($end));
            if (count($array[$page]) <= 0) {
                unset($array[$page]);
            }
            Yii::$app->session->set('filtr', $array);
        }
    }

    public function actionDelfiltr()
    {
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

    public function actionSavestate()
    {

        $response = array();
        foreach ($_POST as $key => $value) {
            if ($key == "time" OR $key == "start" OR $key == "length") {
                $response[$key] = intval($value);
            } else {
                $response[$key] = $value;
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->session->set('profile_state', array('products' => $_POST));
        return $response;
    }

    public function actionGetstate()
    {
        $page = 'monets_traffic';

        $state = Yii::$app->session->get('profile_state');
        if ($state[$page] == null) {
            $time = time() * 1000;
            $state[$page] = array(
                'time' => intval($time),
                'start' => 0,
                'length' => 10,
                'order' => array(
                    '0' => array(
                        '0' => 1,
                        '1' => 'asc'
                    ),
                ),
            );
            Yii::$app->session->set('profile_state', $state);
        }
        $state = Yii::$app->session->get('profile_state');
        $response = array();
        foreach ($state['monets_traffic'] as $key => $value) {
            if ($key == "time" OR $key == "start" OR $key == "length") {
                $response[$key] = intval($value);
            } else {
                $response[$key] = $value;
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }


    public function actionGetNewTable()
    {
        if (Yii::$app->request->isAjax) {
            $name = $_GET['name'];
            $table = $_GET['table'];

            $draw = $_GET['draw'];      //Текущая страница
            $start = $_GET['start'];    //С какой записи
            $length = $_GET['length'];  //Количество записей на страницу
            $search = $_GET['search']['value'];  //Поиск
            $order = $_GET['order'][0]; //Сортировка

            $config = Helpers::GetConfig($name, "select_fields");
            $search_config = Helpers::GetConfig($name, "search_fields");
            $filtr = Yii::$app->session->get('filtr');

            $other = (array)$_POST['other'];
            $query = null;
            $condition = null;
            $search_condition = $table . '.id != 0';

            if ($order['dir'] == "asc") {
                $sort = SORT_ASC;
            } else {
                $sort = SORT_DESC;
            }

            $arr_date = array();


            /* -------------- ВНЕДРЕНИЕ */
            if (Yii::$app->session->get('profile_role') != 3) {
                if ($name == "orders") {
                    if (Yii::$app->session->get('profile_role') == 5) {
                        $query = $table . ".taxi_park_id = " . Yii::$app->session->get('profile_tp');
                    } else if (Yii::$app->session->get('profile_role') == 7) {
                        $query = $table . ".company_id = " . Yii::$app->session->get('company_id');
                    }
                }
            }


            if ($query == null) {
                $query = $table . ".id != -1";
            }



            if ($filtr[$name] != null) {
                foreach ($filtr[$name] as $key => $value) {
                    if (count($value) <= 1) {
                        if ($condition == null) {
                            $condition .= $table . "." . $key . " = '" . $value . "'";
                        } else {
                            $condition .= " AND " . $table . "." . $key . " = '" . $value . "'";
                        }
                    } else {
                        foreach ($value as $d => $date) {
                            if ($arr_date[$key]['start'] == null) {
                                $query .= " AND " . $table . "." . $key . " >= " . $date;
                                $arr_date[$key]['start'] = $date;
                            } else {
                                $query .= " AND " . $table . "." . $key . " <= " . $date;
                                $arr_date[$key]['end'] = $date;
                            }
                        }
                    }
                }
            }
            if ($other != null) {
                foreach ($other as $key => $value) {
                    if ($condition == null) {
                        $condition .= $table . "." . $key . " = '" . $value . "'";
                    } else {
                        $condition .= " AND " . $table . "." . $key . " = '" . $value . "'";
                    }
                }
            }

            if ($search != null AND $search_config != null) {
                $search_condition = null;
                foreach ($search_config as $value) {
                    if ($search_condition == null) {
                        $search_condition .= $table . "." . $value . " LIKE '%" . $search . "%'";
                    } else {
                        $search_condition .= " OR " . $table . "." . $value . " LIKE '%" . $search . "%'";
                    }
                }
            }
            if ($condition == null) {
                $condition = $table . ".id IS NOT NULL";
            }


            if ($name == "users") { //Producty
                $recordsTotal = Users::find()->andWhere($query)->count();
                $recordsFiltered = Users::find()->andWhere($condition)->andWhere($query)->andWhere($search_condition)->count();
                $model = (new \yii\db\Query())
                    ->select('`users`.`id`,
                         `users`.`first_name`,
                         `users`.`last_name`,
                         `users`.`created`,
                         `users`.`phone_number`,
                         group_concat( roles.name) as roles'
                    )
                    ->from($table)
                    ->where($query)
                    ->andWhere($condition)
                    ->innerJoin("users_roles", "users_roles.user_id = users.id")
                    ->innerJoin("roles", "roles.id = users_roles.role_id")
                    ->groupBy('users.id')
                    ->limit($length)
                    ->offset($start)
                    ->all();
            }


            $array['draw'] = $draw;
            $array['recordsTotal'] = $recordsTotal;
            $array['recordsFiltered'] = $recordsFiltered;
            $array['data'] = $model;

            Yii::$app->response->format = Response::FORMAT_JSON;
            return $array;
        }
    }
}
?>
