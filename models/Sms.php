<?php

namespace bricksasp\member\models;

use Yii;
use bricksasp\helpers\Tools;

/**
 * This is the model class for table "{{%sms}}".
 *
 */
class Sms extends \bricksasp\base\BaseActiveRecord
{
    const TYPE_VCODE = 1;//验证码
    const STATUS_NO_SEND = 0; //未发送
    const STATUS_SEND = 1; //已发送
    const STATUS_USED = 2; //已使用

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sms}}';
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
            [['mobile', 'status', 'type', 'created_at', 'updated_at'], 'integer'],
            [['message'], 'string', 'max' => 255],
            [['message', 'mobile', ], 'required'],
            [['type'], 'default', 'value' => 1],
            [['status'], 'default', 'value' => 0] //使用状态0未发送1已发送2已使用
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'status' => 'Status',
            'message' => 'Message',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 发送短信
     * @param  int $mobile 
     * @param  string $message 
     * @param  int $type
     * @return array
     */
    public function sendsms($mobile, $message, $type=self::TYPE_VCODE)
    {
        if ($this->load(['message' => $message, 'mobile'=>$mobile,'type' => $type]) && $this->save()) {
            // 发短信
            if ($type == self::TYPE_VCODE) {
                $message = Yii::t('base', '980008', $message);
            }

            $this->status = self::STATUS_SEND;
            $this->save();
            return true;
        }
        return $this->errors;
    }

    /**
     * 验证码验证
     * @param  int $mobile 
     * @param  int $code   
     * @return array         
     */
    public function verificationCode($mobile, $code)
    {
        $model = $this::find()->where(['mobile' => $mobile, 'message' => $code, 'status' => self::STATUS_SEND])->one();
        if ($model && (time() - $model->created_at < 3000)) {
            $model->status = self::STATUS_USED;
            // $model->save();
            return true;
        }
        Tools::exceptionBreak(980009);
    }
}
