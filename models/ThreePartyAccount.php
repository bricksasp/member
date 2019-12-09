<?php
namespace bricksasp\member\models;

use bricksasp\helpers\Tools;
use Yii;

/**
 * 三方账户桥文件
 */
class ThreePartyAccount {
    public static $error;


	public static function bridge($class = '', $type = '', $data = []) {
		$model = Yii::createObject([
			'class' => 'bricksasp\\member\\models\\platform\\' . $class,
			'type' => $type,
			'data' => $data,
		]);

		// 三方平台数据
		$res = $model->getData($data);

        if (!$res) {
            self::$error = $model->error;
            return false;
        }
        
		return $res;
	}
}