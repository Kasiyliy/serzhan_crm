<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 17.01.2018
 * Time: 10:42
 */

namespace api\functions;


use backend\models\Categories;
use backend\models\ProductsList;
use backend\models\Subcategories;

class Functions
{
    public static function prepareResponse($data, $status_code = 'Не найдено')
    {
        if(empty($data)) {
            $response[0]['message'] = $status_code;
            $response[0]['status'] = '404';
            \Yii::$app->response->statusCode = 404;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $response;
        } else {
            \Yii::$app->response->statusCode = 200;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $data;
        }
    }

    public static function badRequestResponse($message = 'отсутсвует ID города')
    {
        $response[0]['message'] = $message;
        $response[0]['status'] = '400';
        \Yii::$app->response->statusCode = 400;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }

    public static function notAuthorizedResponse()
    {
        $response['status'] = '401';
        $response['message'] = 'unauthorized';
        \Yii::$app->response->statusCode = 401;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }

    public static function methodNotAllowedResponse($method = 'POST')
    {
        $response['status'] = 405;
        $response['message'] = "Method Not Allowed, Allowed Methods: " . $method;
        \Yii::$app->response->statusCode = 405;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }

    public static function authKeyNotFound()
    {
        $response['status'] = 400;
        $response['message'] = "Auth Key Not Found";
        \Yii::$app->response->statusCode = 400;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }

    public static function missingParameter($parameters_array)
    {
        $response['status'] = 400;
        $response['message'] = implode(', ', $parameters_array) . " is not sent or empty.";
        \Yii::$app->response->statusCode = 400;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }

    public static function prepareSerializedData($array)
    {
        for($i=0; $i<count($array); $i++) {
            if($array[$i]['product_imgs'] != NULL)
                $array[$i]['product_imgs'] = implode(unserialize($array[$i]['product_imgs']));
            if($array[$i]['product_parameters'] != NULL)
                $array[$i]['product_parameters'] = unserialize($array[$i]['product_parameters']);
            if($array[$i]['product_parameter'] != NULL)
                $array[$i]['product_parameter'] = unserialize($array[$i]['product_parameter']);
        }
        return $array;
    }

    public static function selectProduct() {
        $products = ProductsList::find()
            ->select(
                '`product_list_id`,
                    `products`.`product_id`,
                    `shops`.`shop_id`,
                    `shops`.`shop_name`,
                    `subcategories`.`section_name`,
                    `products_list`.`section_id`,
                    `products_list`.`category_id`,
                    `products_list`.`subcategory_id`,
                    `product_list_count`,
                    `products`.`product_name`,
                    `products`.`product_main_img`,
                    `products`.`product_imgs`,
                    `products`.`product_rating`,
                    `products`.`product_price`,
                    `products`.`product_parameters`,
                    `products`.`product_description`'
            )
            ->innerJoin('products', '`products_list`.`product_id` = `products`.`product_id`')
            ->innerJoin('shops', '`products_list`.`shop_id` = `shops`.`shop_id`')
            ->innerJoin('subcategories', '`products_list`.`category_id` = `subcategories`.`category_id`')
            ->asArray();
        return $products;
    }

    public static function getCategoryStructure($section_id)
    {
        $categories = Subcategories::find()->where(['section_id' => $section_id])->asArray()->groupBy('category_id')->all();
        for($i=0; $i<count($categories); $i++) {
            $structure[$categories[$i]['category_id']]['name'] = $categories[$i]['category_name'];
        }

        $subcategories = Subcategories::find()->where(['section_id' => $section_id])->asArray()->orderBy('category_id')->all();

        for($i=0; $i<count($subcategories); $i++) {
            $structure[$subcategories[$i]['category_id']]['subcategories'][$subcategories[$i]['subcategory_id']] = $subcategories[$i]['subcategory_name'];
        }
        for($i=0; $i<count($subcategories); $i++) {
            $categories[$subcategories[$i]['category_name']][] = $subcategories[$i]['subcategory_name'];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $structure;
    }

    public static function getSections() {
        $sections = Subcategories::find()
            ->select(
                '`sections`.`section_id`,
                `sections`.`section_name`,
                `sections`.`section_image`'
            )
            ->innerJoin('sections', '`subcategories`.`section_id` = `sections`.`section_id`')
            ->asArray()
            ->groupBy('section_id')
            ->all();
        return $sections;
    }

    public static function getCategoriesSubcategories($section_id = NULL)
    {
        if ($section_id) {
            $categories = Subcategories::find()->where(['section_id' => $section_id])->orderBy('category_id')->all();
        } else {
            $categories = Subcategories::find()->orderBy('category_id')->all();
        }
        $rows = count($categories);
        $ind = 0;
        $sub = 0;
        $last_category_id = 0;
        $last_category_name = '';
        for ($i = 0; $i < $rows; $i ++) {
            if ($i == 0) {
                $structure[$ind]['category_id'] = $categories[$i]['category_id'];
                $structure[$ind]['category_name'] = $categories[$i]['category_name'];
            } elseif ($last_category_id == $categories[$i]['category_id']) {
                $structure[$ind]['category_id'] = $last_category_id;
                $structure[$ind]['category_name'] = $last_category_name;
            } else {
                $ind ++;
                $sub = 0;
            }
            if ($rows - $i == 1) {
                $structure[$ind]['category_id'] = $categories[$i]['category_id'];
                $structure[$ind]['category_name'] = $categories[$i]['category_name'];
            }
            $structure[$ind]['subcategories'][$sub]['subcategory_id'] = $categories[$i]['subcategory_id'];
            $structure[$ind]['subcategories'][$sub]['subcategory_name'] = $categories[$i]['subcategory_name'];

            $sub ++;
            $last_category_id = $categories[$i]['category_id'];
            $last_category_name = $categories[$i]['category_name'];
        }
        return $structure;
    }

    public static function getShopCategoriesSubcategories($shop_id)
    {
        $shop_subcategories = ProductsList::find()
            ->select('subcategory_id')
            ->where(['shop_id' => $shop_id])
            ->groupBy('subcategory_id')
            ->all();

        $categories = Subcategories::find()
            ->where(['subcategory_id' => $shop_subcategories])
            ->orderBy('subcategory_id')
            ->all();

        $rows = count($categories);
        $ind = 0;
        $sub = 0;
        $last_category_id = 0;
        $last_category_name = '';
        for ($i = 0; $i < $rows; $i ++) {
            if ($i == 0) {
                $structure[$ind]['category_id'] = $categories[$i]['category_id'];
                $structure[$ind]['category_name'] = $categories[$i]['category_name'];
            } elseif ($last_category_id == $categories[$i]['category_id']) {
                $structure[$ind]['category_id'] = $last_category_id;
                $structure[$ind]['category_name'] = $last_category_name;
            } else {
                $ind ++;
                $sub = 0;
            }
            if ($rows - $i == 1) {
                $structure[$ind]['category_id'] = $categories[$i]['category_id'];
                $structure[$ind]['category_name'] = $categories[$i]['category_name'];
            }
            $structure[$ind]['subcategories'][$sub]['subcategory_id'] = $categories[$i]['subcategory_id'];
            $structure[$ind]['subcategories'][$sub]['subcategory_name'] = $categories[$i]['subcategory_name'];

            $sub ++;
            $last_category_id = $categories[$i]['category_id'];
            $last_category_name = $categories[$i]['category_name'];
        }
        return $structure;
    }
}
