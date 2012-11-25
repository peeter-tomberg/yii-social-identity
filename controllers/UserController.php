<?php
class UserController extends Controller {

	public function actionLogin($service = null, $is_callback = false) {

		if($service == null) {
			$this->render('login');
		}
		else {

			$socialIdentity = new SocialUserIdentity($service, $is_callback);
			$authenticate = $socialIdentity->authenticate();

			$successCallback = function() use ($socialIdentity) {

				Yii::app()->user->login($socialIdentity);
				Yii::app()->request->redirect(Yii::app()->createAbsoluteUrl("/"));

			};


			if(!$authenticate) {
				if($socialIdentity->errorCode == SocialUserIdentity::ERROR_UNKNOWN_IDENTITY) {

					$user = new User();
					$user->display_name = $socialIdentity->getState("display_name");
					$user->insert();

					$userSocialLogin = new UserSocialLogins();

					$userSocialLogin->service_name = $service;
					$userSocialLogin->service_id = $socialIdentity->getState("service_id");
					$userSocialLogin->access_token = $socialIdentity->getState("access_token");
					$userSocialLogin->user_id = $user->id;

					$userSocialLogin->insert();

					if(!$socialIdentity->authenticate()) {
						throw new Exception("Something wierd happened while creating a new user & later authenticating it.");
					}

					$successCallback();
				}
				else {
					$this->render('login', array("error" => "Signup error omg"));
				}
			}
			else {
				$successCallback();
			}
		}

	}

}

?>