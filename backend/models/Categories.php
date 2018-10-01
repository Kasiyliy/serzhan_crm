<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Categories extends ActiveRecord{

    public static function tableName(){
        return 'categories';
    }

    public static function tableFields(){
        return ['id', 'name', 'deleted', 'created'];
    }

    public function rules(){
        return [
            [['id', 'name', 'deleted', 'created'], 'safe'],
        ];
    }
}