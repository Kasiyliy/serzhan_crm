<?php
namespace backend\controllers;
use api\models\Orders;
use backend\models\Categories;
use backend\models\Company;
use backend\models\Customers;
use backend\models\DebtReturnings;
use backend\models\Debts;
use backend\models\Goods;
use backend\models\OrdersGoods;
use backend\models\Users;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use backend\components\Helpers;
use backend\models\MonetsTraffic;

class TablesController extends Controller
{

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
                    ->andWhere(['users.deleted' => false])
                    ->leftJoin("users_roles", "users_roles.user_id = users.id")
                    ->leftJoin("roles", "roles.id = users_roles.role_id")
                    ->groupBy('users.id')
                    ->limit($length)
                    ->offset($start)
                    ->all();
            }
            else if ($name == "customers") { //Producty
                $recordsTotal = Customers::find()->andWhere($query)->count();
                $recordsFiltered = Customers::find()->andWhere($condition)->andWhere($query)->andWhere($search_condition)->count();
                $model = (new \yii\db\Query())
                    ->select('`customers`.`id`,
                         `customers`.`name`,
                         `customers`.`created`'
                    )
                    ->from($table)
                    ->where($query)
                    ->andWhere($condition)
                    ->limit($length)
                    ->offset($start)
                    ->all();
            }
            else if ($name == "categories") { //Producty
                $recordsTotal = Categories::find()->andWhere($query)->count();
                $recordsFiltered = Categories::find()->andWhere($condition)->andWhere($query)->andWhere($search_condition)->count();
                $model = (new \yii\db\Query())
                    ->select('`categories`.`id`,
                         `categories`.`name`,
                         `categories`.`created`'
                    )
                    ->from($table)
                    ->where($query)
                    ->andWhere($condition)
                    ->limit($length)
                    ->offset($start)
                    ->all();
            }
            else if ($name == "goods") { //Producty
                $recordsTotal = Goods::find()->andWhere($query)->count();
                $recordsFiltered = Goods::find()->andWhere($condition)->andWhere($query)->andWhere($search_condition)->count();
                $model = (new \yii\db\Query())
                    ->select('`goods`.`id`,
                     `goods`.`name`,
                     `goods`.`price`,
                     `goods`.`quantity`,
                     `statuses`.`name` as status,
                     `goods`.`created`'
                    )
                    ->from($table)
                    ->where($query)
                    ->andWhere($condition)
                    ->innerJoin("statuses", "statuses.id = goods.status_id")
                    ->limit($length)
                    ->offset($start)
                    ->all();
            }
            else if ($name == "orders") {
                $recordsTotal = Orders::find()->andWhere($query)->count();
                $recordsFiltered = Orders::find()->andWhere($condition)->andWhere($query)->andWhere($search_condition)->count();
                $model = null;

                if(Yii::$app->session->get('profile_role') != 2){
                    $model = (new \yii\db\Query())
                    ->select('`orders`.`id`,
                         `customers`.`name` as customer,
                         `users`.`first_name` as first_name,
                         `users`.`last_name` as last_name,
                         `orders`.`created`'
                    )
                    ->from($table)
                    ->where($query)
                    ->andWhere($condition)
                    ->innerJoin("customers", "customers.id = orders.customer_id")
                    ->innerJoin("users", "users.id = orders.user_id")
                    ->limit($length)
                    ->offset($start)
                    ->all();

                }else{
                    $model = (new \yii\db\Query())
                        ->select('`orders`.`id`,
                         `customers`.`name` as customer,
                         `users`.`first_name` as first_name,
                         `users`.`last_name` as last_name,
                         `orders`.`created`'
                        )
                        ->from($table)
                        ->where($query)
                        ->andWhere( ['orders.user_id' => Yii::$app->session->get('profile_id')])
                        ->andWhere($condition)
                        ->innerJoin("customers", "customers.id = orders.customer_id")
                        ->innerJoin("users", "users.id = orders.user_id")
                        ->limit($length)
                        ->offset($start)
                        ->all();
                }
            }
            else if ($name == "debts") {
                $recordsTotal = Debts::find()->andWhere($query)->count();
                $recordsFiltered = Debts::find()->andWhere($condition)->andWhere($query)->andWhere($search_condition)->count();
                $model = (new \yii\db\Query())
                    ->select('`debts`.`id`,
                         `orders`.`id` as order,
                         `debts`.`amount`,
                         `debt_statuses`.`name` as status,
                         `customers`.`name`,
                         `debts`.`created`'
                    )
                    ->from($table)
                    ->where($query)
                    ->andWhere($condition)
                    ->innerJoin("orders", "orders.id = debts.order_id")
                    ->innerJoin("debt_statuses", "debt_statuses.id = debts.debt_status_id")
                    ->innerJoin("customers", "customers.id = orders.customer_id")
                    ->limit($length)
                    ->offset($start)
                    ->all();
            }
            else if ($name == "debt_returnings") {
                $recordsTotal = DebtReturnings::find()->andWhere($query)->count();
                $recordsFiltered = DebtReturnings::find()->andWhere($condition)->andWhere($query)->andWhere($search_condition)->count();
                $model = (new \yii\db\Query())
                    ->select('`debt_returnings`.`id`,
                         `debt_returnings`.`amount`,
                         `debt_returnings`.`created`,
                         `customers`.`name`'
                    )
                    ->from($table)
                    ->where($query)
                    ->andWhere($condition)
                    ->innerJoin("debts", "debts.id = debt_returnings.debt_id")
                    ->innerJoin("orders", "orders.id = debts.order_id")
                    ->innerJoin("customers", "customers.id = orders.customer_id")
                    ->limit($length)
                    ->offset($start)
                    ->all();
            }
            else if ($name == "print") {
                $recordsTotal = OrdersGoods::find()->where(['orders_goods.order_id' => $_GET['order_id']])->andWhere($query)->count();
                $recordsFiltered = OrdersGoods::find()->where(['orders_goods.order_id' => $_GET['order_id']])->andWhere($condition)->andWhere($query)->andWhere($search_condition)->count();
                $model = (new \yii\db\Query())
                    ->select('`goods`.`name` as name,
                         `orders_goods`.`quantity` as amount,
                         `goods`.`price` as price'
                    )
                    ->from($table)
                    ->where($query)
                    ->andWhere(['orders_goods.order_id' => $_GET['order_id']])
                    ->andWhere($condition)
                    ->innerJoin("goods", "goods.id = orders_goods.good_id")
                    ->limit($length)
                    ->offset($start)
                    ->all();
            }
//

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
