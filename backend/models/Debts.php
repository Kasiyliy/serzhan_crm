<?php

namespace backend\models;

use yii\db\ActiveRecord;


class Debts extends ActiveRecord
{

    public static function tableName() {
        return "debts";
    }

    public function rules()
    {
        return [
            [['id', 'order_id', 'amount', 'debt_status_id','deleted', 'created'], 'safe'],
        ];
    }
}
