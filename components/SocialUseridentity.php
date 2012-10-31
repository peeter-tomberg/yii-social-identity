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
	private $_id;

	/**
	 * The name of the service to use (facebook etc)
	 * @var string
	 */
	private $service;

	/**
	 * Determines if this is the user initating the login process or if this is the actual response
	 * @var boolean
	 */
	private $is_callback = false;

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
				$facebook = new Facebook(array(
					'appId'  => $networks[$this->service]["appId"],
					'secret' => $networks[$this->service]["secret"],
				));
				$user = $facebook->getUser();
				if(!$user) {

					if($this->is_callback) {
						return false;
					}

					Yii::app()->request->redirect(
						$facebook->getLoginUrl(array("redirect_uri" => Yii::app()->createAbsoluteUrl("user/user/login", array(
							"service" => $this->service,
							"is_callback" => true
							)
						)))
					);
				}
				else {

					$userSocialLogin = UserSocialLogins::model()->findByAttributes(array("service_name" => $this->service, "service_id" => $user));
					if(!$userSocialLogin) {

						$user_profile = $facebook->api('/me');

						$this->setState("access_token", $facebook->getAccessToken());
						$this->setState("service_id", $user);
						$this->setState("display_name", $user_profile["name"]);

						$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;

						return false;
					}

					$this->_id = $userSocialLogin->user_id;
					$this->errorCode = self::ERROR_NONE;

					return true;
				}

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
