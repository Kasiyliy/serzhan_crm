<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 17.01.2018
 * Time: 10:10
 */

namespace api\controllers;

use yii\web\Controller;
use api\functions\Functions;

class SectionsController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return Functions::prepareResponse(Functions::getSections());
    }
}