<?php

namespace backend\models;

use yii\db\ActiveRecord;


class Users extends ActiveRecord
{

    public static function tableName() {
        return "users";
    }

    public function rules()
    {
        return [
            [['id', 'first_name', 'last_name', 'login', 'password', 'phone_number', 'deleted', 'created'], 'safe'],
        ];
    }
}
