<?php

namespace bricksasp\member\controllers;

use Yii;
use bricksasp\member\models\ThreePartyAccount;
use bricksasp\member\models\FormValidate;
use bricksasp\member\models\Sms;
use bricksasp\member\models\UserWx;

class UserController extends \bricksasp\base\BaseController
{
    /**
     * 登录可访问 其他需授权
     * @return array
     */
    public function allowAction() {
        return [];
    }

    /**
     * 免登录可访问
     * @return array
     */
    public function allowNoLoginAction() {
        return [
        	'sessionkey',
        	'three-party-login',
        	'sms-vcode',
        	'mobile-bind-three-party',
        ];
    }

	/**
	 * @OA\Get(path="/member/user/sessionkey",
	 *   summary="小程序sessionkey",
	 *   tags={"member模块"},
	 *   @OA\Parameter(
	 *     description="登录凭证",
	 *     name="X-Token",
	 *     in="header",
	 *     @OA\Schema(
	 *       type="string"
	 *     )
	 *   ),
	 *   @OA\Parameter(
	 *     description="小程序code",
	 *     name="code",
	 *     in="query",
     *     required=true,
	 *     @OA\Schema(
	 *       type="string"
	 *     )
	 *   ),
	 *   @OA\Parameter(
	 *     description="平台 wechat, ali",
	 *     name="platform",
	 *     in="query",
	 *     @OA\Schema(
	 *       type="string",
	 *       default="wechat"
	 *     )
	 *   ),
	 *   @OA\Parameter(
	 *     description="类型 lite小程序, pub公众号",
	 *     name="type",
	 *     in="query",
	 *     @OA\Schema(
	 *       type="string",
	 *       default="lite"
	 *     ),
	 *   ),
	 *   @OA\Response(
	 *     response=200,
	 *     description="sessionkey",
	 *     @OA\MediaType(
	 *       mediaType="application/json",
	 *       @OA\Schema(ref="#/components/schemas/sessionkey"),
	 *     ),
	 *   ),
	 * )
	 *
	 * @OA\Schema(
	 *   schema="sessionkey",
	 *   description="数据结构",
	 *   allOf={
	 *     @OA\Schema(
	 *       @OA\Property(property="sessionkey", type="string", description="小程序sessionkey"),
	 *       @OA\Property(property="openid", type="string", description="openid"),
	 *     )
	 *   }
	 * )
	 */
    public function actionSessionkey()
    {
    	$parmas = Yii::$app->request->get();
		
		$validator = new FormValidate($parmas, ['scenario' => 'sessionkey']);

		if ($validator->validate()) {
	    	$model = new ThreePartyAccount();
	    	$parmas['owner_id'] = $this->ownerId;
	    	$res = $model->bridge(ucfirst($validator->platform), $validator->type, $parmas);
	    	return $this->success($res);
		}

		return $this->fail($validator->errors);
    }


	/**
	 * @OA\Post(path="/member/user/three-party-login",
	 *   summary="三方登录",
	 *   tags={"member模块"},
	 *   @OA\Parameter(
	 *     description="登录凭证",
	 *     name="X-Token",
	 *     in="header",
	 *     @OA\Schema(
	 *       type="string"
	 *     )
	 *   ),
	 *   @OA\RequestBody(
	 *     required=true,
	 *     @OA\MediaType(
	 *       mediaType="application/json",
	 *       @OA\Schema(
	 *         @OA\Property(
	 *           description="openid",
	 *           property="openid",
	 *           type="string",
	 *         ),
	 *         @OA\Property(
	 *           description="unionid存在者填写",
	 *           property="unionid",
	 *           type="string"
	 *         ),
	 *         @OA\Property(
	 *           description="微信小程序iv",
	 *           property="iv",
	 *           type="string",
	 *         ),
	 *         @OA\Property(
	 *           description="微信小程序encryptedData",
	 *           property="encryptedData",
	 *           type="string"
	 *         ),
	 *         @OA\Property(
	 *           description="平台 wechat, ali",
	 *           property="platform",
	 *           type="string",
     *           example="wechat",
	 *         ),
	 *         @OA\Property(
	 *           description="类型 litelogin 小程序, publogin 公众号",
	 *           property="type",
	 *           type="string",
	 *           example="litelogin"
	 *         )
	 *       )
	 *     )
	 *   ),
	 *   @OA\Response(
	 *     response=200,
	 *     description="threePartyLogin",
	 *     @OA\MediaType(
	 *       mediaType="application/json",
	 *       @OA\Schema(ref="#/components/schemas/threePartyLogin"),
	 *     ),
	 *   ),
	 * )
	 *
	 * @OA\Schema(
	 *   schema="threePartyLogin",
	 *   description="数据结构",
	 *   allOf={
	 *     @OA\Schema(
	 *       @OA\Property(property="user_wx_id", type="string", description="用户未绑定时返回"),
	 *       @OA\Property(property="token", type="string", description="用户已绑定时返回"),
	 *     )
	 *   }
	 * )
	 */
    public function actionThreePartyLogin()
    {
    	$parmas = Yii::$app->request->post();
		$validator = new FormValidate($parmas, ['scenario' => 'threePartyLogin']);
		if ($validator->validate()) {

	    	$model = new ThreePartyAccount();
	    	$parmas['owner_id'] = $this->ownerId;
	    	$res = $model->bridge(ucfirst($validator->platform), $validator->type, $parmas);
	    	return $this->success($res);

		}

		return $this->fail($validator->errors);
    }

	/**
	 * @OA\Post(path="/member/user/sms-vcode",
	 *   summary="发送短信验证码",
	 *   tags={"member模块"},
	 *   @OA\Parameter(
	 *     description="登录凭证",
	 *     name="X-Token",
	 *     in="header",
	 *     @OA\Schema(
	 *       type="string"
	 *     )
	 *   ),
	 *   @OA\RequestBody(
	 *     required=true,
	 *     @OA\MediaType(
	 *       mediaType="application/json",
	 *       @OA\Schema(
	 *         @OA\Property(
	 *           description="手机号码",
	 *           property="mobile",
	 *           type="integer",
	 *           example=18782908511
	 *         )
	 *       )
	 *     )
	 *   ),
	 *   @OA\Response(
	 *     response=200,
	 *     description="sms",
	 *     @OA\MediaType(
	 *       mediaType="application/json",
	 *       @OA\Schema(ref="#/components/schemas/response"),
	 *     ),
	 *   ),
	 * )
	 *
	 */
    public function actionSmsVcode()
    {
    	$model = new Sms();
    	$code = rand(10000,99999) . '';
    	$res = $model->sendsms(Yii::$app->request->post('mobile'), $code);
    	if ($res === true) {
    		return $this->success(Yii::t('base',980006), $code);
    	}
    	return $this->fail($res);
    }

	/**
	 * @OA\Post(path="/member/user/mobile-bind-three-party",
	 *   summary="手机注册绑定三方账户",
	 *   tags={"member模块"},
	 *   @OA\Parameter(
	 *     description="登录凭证",
	 *     name="X-Token",
	 *     in="header",
	 *     @OA\Schema(
	 *       type="string"
	 *     )
	 *   ),
	 *   @OA\RequestBody(
	 *     required=true,
	 *     @OA\MediaType(
	 *       mediaType="application/json",
	 *       @OA\Schema(
	 *         @OA\Property(
	 *           description="手机号码",
	 *           property="mobile",
	 *           type="integer",
	 *           example=18782908511
	 *         ),
	 *         @OA\Property(
	 *           description="短信验证码",
	 *           property="vcode",
	 *           type="integer",
	 *         ),
	 *         @OA\Property(
	 *           description="用户id",
	 *           property="uid",
	 *           type="integer",
	 *         ),
	 *         @OA\Property(
	 *           description="平台 wechat, ali",
	 *           property="platform",
	 *           type="string",
     *           example="wechat",
	 *         )
	 *       )
	 *     )
	 *   ),
	 *   @OA\Response(
	 *     response=200,
	 *     description="register",
	 *     @OA\MediaType(
	 *       mediaType="application/json",
	 *       @OA\Schema(ref="#/components/schemas/response"),
	 *     ),
	 *   ),
	 * )
	 *
	 */
    public function actionMobileBindThreeParty()
    {
    	$parmas = Yii::$app->request->post();

		$validator = new FormValidate($parmas, ['scenario' => 'registerMobile']);
		$model = new Sms();
		if ($validator->validate() && $model->verificationCode($parmas['mobile'], $parmas['vcode'])) {
			$parmas['owner_id'] = $this->ownerId;
	    	if (empty($parmas['platform']) || $parmas['platform'] == 'wechat') {
		    	$model = new ThreePartyAccount();
		    	$parmas['owner_id'] = $this->ownerId;
		    	$res = $model->bridge(ucfirst($validator->platform), $validator->type, $parmas);
		    	return $this->success($res);
	    	}
		}

		return $this->fail($validator->errors);
    }
}
