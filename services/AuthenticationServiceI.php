<?php
/**
 * 3rd party authentication interface
 * @author Peeter Tomberg
 *
 */
interface AuthenticationServiceI {


	public function __construct(SocialUserIdentity $si, array $options);

	/**
	 * Handles authenticating the authorized 3rd party user against our own userbase
	 * @return boolean true on a succesful authentication, false on a failure
	 */
	public function authenticate();

	/**
	 * Handles authorizing our application with the 3rd party
	 *
	 * @throws AuthenticationException when the client returns without succsefully authorizing with the 3rd party
	 */
	public function authorize();

}