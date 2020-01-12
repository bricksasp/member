<?php

namespace bricksasp\member\models;

use Yii;

/**
 * This is the model class for table "{{%user_integral}}".
 *
 * @property int $user_id
 * @property int $integration 操作积分
 * @property int $score 消费积分
 * @property int $credit 信用积分
 */
class UserIntegral extends \bricksasp\base\BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_integral}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'owner_id', 'integration', 'score', 'credit'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'integration' => 'Integration',
            'score' => 'Score',
            'credit' => 'Credit',
        ];
    }
}
