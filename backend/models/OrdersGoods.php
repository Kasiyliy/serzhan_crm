<?php
namespace backend\models;
use yii\db\ActiveRecord;

class OrdersGoods extends ActiveRecord {

    public static function tableName(){
        return 'orders_goods';
    }

    public static function tableFields(){
        return ['id' , 'order_id' , 'good_id' , 'quantity', 'created'];
    }

    public function rules(){
        return [
          [['id' , 'order_id' , 'good_id' , 'quantity', 'created'],'safe'],
        ];
    }
}