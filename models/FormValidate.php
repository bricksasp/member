<?php
namespace bricksasp\member\models;

use Yii;

/**
 * This is the model class for Module form validate.
 */
class FormValidate extends \bricksasp\base\FormValidate
{
    const SESSIONKEY = 'sessionkey';
    const THREE_PARTY_LOGIN = 'threePartyLogin';
    const REGISTER_MOBILE = 'registerMobile';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['platform'], 'in', 'range'=> ['wechat', 'ali']],
            [['type'], 'in', 'range'=> ['lite', 'pub', 'litelogin', 'publogin', 'bind']],
            // [['platform', 'type'], 'required', 'on' => ['sessionkey', 'threePartyLogin']],
            [['code'], 'required', 'on' => ['sessionkey']],
            [['openid', 'iv', 'encryptedData'], 'required', 'on' => ['threePartyLogin']],
            [['platform'], 'default', 'value' => 'wechat'],
            [['type'], 'default', 'value' => 'lite'],
            [['mobile', 'vcode', 'uid'], 'required', 'on' => ['registerMobile']]
            
        ];
    }

    /**
     * 使用场景
     */
    public function scenarios()
    {
        return [
            self::SESSIONKEY => ['platform', 'type', 'code'],
            self::THREE_PARTY_LOGIN => ['platform', 'type', 'openid', 'iv', 'encryptedData'],
            self::REGISTER_MOBILE => ['platform', 'type', 'openid', 'iv', 'encryptedData'],
        ];
    }

    public function checkco()
    {
        if(!$this->cart && $this->products && !is_array($this->products)){
            $this->addError('products', 'cart || products 二选一必须为数组');
        }
    }
}