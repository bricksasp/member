<?php
namespace bricksasp\member\models;

use Yii;
use bricksasp\helpers\Tools;

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

    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['openid', 'owner_id'], 'required'],
            [['user_id', 'type', 'gender', 'created_at', 'updated_at'], 'integer'],
            [['openid', 'session_key', 'unionid', 'nickname', 'language'], 'string', 'max' => 64],
            [['avatar'], 'string', 'max' => 255],
            [['city', 'province', 'country'], 'string', 'max' => 128],
            [['country_code', 'mobile'], 'string', 'max' => 20],
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
