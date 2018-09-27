<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Orders extends ActiveRecord {

    public static function tableName(){
        return 'orders';
    }

    public function rules(){
        return [
          [['id' , 'customer_id','user_id' , 'deleted' ,'created'],'safe'],
        ];
    }
}