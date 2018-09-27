<?php

namespace backend\models;

use yii\db\ActiveRecord;


class DebtStatuses extends ActiveRecord
{

    public static function tableName() {
        return "debt_statuses";
    }

    public function rules()
    {
        return [
            [['id', 'name', 'quantity', 'created'], 'safe'],
        ];
    }
}
