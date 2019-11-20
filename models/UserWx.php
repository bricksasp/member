<?php
namespace bricksasp\member\models;

use Yii;

/**
 * This is the model class for table "{{%user_wx}}".
 *
 */
class UserWx extends \bricksasp\base\BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_wx}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'type', 'gender', 'created_at', 'updated_at'], 'integer'],
            [['openid', 'session_key', 'unionid', 'nickname', 'language'], 'string', 'max' => 50],
            [['avatar'], 'string', 'max' => 255],
            [['city', 'province', 'country'], 'string', 'max' => 80],
            [['country_code', 'mobile'], 'string', 'max' => 20],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'type' => 'Type',
            'openid' => 'Openid',
            'session_key' => 'Session Key',
            'unionid' => 'Unionid',
            'avatar' => 'Avatar',
            'nickname' => 'Nickname',
            'gender' => 'Gender',
            'language' => 'Language',
            'city' => 'City',
            'province' => 'Province',
            'country' => 'Country',
            'country_code' => 'Country Code',
            'mobile' => 'Mobile',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
