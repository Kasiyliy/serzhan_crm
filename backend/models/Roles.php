<?php

namespace backend\models;

use yii\db\ActiveRecord;


class Roles extends ActiveRecord
{

    public static function tableName() {
        return "roles";
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
