<?php 
global $UsersModel;
global $MessagesModel;
global $CONFIG;

if ($_POST) {
	$VALID["email"] = validate(VALID_email($_POST["email"]));
	
	if (isnotnull($VALID)) {
		$EmailFound = $UsersModel->find("WHERE email = '{$_POST["email"]}'", false);
		
		if ($EmailFound) {
			$variables["EMAIL"] = $EmailFound["email"];
			$variables["PASSWORD"] = $EmailFound["password"];	
			$variables["LOGIN_URL"] = "http://".$CONFIG["host"].get_uri("user_login_url");

			$MessagesModel->send_to_email($_POST["email"], "forgot-password-email", $variables);
			
			$VALID["found"] = true;
		} else {
			feedback("errors", "There is no user with that email address on our system.");		
		}
	}
	$form = $_POST;
}

?>
<h2>Forgot your <span>password?&nbsp;</span></h2>
<?php if ($VALID["found"] && $_POST) :?>
    <p>An email has been sent to <?=$EmailFound["email"]?> </p>		
<?php else : ?>
    <p>Enter your email address below.</p>
    <?= display_feedback() ?>
	<form class="standard"method="post">
	    <fieldset>
	        <ol>
	         	<li>
		         	<label for="email">Email</label>
		            <input type="text" class="required" name="email" id="email" value="<?= $form["email"] ?>" size="40" />
	            </li>
	            <li>
	            	<label>&nbsp;</label>
	            	<button type="submit" action="login">Send me my password &gt;&gt;</button>

	       	 	   	
	      			<p>  &nbsp;<a href="<?= get_uri("user_login_url") ?>" title="Back to Login"> &lt; &lt; Back to login</a> </p>    	
        
	            </li>
			</ol>    	
       		<div style="clear: both;"></div>
		</fieldset>
	</form>
<?php endif; ?>
