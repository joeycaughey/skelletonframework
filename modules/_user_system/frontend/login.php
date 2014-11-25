<?php
global $ACTIVE_USER;
global $sitename;
global $CONFIG;
global $UsersModel;

if ($_POST) {
	$UsersModel->login($_POST["email"], $_POST["password"]);
	$form = $_POST;
}
?>


<h2>Login to Your Account</h2>

<div class="two-column-offset-right-layout">
	<div class="column first">
		<form class="standard" method="post">
		    <fieldset> 
		    	<?= display_feedback() ?>
		        <ol>
		         	<li>
			         	<label for="email">Email</label>
			            <input type="text" class="required" name="email" id="email" value="<?= $form["email"] ?>" size="40" />
		            </li>
		    
		            <li>
		            	<label for="password">Password</label>
		            	<input type="password" class="required" name="password" id="password" size="26" />&nbsp;<a href="<?= get_uri("forgot_password_url") ?>" title="Forgot your password?"> Forgot your password?</a>
				    
		            </li>
		            <li>
		            	<label>&nbsp;</label>
		            	<button type="submit">Sign In &gt;&gt;</button>&nbsp;<button type="button" id="signup-button">Not a Member? Sign Up</button>
		            </li>
				</ol>	
			</fieldset>
		</form>
	</div>

	<div style="clear: both;"></div>
</div>
	

<script type="text/javascript">
$(document).ready(function() {
	$("#signup-button").click(function() {
		document.location = '<?= get_uri("signup_url"); ?>';
	});
});
</script>
