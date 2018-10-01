<?php

namespace backend\models;

use yii\db\ActiveRecord;


class UsersRoles extends ActiveRecord
{

    public static function tableName() {
        return "users_roles";
    }

    public static function tableFields(){
        return ['id', 'role_id', 'user_id', 'created'];
    }

    public function rules()
    {
        return [
            [['id', 'role_id', 'user_id', 'created'], 'safe'],
        ];
    }
}
