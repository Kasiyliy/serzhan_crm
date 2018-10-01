<?php

namespace backend\models;

use yii\db\ActiveRecord;


class DebtReturnings extends ActiveRecord
{

    public static function tableName() {
        return "debt_returnings";
    }

    public static function tableFields(){
        return ['id', 'debt_id', 'amount', 'created'];
    }

    public function rules()
    {
        return [
            [['id', 'debt_id', 'amount', 'created'], 'safe'],
        ];
    }
}
