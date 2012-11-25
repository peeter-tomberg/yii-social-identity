<?php

Yii::setPathOfAlias('ZendOAuth', Yii::getPathOfAlias("user.vendors.ZendOAuth.library.ZendOAuth"));
Yii::setPathOfAlias('Zend', Yii::getPathOfAlias("user.vendors.Zend"));

/**
 *
 * @author Peeter
 *
 */
class TwitterAuthenticationService implements AuthenticationServiceI {

	public function __construct(SocialUserIdentity $si, array $options) {

		$aTwitterConfig = array(
				'callbackUrl' => "http://localhost",
				'siteUrl' => 'https://api.twitter.com/oauth',
				'authorizeUrl' => 'https://api.twitter.com/oauth/authenticate',
				'consumerKey' => $options["key"],
				'consumerSecret' => $options["secret"]
		);

		$consumer = new ZendOAuth\Consumer($aTwitterConfig);
		$oSession = Yii::app()->getSession();
		$token = null;

		if ($oSession->get('TWITTER_ACCESS_TOKEN'))
		{
			$token = unserialize($oSession->get('TWITTER_ACCESS_TOKEN'));
		}
		else if (isset($_GET['oauth_token']))
		{
			try
			{
				$token = $consumer->getAccessToken(
						$_GET,
						unserialize($oSession->get('TWITTER_REQUEST_TOKEN'))
				);
			}
			catch (\Exception $oException)
			{
				$token = NULL;
			}

			if ($token)
			{
				$oSession->add('TWITTER_ACCESS_TOKEN', serialize($token));
			}
		}

		if(!$token)
		{
			$token = $consumer->getRequestToken();
			$oSession->add('TWITTER_REQUEST_TOKEN', serialize($token));
			$consumer->redirect();
		}




	}

	public function authorize() {

	}

	public function authenticate() {

	}
}

?>