<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Returnings extends ActiveRecord {
    public static function tableName(){
        return 'returnings';
    }

    public function rules(){
        return [
          [['id' , 'orders_good_id', 'quantity','created' ],'safe']
        ];
    }
}