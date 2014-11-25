/*** CONFIGURATION ***/

var facebook_app_id = "255339351255706";
var facebook_app_domain = "dev.eyemetro.local";


/*** INIT SCRIPT ***/
$.getScript('//connect.facebook.net/en_US/all.js', function() { });


/*** DOCUMENT LOAD SCRIPT ***/
$(document).ready(function(){
	
	$("#facebook-login").click(function() {
		facebook_login();
	});
});

/*** FUNCTIONS ***/

function facebook_login() {
	alert(true);
    FB.login(function(response) {
        if (response.authResponse) {
            // connected
        } else {
            // cancelled
        }
    });
}

// Additional JS functions here
window.fbAsyncInit = function() {
	  FB.init({
	    appId      : facebook_app_id, // App ID
	    channelUrl : '//'+facebook_app_domain+'/channel.html', // Channel File
	    status     : true, // check login status
	    cookie     : true, // enable cookies to allow the server to access the session
	    xfbml      : true  // parse XFBML
	  });

	  // Additional init code here
	  
	  FB.getLoginStatus(function(response) {
		  if (response.status === 'connected') {
			   FB.api('/me', function(response) {
			        console.log('Good to see you, ' + response.name + '.');
			   });
		  } else if (response.status === 'not_authorized') {
		    // not_authorized
		    facebook_login();
		  } else {
		    // not_logged_in
			facebook_login();
		  }
	  });
	  
};

