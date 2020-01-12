<?php

namespace bricksasp\member\models;

use Yii;

/**
 * This is the model class for table "{{%user_fund}}".
 *
 * @property int $user_id
 * @property string $amount 余额可提现
 * @property string $discount_amount 优惠金不可提
 */
class UserFund extends \bricksasp\base\BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_fund}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'owner_id'], 'integer'],
            [['amount', 'discount_amount'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'amount' => 'Amount',
            'discount_amount' => 'Discount Amount',
        ];
    }
}
