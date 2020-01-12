<?php

namespace bricksasp\member\models;

use Yii;

/**
 * This is the model class for table "{{%user_integral_log}}".
 *
 * @property int $user_id
 * @property int $owner_id
 * @property string $point
 * @property int $type
 * @property int $created_at
 */
class UserIntegralLog extends \bricksasp\base\BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_integral_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'owner_id', 'type', 'created_at'], 'integer'],
            [['point'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'owner_id' => 'Owner ID',
            'point' => 'Point',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }
}
