<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 17.01.2018
 * Time: 12:44
 */

namespace api\controllers;

use api\functions\Functions;
use backend\models\Products;
use backend\models\ProductsList;
use Yii;
use yii\web\Controller;

class ProductsController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionSection()
    {
        $city_id = \Yii::$app->request->get('city_id');
        $section_id = \Yii::$app->request->get('section_id');
        if(empty($city_id)) {
            return Functions::badRequestResponse();
        } elseif(empty($section_id)) {
            return Functions::badRequestResponse('Отсутсвует ID раздела');
        } else {
            $products = Functions::selectProduct();
            $products = $products
                ->where([
                    '`products_list`.`city_id`' => $city_id,
                    '`products_list`.`section_id`' => $section_id,
                    '`products_list`.`product_list_status`' => 1
                ])
                ->all();
            $products = Functions::prepareSerializedData($products);
            return Functions::prepareResponse($products);
        }
    }

    public function actionShop()
    {
        $city_id = \Yii::$app->request->get('city_id');
        $shop_id = \Yii::$app->request->get('shop_id');
        if(empty($city_id)) {
            return Functions::badRequestResponse();
        } elseif(empty($shop_id)) {
            return Functions::badRequestResponse('Отсутсвует ID магазина');
        } else {
            $products = Functions::selectProduct();
            $products = $products
                ->where(['`products_list`.`city_id`' => $city_id,
                    '`products_list`.`shop_id`' => $shop_id,
                    '`products_list`.`product_list_status`' => 1
                ])
                ->all();
            $products = Functions::prepareSerializedData($products);
            return Functions::prepareResponse($products);
        }
    }

    public function actionShopSection()
    {
        $city_id = \Yii::$app->request->get('city_id');
        $shop_id = \Yii::$app->request->get('shop_id');
        $section_id = \Yii::$app->request->get('section_id');
        if(empty($city_id)) {
            return Functions::badRequestResponse();
        } elseif(empty($shop_id)) {
            return Functions::badRequestResponse('Отсутсвует ID магазина');
        } elseif(empty($section_id)) {
            return Functions::badRequestResponse('Отсутсвует ID Раздела');
        } else {
            $products = Functions::selectProduct();
            $products = $products
                ->where([
                    '`products_list`.`city_id`' => $city_id,
                    '`products_list`.`shop_id`' => $shop_id,
                    '`products_list`.`section_id`' => $section_id,
                    '`products_list`.`product_list_status`' => 1
                ])
                ->all();
            $products = Functions::prepareSerializedData($products);
            return Functions::prepareResponse($products);
        }
    }

    public function actionShopSubcategory()
    {
        $city_id = \Yii::$app->request->get('city_id');
        $shop_id = \Yii::$app->request->get('shop_id');
        $subcategory_id = \Yii::$app->request->get('subcategory_id');
        settype($city_id, 'INTEGER');
        settype($shop_id, 'INTEGER');
        settype($section_id, 'INTEGER');
        if(empty($city_id)) {
            return Functions::badRequestResponse();
        } elseif(empty($shop_id)) {
            return Functions::badRequestResponse('Отсутсвует ID магазина');
        } elseif(empty($subcategory_id)) {
            return Functions::badRequestResponse('Отсутсвует ID Подкатегории');
        } else {
            $products = Functions::selectProduct();
            $products = $products
                ->where([
                    '`products_list`.`city_id`' => $city_id,
                    '`products_list`.`shop_id`' => $shop_id,
                    '`products_list`.`subcategory_id`' => $subcategory_id,
                    '`products_list`.`product_list_status`' => 1
                ])
                ->all();
            $products = Functions::prepareSerializedData($products);
            return Functions::prepareResponse($products);
        }
    }

    public function actionCategoryProducts()
    {
        if (Yii::$app->request->isGet) {
            $category_id = Yii::$app->request->get('category_id');
            $city_id = Yii::$app->request->get('city_id');
            settype($category_id, 'INTEGER');
            settype($city_id, 'INTEGER');
            if (empty($category_id)) {
                return Functions::missingParameter(['category_id']);
            } elseif (empty($category_id)) {
                return Functions::missingParameter(['city_id']);
            } else {
                $products = Functions::selectProduct()
                    ->where([
                        '`products_list`.`category_id`' => $category_id,
                        '`products_list`.`city_id`' => $city_id,
                        '`products_list`.`product_list_status`' => 1
                    ])
                    ->all();
                $products = Functions::prepareSerializedData($products);
                return Functions::prepareResponse($products);
            }
        } else {
            return Functions::methodNotAllowedResponse('GET');
        }
    }

    public function actionSubcategoryProducts()
    {
        if (Yii::$app->request->isGet) {
            $subcategory_id = Yii::$app->request->get('subcategory_id');
            $city_id = Yii::$app->request->get('city_id');
            settype($subcategory_id, 'INTEGER');
            settype($city_id, 'INTEGER');
            if (empty($city_id)) {
                return Functions::missingParameter(['city_id']);
            }
            if (empty($subcategory_id)) {
                return Functions::missingParameter(['subcategory_id']);
            } else {
                $products = Functions::selectProduct()
                    ->where([
                        '`products_list`.`subcategory_id`' => $subcategory_id,
                        '`products_list`.`city_id`' => $city_id,
                        '`products_list`.`product_list_status`' => 1
                    ])
                    ->all();
                $products = Functions::prepareSerializedData($products);
                return Functions::prepareResponse($products);
            }
        } else {
            return Functions::methodNotAllowedResponse('GET');
        }
    }

    public function actionSearch()
    {
        if (Yii::$app->request->isGet) {
            $query = Yii::$app->request->get('query');
            $city_id = Yii::$app->request->get('city_id');
            $section_id = Yii::$app->request->get('section_id');
            $subcategory_id = Yii::$app->request->get('subcategory_id');
            settype($city_id, 'INTEGER');
            settype($section_id, 'INTEGER');
            settype($subcategory_id, 'INTEGER');

            if (!empty($query) && !empty($section_id) && !empty($city_id)) {
                $products = Functions::selectProduct()
                    ->where(['LIKE', '`products`.`product_name`', $query])
                    ->orWhere(['LIKE', '`products`.`product_description`', $query])
                    ->orWhere(['LIKE', '`products`.`product_price`', $query])
                    ->andWhere(['`products_list`.`section_id`' => $section_id, '`products_list`.`city_id`' => $city_id])
                    ->all();
                return Functions::prepareResponse($products);
            } elseif (!empty($query) && !empty($subcategory_id) && !empty($city_id)) {
                $products = Functions::selectProduct()
                    ->where(['LIKE', '`products`.`product_name`', $query])
                    ->orWhere(['LIKE', '`products`.`product_description`', $query])
                    ->orWhere(['LIKE', '`products`.`product_price`', $query])
                    ->andWhere(['`products_list`.`subcategory_id`' => $subcategory_id, '`products_list`.`city_id`' => $city_id])
                    ->all();
                return Functions::prepareResponse($products);
            } elseif (!empty($query) && !empty($city_id)) {
                $products = Functions::selectProduct()
                    ->where(['LIKE', '`products`.`product_name`', $query])
                    ->orWhere(['LIKE', '`products`.`product_description`', $query])
                    ->orWhere(['LIKE', '`products`.`product_price`', $query])
                    ->andWhere(['`products_list`.`city_id`' => $city_id])
                    ->all();
                return Functions::prepareResponse($products);
            } elseif (empty($query)) {
                return Functions::missingParameter(['query']);
            } elseif (empty($city_id)) {
                return Functions::missingParameter(['city_id']);
            }
        } else {
            return Functions::methodNotAllowedResponse('GET');
        }
    }
}
