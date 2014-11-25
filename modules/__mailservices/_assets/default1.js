$(document).ready(function() { 
	
	$.get('/mailinglist/signup/', function(html) {
		$('#newsletter-signup-form').html(html);
		
		 $("#newsletter-submit-button").click(function() {
			 save_info({
				 op: 'save',
				 email:  $("#newsletter-signup-email").val(),
				 contact_name: $("#newsletter-contact-name").val(),
				 zip_postal_code: $("#newsletter-signup-postal-code").val() 
			 });
		    $.unblockUI();
		 });
		 
		 $("#newsletter-close-button").click(function() {
			 $.unblockUI();
		 });
	});
	
    $('#newsletter-signup-button').click(function() { 
    	save_info({ email_address: $("#newsletter-signup-email").val() });
    	$("#newsletter-email-address").val( $("#newsletter-signup-email").val() );
        $.blockUI({ 
        	message: $('#newsletter-signup-form'),
        	css: { 
        	width: '500px',
            border: 'solid 1px #666', 
            padding: '15px', 
            margin: '-50px',
            backgroundColor: '#030303', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: 1, 
            color: '#fff' 
        } }); 
    }); 
    
    message = 'You@email.com';
    $("#newsletter-signup-email").focus(function() {
    	if ($(this).val()==message) $(this).val("");
    }).blur(function() {
    	if ($(this).val()=="") $(this).val(message);
    }).val(message);
}); 


function save_info(params) {
	$.post('/mailinglist/save/', params, function(html) {
		if (params.op=='save') $('#newsletter-signup-form').html(html);
	});
}