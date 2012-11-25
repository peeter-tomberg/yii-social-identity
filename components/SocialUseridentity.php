<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class SocialUserIdentity extends CUserIdentity
{

	/**
	 * The id of the logged in user
	 * @var mixed
	 */
	public $_id;

	/**
	 * The name of the service to use (facebook etc)
	 * @var string
	 */
	public $service;

	/**
	 * Determines if this is the user initating the login process or if this is the actual response
	 * @var boolean
	 */
	public $is_callback = false;

	/**
	 * Construct a SocialUseridentity
	 * @param string $service the name of the service to use (facebook etc)
	 * @param boolean $is_callback determines if this is the user initating the login process or if this is the actual response
	 */
	public function __construct($service, $is_callback) {
		$this->service = $service;
		$this->is_callback = $is_callback;
	}
	/**
	 * Authenticates a user using a social network
	 * @return boolean true on succesful authentication
	 * @throws Exception when user tries to use an unknown or a disabled service to authenticate
	 */
	public function authenticate() {


		$networks = Yii::app()->getModule("user")->networks;

		if(!array_key_exists($this->service, $networks) || !isset($networks[$this->service]["enabled"]) || $networks[$this->service]["enabled"] == false) {
			throw new Exception("User trying to use an unknown or disabled service to authenticate");
		}

		switch($this->service) {
			case 'facebook':

				$facebookAuthenticationService = new FacebookAuthenticationService($this, array(

					'appId'			=> $networks[$this->service]["appId"],
					'secret'		=> $networks[$this->service]["secret"],

					'redirect_url'	=> Yii::app()->createAbsoluteUrl("user/user/login", array(
						"service" => $this->service,
						"is_callback" => true
					))
				));

				try {
					$facebookAuthenticationService->authorize();
				}
				catch(AuthorizationException $e) {
					$this->errorCode = UserIdentity::ERROR_USERNAME_INVALID;
					return false;
				}

				return $facebookAuthenticationService->authenticate();
				break;

			case 'twitter':

				$twitterAuthenticationService = new TwitterAuthenticationService($this, array(

						'key'			=> $networks[$this->service]["key"],
						'secret'		=> $networks[$this->service]["secret"],

						'redirect_url'	=> Yii::app()->createAbsoluteUrl("user/user/login", array(
							"service" => $this->service,
							"is_callback" => true
						))
				));

				try {
					$twitterAuthenticationService->authorize();
				}
				catch(AuthorizationException $e) {
					$this->errorCode = UserIdentity::ERROR_USERNAME_INVALID;
					return false;
				}

				return $twitterAuthenticationService->authenticate();

				break;

			default:
				throw new Exception("User trying to authenticate with an unknown service $this->service");
		}

	}

	public function getId() {
		return $this->_id;
	}



}

// EOF
