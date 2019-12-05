<?php

namespace bricksasp\member\controllers;

use Yii;
use bricksasp\member\models\UserWx;

class UserController extends \bricksasp\base\BaseController
{
    /**
     * 登录可访问 其他需授权
     * @return array
     */
    public function allowAction() {
        return [
            // 'add',
            // 'delete',
            // 'view',
            // 'index',
        ];
    }

    /**
     * 免登录可访问
     * @return array
     */
    public function allowNoLoginAction() {
        return ['applet-user-info'];
    }

	/**
	 * @OA\Get(path="/member/user/applet-user-info",
	 *   summary="小程序sessionkey",
	 *   tags={"member模块"},
	 *   @OA\Parameter(
	 *     description="开启平台功能后，访问商户对应的数据标识，未开启忽略此参数",
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
     *     required=true,
	 *     @OA\Schema(
	 *       type="string",
	 *       default="wechat"
	 *     )
	 *   ),
	 *   @OA\Parameter(
	 *     description="类型 lite小程序, pub公众号",
	 *     name="type",
	 *     in="query",
     *     required=true,
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
	 *       @OA\Schema(ref="#/components/schemas/appletSessionkey"),
	 *     ),
	 *   ),
	 * )
	 *
	 * @OA\Schema(
	 *   schema="appletSessionkey",
	 *   description="订单列表结构",
	 *   allOf={
	 *     @OA\Schema(
	 *       @OA\Property(property="sessionkey", type="string", description="小程序sessionkey"),
	 *       @OA\Property(property="openid", type="string", description="openid"),
	 *     )
	 *   }
	 * )
	 */
    public function actionAppletUserInfo()
    {
    	$code = Yii::$app->request->get('code');
    	$platform = Yii::$app->request->get('platform','wechat');
    	$type = Yii::$app->request->get('type','applet');
    	$model = new UserWx();
    	$res = $model->appletSessionkey(['code' => $code, 'owner_id' => $this->ownerId, 'class' => ucfirst($platform), 'type' => $type]);
    	return $this->success($res);
    }

}
