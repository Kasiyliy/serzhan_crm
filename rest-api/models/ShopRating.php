<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "shop_rating".
 *
 * @property integer $id
 * @property integer $mobile_user_id
 * @property integer $shop_id
 * @property integer $value
 * @property string $date
 */
class ShopRating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile_user_id', 'shop_id', 'value'], 'required'],
            [['mobile_user_id', 'shop_id', 'value'], 'integer'],
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
            'shop_id' => 'Shop ID',
            'value' => 'Value',
            'date' => 'Date',
        ];
    }

    public static function isRatedBefore($mobile_user_id, $shop_id)
    {
        if (static::findOne(['mobile_user_id' => $mobile_user_id, 'shop_id' => $shop_id])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
