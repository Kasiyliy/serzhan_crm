<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Statuses extends ActiveRecord{

    public static function tableName() {
        return "statuses";
    }

    public function rules(){
        return [
           [['id' , 'name' , 'deleted', 'created'], 'safe'],
        ];
    }

}