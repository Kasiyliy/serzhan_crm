<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "mobile_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string $city_id
 * @property string $address
 * @property integer $auth_key
 * @property string $date
 */
class MobileUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mobile_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'phone', 'password', 'city_id'], 'required'],
            [['date'], 'safe'],
            [['city_id'], 'integer'],
            [['username', 'email'], 'string', 'max' => 300],
            [['phone'], 'string', 'max' => 50],
            ['phone', 'unique', 'message' => 'Phone must be unique.'],
            ['email', 'unique', 'message' => 'Email must be unique.'],
            [['password'], 'string', 'min' => 6],
            [['password'], 'string', 'max' => 150],
            [['address'], 'string', 'max' => 400],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'phone' => 'Phone',
            'password' => 'Password',
            'address' => 'Address',
            'city_id' => 'City ID',
            'auth_key' => 'auth_key',
            'date' => 'Date',
        ];
    }

    public function register()
    {
        if($this->validate()) {
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $this->auth_key = Yii::$app->security->generateRandomString();
            if($this->save()) {
                return $this;
            } else {
                return NULL;
            }
        }
    }

    public function getIdentityByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
        if($this->save()) {
            return $this->auth_key;
        } else {
            return NULL;
        }
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function getIdentityByAuthKey($auth_key)
    {
        return static::findOne(['auth_key' => $auth_key]);
    }
}
