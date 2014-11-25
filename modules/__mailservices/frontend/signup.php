<h2>Join the Mailing List</h2>
<?= display_feedback() ?>
<form method="POST" align="left">
	<fieldset>
	<ol align="left">
		<li>
			<label for="contact[email]">Email Address</label>
			<input type="text" id="newsletter-email-address" name="contact[email]" value="" />
		</li>
		<li>
			<label for="contact[name]">Name</label>
			<input type="text" id="newsletter-contact-name" name="contact[name]" value="" />
		</li>
		<li>
			<label for="address[postal_code]">Postal Code</label>
			<input type="text" id="newsletter-signup-postal-code" name="address[postal_code]" value="" />
		</li>
	</ol>
	</fieldset>
	<div class="button_holder">
		<button type="button" id="newsletter-submit-button">Sign me up!</button>
		<button type="button" id="newsletter-close-button">Close [x]</button>
	</div>
</form>