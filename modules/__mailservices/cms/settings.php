
<h2>Mailing List Settings</h2>
<?php if (config("editMode") == "wizard" && false):?>
	<div class="message info">
		Use the following options to customize your Newsletter subscribers data collection.
		<dl>
			<dt>Verify E-mail Address</dt>
				<dd>Verify the integrity of new subscribers' e-mail address by sending a verification e-mail to them with a verification link.</dd>

			<dt>Collect additional information</dt>
				<dd>Enabling this option will require new subscribers to fill in additional contact information upon signup. Name and E-mail Address only are collected by default.</dd>
		</dl>
	</div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Data Collection</legend>
                <ol>
                    <li class="checkbox">
                        <label title="Send an e-mail to subscribers to new verify the integrity of their e-mail address">Verify Email</label>
    				<input type="checkbox" name="config[module_mailinglists_verify_email]" value="Yes" class="plain" <?= (config("module_mailinglists_verify_email")=="") ? 'checked' : '' ?> />
                        <label class="fluid" for="param_emailCheck" title="">Verify Email Addresses on signup?</label>
                    </li>
                    <li class="checkbox">
                        <label title="Collect additional information regarding subscribers. This will take new signups to a form to gather all their contact information">Information</label>
    				<input type="checkbox" name="config[module_mailinglists_collect_additional_information]" value="Yes" class="plain" <?= (config("module_mailinglists_collect_additional_information")=="") ? 'checked' : '' ?> />
    				<label class="fluid" for="param_allInfo" title="">Collect additional information on signup?</label>
                    </li>
                </ol>
	</fieldset>
	<div class="buttonHolder">
		<button type="button" onClick="javascript:history.back();" class="red cancel">Cancel</button>
		<button type="submit" class="submit">Save Settings</button>
		<span>And</span>
		<input type="radio" name="return" value="1" id="return1" class="plain" checked><label class="fluid" for="return1">Remain on this page</label>			
		<input type="radio" name="return" value="2" id="return2" class="plain"><label class="fluid" for="return2">Return to Newsletter Welcome</label>
	</div>
</form>
