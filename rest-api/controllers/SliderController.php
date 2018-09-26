<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 17.01.2018
 * Time: 10:37
 */

namespace api\controllers;


use api\functions\Functions;
use backend\models\SliderImages;
use yii\web\Controller;

class SliderController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $city_id = \Yii::$app->request->get('city_id');
        if(empty($city_id)) {
            return Functions::badRequestResponse();
        } else {
            $slider_images = SliderImages::find()
                ->where(['city_id' => $city_id])
                ->asArray()
                ->all();
            return Functions::prepareResponse($slider_images);
        }
    }
}