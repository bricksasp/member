<?php
namespace bricksasp\member\controllers;

use yii\web\Controller;

/**
 * Default controller for the `member` module
 */
class DefaultController extends Controller {

	public function actions() {
		return [
			'error' => [
				'class' => \bricksasp\base\actions\ErrorAction::className(),
			],
		];
	}
	
	public function actionIndex() {
		return $this->render('index');
	}
}
