<?php
global $UsersModel;

$User = $UsersModel->get_from_hash($_GET["hash"]);

?>
<div class="two-column-offset-left-layout"  id="game-stage">
	<div class="column first">
		<h2>&nbsp;</h2>
		<ol class="steps">
			<li>1. Create an Account</li>
			<li class="on">2. Verify Your Account</li>
			<li>3. Start Browsing!</li>
		</ol>
	</div>
	<div class="column">		
		<p>&nbsp;</p>	
		<h2>Thank you for your sign up</h2>
		<p>An email has been sent to you <b>(<?=$User["email"]?>)</b> with a verification link. <br />Please check your email and verify your account.</p>
	
	

	</div>
	<div style="clear: both;"></div>
</div>
