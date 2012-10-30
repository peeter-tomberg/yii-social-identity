<?php
	if(isset($error)) {
		echo "<p class='social-login-error'>$error</p>";
	}
	$this->widget('application.modules.user.widgets.SocialLoginWidget');
?>