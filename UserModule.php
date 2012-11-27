<?php

class UserModule extends CWebModule
{

	/**
	 * Holds data from the config file
	 * @var array
	 */
	public $networks = array();

	public $userModel = 'SocialUser';

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(

			'user.models.*',
			'user.controllers.*',
			'user.components.*',
			'user.services.*',
			'user.exceptions.*',
			'user.vendors.facebook.src.*',

		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
