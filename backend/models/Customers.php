<?php

namespace backend\models;

use yii\db\ActiveRecord;


class Customers extends ActiveRecord
{

    public static function tableName() {
        return "customers";
    }

    public static function tableFields(){
        return ['id', 'name', 'deleted', 'created'];
    }

    public function rules()
    {
        return [
            [['id', 'name', 'deleted', 'created'], 'safe'],
        ];
    }
}
