<?php

/**
 * A simple dashboard widget widget
 */
class SocialLoginWidget extends CWidget
{

	/**
	 * this method is called by CController::beginWidget()
	 */
	public function init()
	{

		$this->render("social_login_widget", array(
			"networks" => Yii::app()->getModule("user")->networks
		));

	}

	/**
	 * this method is called by CController::endWidget()
	 */
	public function run()
	{

	}
}