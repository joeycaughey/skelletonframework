
$(document).ready(function() {
	var google_analytics_code = "";
	var gigya_api_key = '';
	var facebook_app_id = "";
	
	if (google_analytics_code) {
		$.getScript('http://www.google-analytics.com/ga.js', function(data, textStatus){ 
			try {
				var pageTracker = _gat._getTracker(google_analytics_code);
				pageTracker._trackPageview();
			} catch(err) {} 
		});
	}
	
	if (gigya_api_key) {
		$.getScript('http://cdn.gigya.com/js/socialize.js?apiKey='+gigya_api_key, function() {
			$.getScript('/_assets/gigya/gigya.js', function() { });
		});
	}	
	
	if (facebook_app_id) {
		$.getScript('http://connect.facebook.net/en_US/all.js', function() { 
			window.fbAsyncInit = function() {
		          FB.init({
		            appId      : facebook_app_id,
		            status     : true, 
		            cookie     : true,
		            xfbml      : true
		          });
		          
		          
		          FB.getLoginStatus(function(response) {
		        	  //console.log(response);
			    	  if (response.status=="connected") {
			    	    // logged in and connected user, someone you know
			    		//alert("Loged into facebook: "+response.session.uid);
			    		$("#facebook-button").html("Loged in to Facebook")
			    	  } else {
			    	    // no user session available, someone you dont know
			    	  }
		          });
		          
		          /* All the events registered */
		          FB.Event.subscribe('auth.login', function(response) {
		              // do something with response
		             // login();
		          });
		          FB.Event.subscribe('auth.logout', function(response) {
		              // do something with response
		              //logout();
		          });
		          
		          //FB.ui({ method: 'feed',  message: 'Facebook for Websites is super-cool'});
		    };
	
		});
	}


	$.getScript('/_assets/base.js', function() { });
	$.getScript('/_assets/jquery.forms/jquery.forms.js', function() { });
});		
		





