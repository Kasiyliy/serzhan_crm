<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 17.01.2018
 * Time: 12:00
 */

namespace api\controllers;

use api\functions\Functions;
use Yii;
use yii\web\Controller;

class CategoriesController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isGet) {
            $section_id = Yii::$app->request->get('section_id');
            settype($section_id, 'INTEGER');
            if (!empty($section_id)) {
                return Functions::prepareResponse(Functions::getCategoriesSubcategories($section_id));
            } else {
                return Functions::missingParameter(['section_id']);
            }
        } else {
            return Functions::methodNotAllowedResponse('GET');
        }
    }

    public function actionAllSections()
    {
        if (Yii::$app->request->isGet) {
            return Functions::prepareResponse(Functions::getCategoriesSubcategories());
        } else {
            return Functions::methodNotAllowedResponse('GET');
        }
    }

    public function actionShopCategories()
    {
        if (Yii::$app->request->isGet) {
            $shop_id = Yii::$app->request->get('shop_id');
            settype($shop_id, 'INTEGER');
            if (!empty($shop_id)) {
                return Functions::prepareResponse(Functions::getShopCategoriesSubcategories($shop_id));
            } else {
                return Functions::missingParameter(['shop_id']);
            }
        } else {
            return Functions::methodNotAllowedResponse('GET');
        }
    }
}
