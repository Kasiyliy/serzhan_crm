<?php

namespace api\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $order_group_id
 * @property integer $product_id
 * @property integer $product_price
 * @property integer $product_count
 * @property integer $product_summ
 * @property string $product_parameter
 * @property integer $status
 * @property string $created_date
 * @property string $updated_date
 */
class Orders extends \yii\db\ActiveRecord
{
    const STATUS_ORDERED = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_date',
                'updatedAtAttribute' => 'updated_date',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'product_price', 'product_count', 'product_summ', 'status'], 'required'],
            [['order_group_id', 'product_id', 'product_price', 'product_count', 'product_summ', 'status'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['product_parameter'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_group_id' => 'Order Group ID',
            'product_id' => 'Product ID',
            'product_price' => 'Product Price',
            'product_count' => 'Product Count',
            'product_summ' => 'Product Summ',
            'product_parameter' => 'Product Parameter',
            'status' => 'Status',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
        ];
    }

    public static function getOrderGroupIdByOrderId($order_id)
    {
        $order_group_id = static::find()
            ->select('order_group_id')
            ->where(['id' => $order_id])
            ->one();
        if ($order_group_id) {
            return $order_group_id;
        } else {
            return NULL;
        }
    }
}
