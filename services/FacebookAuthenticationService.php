<?php
/**
 * Handles Facebook authentication
 * @author Peeter Tomberg
 *
 */
class FacebookAuthenticationService implements AuthenticationServiceI {

	private $options = array();

	private $socialUseridentity;

	private $facebook;

	public function __construct(SocialUserIdentity $socialUseridentity, array $options) {

		$this->socialUseridentity	= $socialUseridentity;
		$this->options				= $options;

		$this->facebook = new Facebook(array(
				'appId'  => $this->options["appId"],
				'secret' => $this->options["secret"],
		));
	}
	/**
	 * (non-PHPdoc)
	 * @see AuthenticationServiceI::authorize()
	 */
	public function authorize() {

		$user = $this->facebook->getUser();
		if(!$user && !$this->socialUseridentity->is_callback) {

			Yii::app()->request->redirect(
				$this->facebook->getLoginUrl(array("redirect_uri" => $this->options["redirect_url"]))
			);
		}
		else if(!$user) {
			throw new AuthorizationException("Authorization failed");
		}


	}

	/**
	 * (non-PHPdoc)
	 * @see AuthenticationServiceI::authenticate()
	 */
	public function authenticate() {


		$user = $this->facebook->getUser();

		$userSocialLogin = UserSocialLogins::model()->findByAttributes(array("service_name" => $this->socialUseridentity->service, "service_id" => $user));

		if(!$userSocialLogin) {

			$user_profile = $this->facebook->api('/me');

			$this->socialUseridentity->setState("access_token", $this->facebook->getAccessToken());
			$this->socialUseridentity->setState("service_id", $user);
			$this->socialUseridentity->setState("display_name", $user_profile["name"]);

			$this->socialUseridentity->errorCode = UserIdentity::ERROR_UNKNOWN_IDENTITY;

			return false;
		}

		$this->socialUseridentity->_id = $userSocialLogin->user_id;
		$this->socialUseridentity->errorCode = UserIdentity::ERROR_NONE;

		return true;

	}

}

?>