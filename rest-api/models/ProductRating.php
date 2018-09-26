<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "product_rating".
 *
 * @property integer $id
 * @property integer $mobile_user_id
 * @property integer $product_id
 * @property integer $value
 * @property string $date
 */
class ProductRating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile_user_id', 'product_id', 'value'], 'required'],
            [['mobile_user_id', 'product_id'], 'integer'],
            [['value'], 'integer', 'integerOnly' => true, 'min' => 1, 'max' => 5],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile_user_id' => 'Mobile User ID',
            'product_id' => 'Product ID',
            'value' => 'Value',
            'date' => 'Date',
        ];
    }

    public static function isRatedBefore($mobile_user_id, $product_id)
    {
        if (static::findOne(['mobile_user_id' => $mobile_user_id, 'product_id' => $product_id])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
