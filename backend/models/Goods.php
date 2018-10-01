<?php

namespace backend\models;

use yii\db\ActiveRecord;

class Goods extends ActiveRecord {

    public static function tableName() {
        return "goods";
    }

    public static function tableFields(){
        return ['id', 'name', 'category_id', 'price' , 'quantity', 'status_id','deleted' ,'created'];
    }

    public function rules()
    {
        return [
            [['id', 'name', 'category_id', 'price' , 'quantity', 'status_id','deleted' ,'created'], 'safe'],
        ];
    }
}