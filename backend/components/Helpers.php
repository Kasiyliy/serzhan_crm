<?php
    namespace backend\components;
    use backend\models\Dealers;
use Yii;

    class Helpers {
        public static function GeneratePassword() {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $password = substr( str_shuffle( $chars ), 0, 8 );
            return $password;
        }

        public static function CheckAuth($type, $link) {
            if (Yii::$app->session->get('profile_auth') == "OK" AND Yii::$app->session->get('profile_ip') == $_SERVER['REMOTE_ADDR']) {
                $auth = true;
            } else {
                $auth = false;
            }
            if ($type == "redirect") {
                if ($auth == true) {
                    return Yii::$app->response->redirect($link);
                }
            } else if ($type == "no-redirect") {
                if ($auth == false) {
                    return Yii::$app->response->redirect("/profile/authentication/");
                }
            } else if ($type == "check") {
                return $auth;
            }
        }

        public static function SetBack($page) {
            $backs = Yii::$app->session->get('navigation_back');
            $backs[] = $page;
            return $backs;
        }

        public static function GetConfig($table, $type) {
            $array = null;
            switch ($table) {
                case "moderators":
                    $array = array (
                        'select_fields' => ['id', 'name', 'phone', 'email',  'last_edit', 'created'],
                    );
                    break;
                case "traffic":
                    $array = array (
                        'select_fields' => ['id', 'sender_user_id', 'sender_tp_id', 'reciever_user_id',  'reciever_tp_id', 'amount', 'date'],
                    );
                    break;
                case "cashiers":
                    $array = array (
                        'select_fields' => ['id', 'name', 'phone', 'email',  'last_edit', 'created'],
                    );
                    break;
                case "admins":
                    $array = array (
                        'select_fields' => ['id', 'name', 'phone', 'email',  'last_edit', 'created'],
                    );
                    break;
                case "users":
                    $array = array (
                        'select_fields' => ['id', 'name', 'phone', 'email',  'last_edit', 'created', 'is_active'],
                    );
                    break;
                case "tadmins":
                    $array = array (
                        'select_fields' => ['id', 'name', 'phone', 'email',  'last_edit', 'created', 'taxi_park_id'],
                    );
                    break;
                case "taxi-parks":
                    $array = array (
                        'select_fields' => ['id', 'name', 'type', 'city_id',  'balance'],
                        'filtr' => array (
                            'type' => array (
                                'label' => 'Тип оплаты',
                                'type' => 'static',
                                'icon' => 'icon-check',
                                'data' => array(
                                    '1' => 'tip 1',
                                    '3' => 'tip 3'
                                ),
                            ),
                        )
                    );
                    break;
                case "cashier":
                    $array = array (
                        'select_fields' => ['id', 'name', 'type', 'city_id',  'balance'],
                        'filtr' => array (
                            'type' => array (
                                'label' => 'Тип оплаты',
                                'type' => 'static',
                                'icon' => 'icon-check',
                                'data' => array(
                                    '1' => 'tip 1',
                                    '3' => 'tip 3'
                                ),
                            ),
                        )
                    );
                    break;
                case "drivers":
                    $array = array (
                        'select_fields' => ['id', 'name', 'phone', 'email',  'taxi_park_id', 'created', 'is_active', 'balance'],
                    );
                    break;

                default:
                    $array = null;
                    break;
            }
            return $array[$type];
        }

        public static function GetRangeAccess($roles) {
    
            return true;
        }

        public static function GetPageAccess($page) {
 
            return true;
        }

        public static function GetTransliterate($s) {
            $s = (string) $s;
            $s = strip_tags($s);
            $s = str_replace(array("\n", "\r"), " ", $s);
            $s = preg_replace("/\s+/", ' ', $s);
            $s = trim($s);
            $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s);
            $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
            $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s);
            $s = str_replace(" ", "-", $s);
            return $s;
        }
    }
?>
