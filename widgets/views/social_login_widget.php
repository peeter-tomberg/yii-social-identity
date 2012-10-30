<div id="social-login-widget">

	<?php
	foreach($networks as $networkName => $networkSettings) {
	?>
		<div class="<?php echo $networkName; ?>">
			<a href="<?php echo Yii::app()->createAbsoluteUrl("user/user/login", array("service" => $networkName)); ?>">
				Login using <?php echo $networkName; ?>
			</a>
		</div>
	<?php
	}
	?>

</div>
