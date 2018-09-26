<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 17.01.2018
 * Time: 10:38
 */

namespace api\controllers;


use api\functions\Functions;
use backend\models\Cities;
use yii\web\Controller;

class CitiesController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $cities = Cities::find()
            ->asArray()
            ->all();

        return Functions::prepareResponse($cities);
    }
}