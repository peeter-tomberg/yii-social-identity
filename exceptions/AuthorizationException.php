<?php

/**
 * Authorization Exception
 * @author Peeter.Tomberg
 *
 */
class AuthorizationException extends Exception {

	/**
	 *
	 * @param
	 *        	message[optional]
	 *
	 * @param
	 *        	code[optional]
	 *
	 * @param
	 *        	previous[optional]
	 *
	 */
	public function __construct($message = null, $code = null, $previous = null) {
		parent::__construct ( $message = null, $code = null, $previous = null );
	}
}

?>