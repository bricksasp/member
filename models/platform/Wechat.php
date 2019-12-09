<?php
namespace bricksasp\member\models\platform;

use Yii;
use yii\base\BaseObject;
use bricksasp\base\models\Setting;
use bricksasp\member\models\UserWx;
use bricksasp\base\Config as BaseConfig;
use yii\httpclient\Client;
use bricksasp\helpers\Tools;
use bricksasp\rbac\models\User;
use bricksasp\rbac\models\UserInfo;


class Wechat extends BaseObject
{
    const TYPE_LITE = 1; //小程序
    const TYPE_PUB = 2; //公众号
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

    /**
     * 小程序sessionkey
     * @return array 
     */
    public function lite(){
        $config = $this->config();
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$config['wx_applet_appid'].'&secret='.$config['wx_applet_secret'].'&js_code='.  $this->data['code'] .'&grant_type=authorization_code';

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

        if(isset($res['unionid']) && $res['unionid']){
            $map['unionid'] = $res['unionid'];
        }else{
            $map['openid'] = $res['openid'];
        }
        $model = UserWx::find()->where($map)->one();
        if ($model) {
            $model->load($res);
            
            if (!$model->save()) Tools::exceptionBreak('更新失败');
        }else{
            $model = new UserWx();
            $data['owner_id'] = $this->data['owner_id'];
            $data['type'] = self::TYPE_LITE;
            $model->load(array_merge($data,$res));
            // print_r($res);
            if (!$model->save()) Tools::exceptionBreak('980002');
        }
        return $res;
    }

    /**
     * 小程序登录
     * @return array 
     */
    public function litelogin()
    {
        $model = UserWx::find()->where(['openid' => $this->data['openid']])->one();
        $res = self::decrypt($this->data['encryptedData'], $this->data['iv'], $model->session_key );
        // return $res;
        $model->load($this->formatData($res));
        $model->save();

        if ($model->user_id) {
            return ['token' => User::generateApiToken($model->user_id)];
        }
        return (object)['user_wx_id'=>$model->id];
    }

    /**
     * 绑定微信三方账户
     * @return array
     */
    public function bind()
    {
        $user = new User();
        $user->username = $this->data['mobile'];
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword(Yii::$app->security->generateRandomString(6));
        $user->generateAuthKey();

        $transaction = User::getDb()->beginTransaction();
            if (!$user->save()) {
                $transaction->rollBack();
                Tools::exceptionBreak('1');
            }
            $userInfo = new UserInfo();
            $userInfo->load(['user_id'=>$user->id, 'owner_id'=>$this->data['owner_id']]);
            if (!$userInfo->save()) {
                $transaction->rollBack();
                Tools::exceptionBreak('2');
            }
            UserWx::updateAll(['user_id'=>$user->id],['id'=>$this->data['uid']]);
            $transaction->commit();

            return ['token' => User::generateApiToken($user->id)];
        try {
        } catch(\Exception $e) {
            Tools::exceptionBreak('3');
            throw $e;
        } catch(\Throwable $e) {
            Tools::exceptionBreak('4');
            throw $e;
        }
    }

    public function formatData($data)
    {
        $data['avatar'] = $data['avatarUrl'];
        $data['nickname'] = $data['nickName'];
        $data['openid'] = $data['openId'];
        return $data;
    }

    /**
     * 小程序解码
     * @return array
     */
    public static function decrypt($encryptedData,$iv,$sessionKey)
    {
        if (strlen($sessionKey) != 24) {
            Tools::exceptionBreak('980004');
        }
        $aesKey=base64_decode($sessionKey);

        $aesIV=base64_decode($iv);

        $aesCipher=base64_decode($encryptedData);

        $data=json_decode(openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV),true);
        
        if ($data) return $data;

        Tools::exceptionBreak('980005');
    }

    public function getData(){
        return call_user_func_array([$this,$this->type],[]);
    }

}