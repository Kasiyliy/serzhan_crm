<?php

namespace api\models;

use backend\models\SellersInfo;
use backend\models\Shops;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "order_group".
 *
 * @property integer $id
 * @property integer $count
 * @property integer $overall_summ
 * @property string $address
 * @property string $description
 * @property integer $mobile_user_id
 * @property integer $shop_id
 * @property integer $status
 * @property string $updated_date
 * @property string $created_date
 */
class OrderGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_group';
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
            [['count', 'overall_summ', 'address', 'mobile_user_id', 'shop_id', 'status'], 'required'],
            [['count', 'overall_summ', 'mobile_user_id', 'shop_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['updated_date', 'created_date'], 'safe'],
            [['address'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'count' => 'Count',
            'overall_summ' => 'Overall Summ',
            'address' => 'Address',
            'description' => 'Description',
            'mobile_user_id' => 'Mobile User ID',
            'shop_id' => 'Shop ID',
            'status' => 'Status',
            'updated_date' => 'Updated Date',
            'created_date' => 'Created Date',
        ];
    }

    public static function isSelfOrder($mobile_user_id, $order_group_id)
    {
        if (static::findOne(['mobile_user_id' => $mobile_user_id, 'id' => $order_group_id])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function getDealersOverallSum($id)
    {
        $shops = SellersInfo::getShopIdsOfDealer($id);

        if (!$shops) {
            return 0;
        }

        $overall_summ = static::find()
            ->select('overall_summ')
            ->where(['shop_id' => $shops])
            ->asArray()
            ->all();

        for ($i = 0; $i < count($overall_summ); $i ++) {
            $overall_summ[$i] = $overall_summ[$i]['overall_summ'];
        }

        if (!$overall_summ) {
            return 0;
        }

        $overall_summ = array_sum($overall_summ);

        return $overall_summ;
    }

    public static function getSellersOverallSum($id)
    {
        $shop = Shops::getShopIdByUserId($id);

        if (!$shop) {
            return 0;
        }

        $overall_summ = static::find()
            ->select('overall_summ')
            ->where(['shop_id' => $shop])
            ->asArray()
            ->all();

        for ($i = 0; $i < count($overall_summ); $i ++) {
            $overall_summ[$i] = $overall_summ[$i]['overall_summ'];
        }

        if (!$overall_summ) {
            return 0;
        }

        $overall_summ = array_sum($overall_summ);

        return $overall_summ;
    }

    public static function getOrdersCount($seller_id)
    {
        $shop_id = Shops::getShopIdByUserId($seller_id);
        if ($shop_id) {
            $orders_count = OrderGroup::find()
                ->where(['shop_id' => $shop_id])
                ->count();
        } else {
            $orders_count = 0;
        }
        return $orders_count;
    }
}
