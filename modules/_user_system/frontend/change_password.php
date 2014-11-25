<?php
global $ACTIVE_USER;
global $sitename;
global $CONFIG;
global $UsersModel;


$_REQUEST["redirect"] = ($_REQUEST["redirect"]) ?  $_REQUEST["redirect"] : $_SERVER['HTTP_REFERER'];

if (!$UsersModel->ACTIVE_USER()) {
	header("Location: ".$_REQUEST["redirect"]);
}

if ($_POST) {
	if ($UsersModel->ACTIVE_USER["password"]==$_POST["oldpassword"]) {
		if (VALID_password($_POST["password"], $_POST["confirm_password"])) {
			feedback("notices", "Pasword changed.");
			$VALID_form = true;
		} else {
			feedback("errors", "Your passwords do not match.");
		}
	} else {
		feedback("errors", "Invalid Password.");
	}
	
}
?>

<div id="game-stage">
	<h2>Change Your Password</h2>
	<?php if ($VALID_form) : ?>
	<p>You password has been changed.</p>
	
	<button type="button" id="continue-button">Continue &gt;&gt;</button>
	
	<?php else : ?>
	<form class="standard" method="post" style="width: 70%; margin-top: 30px;">
		<input type="hidden" name="redirect" value="<?=$_REQUEST["redirect"]?>" />
	    <fieldset>
	    	<?= display_feedback() ?>
	        <ol>    
	            <li>
	            	<label for="password">Old Password</label>
	            	<input type="password" class="required" name="oldpassword" id="password" size="26" />
	            </li>
	      		<li>
	            	<label for="password">New Password</label>
	            	<input type="password" class="required" name="password" id="password" size="26" />
	            </li>
	      		<li>
	            	<label for="confirm_password">Confirm Password</label>
	            	<input type="password" class="required" name="confirm_password" id="password" size="26" />
	            </li>
			</ol>
			<br />
			<div class="button_holder">
	        	<button type="submit">Change Password &gt;&gt;</button>
	        	<button type="button" id="continue-button">Nevermind, Go Back &gt;&gt;</button>
	       	</div>	
		</fieldset>
	</form>
	<?php endif; ?>
</div>	
<script type="text/javascript">
	$(document).ready(function() {
		$("#continue-button").click(function() {
			document.location = '<?=$_REQUEST["redirect"]?>';
		});
	});
</script>