<?php
namespace bricksasp\member\models\platform;

use Yii;
use yii\base\BaseObject;
use bricksasp\base\models\Setting;
use bricksasp\member\models\UserWx;
use bricksasp\base\Config as BaseConfig;


class Wechat extends BaseObject
{
    public $data;
    public $type;
    public $error;

    public function config()
    {
        $c = Setting::find()->where(['and', ['user_id' => $this->data['owner_id']], ['like', 'key', 'wx_']])->asArray()->all();
        if (!$c) {
            Tools::exceptionBreak(950002);
        }
        return array_column($c, 'val', 'key');
    }

    public function lite(){
        $config = $this->config();
// print_r($config);exit;
        // $appid = 'wx447138b1d90cde88';
        // $secret = '03350a53acbc1f5a42713f982c3242c7';
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$config['wx_applet_appid'].'&secret='.$config['wx_applet_secret'].'&js_code='.$code.'&grant_type=authorization_code';

        $client = new Client();
        $response = $client->createRequest()
            ->setHeaders(['content-type' => 'application/json'])
            ->setMethod('GET')
            ->setUrl($url)
            ->send();
        if ($response->isOk) {
            $res = $response->data;
        }else{
            Tools::exceptionBreak('980001');
        }

        if(!empty($res['errcode'])){
            Tools::exceptionBreak($res['errcode']);
        }
        return $res;
    }

    public function getData(){
        return call_user_func_array([$this,$this->type],[]);
    }
}