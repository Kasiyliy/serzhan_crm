<?php
namespace backend\controllers;

use backend\components\Helpers;
use yii\web\Response;
use Yii;
use yii\web\Controller;
use backend\models\Users;
use backend\models\TaxiPark;


class LoadajaxController extends Controller
{
    public function actionGetpage() {
        if (Yii::$app->request->isAjax) {
            if (Helpers::CheckAuth("check", null)) {
                $page = $_POST['page'];
                Yii::$app->session->set('navigation_page', $page);
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'back',
                    'value' => $page
                ]));

                if ($page == "account") {
                    $model = Users::find()->where(['id' => Yii::$app->session->get('profile_id')])->one();
                }

                if (Helpers::GetPageAccess($page)) {
                    return $this->renderPartial('/tables/' . $page . '/index', array('model' => $model, 'page' => $page));
                } else {
                    return $this->renderPartial('/system/access-denied');
                }
            } else {
                return 101;
            }
        }
    }

    public function actionGetaction() {
        if (Yii::$app->request->isAjax) {
            if (Helpers::CheckAuth("check", null)) {
                if ($_POST['id'] != null) {
                    $id = $_POST['id'];
                } else {
                    $id = 0;
                }
                $security = true;
                $page = $_POST['page'];
                if ($page == "users/form-user") {
                    $model = Users::find()->where(['id' => $id])->one();
                } else if ($page == "customers/form-customer") {
                    $model = Users::find()->where(['id' => $id])->one();
                }
                else {
                    $model = null;
                }

                Yii::$app->session->set('navigation_page', $page);
                if ($model != null) {
                    return $this->renderPartial('/tables/' . $page, array("model" => $model, "security" => $security));
                } else {
                    return $this->renderPartial('/tables/' . $page, array("security" => $security, 'model' => null));
                }
            } else {
                return 101;
            }
        }
    }

    public function actionFiltrlink() {
        if (Yii::$app->request->isAjax) {
            if (Helpers::CheckAuth("check", null)) {
                $id = $_POST['id'];
                $page = $_POST['page'];
                $array = Yii::$app->session->get('filtr');
                if ($page == "sellers") {
                    $array[$page]['rod_id'] = $id;
                } else if ($page == "shops") {
                    $array[$page]['user_id'] = $id;
                } else if ($page == "products") {
                    $array[$page]['shop_id'] = $id;
                }
                Yii::$app->session->set('filtr', $array);
                $response['type'] = "success";
            } else {
                $response['type'] = "information";
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }
    }

}
