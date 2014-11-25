{
  connectWithoutLoginBehavior: 'alwaysLogin'     // change the connect without login behavior       
}


var domain = "dev.eyemenus.com"
var conf= {
	siteName: domain,enabledProviders: 
   'facebook,twitter,linkedin'		
}



// This method is activated when the page is loaded
$(document).ready(function() {
	gigya.services.socialize.addEventHandlers({}, {
		context: { str: 'congrats on your' }
		, onLogin: onLoginHandler 
		, onLogout: onLogoutHandler
	});
	
	$("#gigya-logout-holder").click(function() {
		gigya.services.socialize.logout({}, {/*no required params*/}); // logout from Gigya platform
	});
});

// onLogin Event handler
function onLoginHandler(eventObj) {	
    //alert(eventObj.context.str + ' ' + eventObj.eventName + ' to ' + eventObj.provider 
	//	+ '!\n' + eventObj.provider + ' user ID: ' +  eventObj.user.identities[eventObj.provider].providerUID);
    // verify the signature ...
    verifyTheSignature(eventObj.UID, eventObj.timestamp, eventObj.signature);

    // Check whether the user is new by searching if eventObj.UID exists in your database
    var newUser = true; // lets assume the user is new
    
    if (newUser) {
        // 1. Register user 
        // 2. Store new user in DB
        // 3. link site account to social network identity
        //   3.1 first construct the linkAccounts parameters
        var dateStr = Math.round(new Date().getTime()/1000.0); // Current time in Unix format
															//(i.e. the number of seconds since Jan. 1st 1970)
		
        var siteUID = 'uTtCGqDTEtcZMGL08w'; // siteUID should be taken from the new user record
                                           // you have stored in your DB in the previous step
        var yourSig = createSignature(siteUID, dateStr);

        var params = {
            siteUID: siteUID, 
            timestamp:dateStr,
			cid:'',
            signature:yourSig
        };
        
        //   3.1 call linkAccounts method:
        gigya.services.socialize.notifyRegistration({}, params);
    }
	
	document.getElementById('status').style.color = "green";
	document.getElementById('status').innerHTML = "Status: You are now signed in";

}

// Note: the actual signature calculation implementation should be on server side
function createSignature(UID, timestamp) {
	encodedUID = encodeURIComponent(UID); // encode the UID parameter before sending it to the server.
										// On server side use decodeURIComponent() function to decode an encoded UID
    return '';
}

// Note: the actual signature calculation implementation should be on server side
function verifyTheSignature(UID, timestamp, signature) {
	encodedUID = encodeURIComponent(UID); // encode the UID parameter before sending it to the server.
										// On server side use decodeURIComponent() function to decode an encoded UID
    //alert('Your UID: ' + UID + '\n timestamp: ' + timestamp + '\n signature: ' + signature + '\n Your UID encoded: ' + encodedUID);
}


function share(options) {
	var act = new gigya.services.socialize.UserAction();

	if (options.title) act.setTitle(options.title); 
	if (options.link) act.setLinkBack(options.link);  // Setting the Link Back  
	if (options.description) act.setDescription(options.description);   // Setting Description  
	if (options.link) act.addActionLink("Read More", options.link);  // Adding Action Link  
	// Adding a Media (image)  
	if (options.image) act.addMediaItem( { type: 'image', src: options.image, href: options.link });  
	              
	var conf= {
		siteName: domain
		,enabledProviders: 'facebook,twitter,linkedin,yahoo' //,google	
	}
	
	var share_params=
	{
		userAction: act
		,showEmailButton: true
		,showMoreButton: true
		,cid: ''
		//,userMessagePlaceholder: message
	}
	gigya.services.socialize.showShareUI(conf,share_params);
	
}
		
// onLogout Event handler
function onLogoutHandler(eventObj) {
	document.getElementById('status').style.color = "red";
	document.getElementById('status').innerHTML = "Status: You are now signed out";
}

var login_params = {
	showTermsLink: 'false'
	,height: 100
	,width: 330
	,containerID: 'gigya-login-holder'
	,UIConfig: '<config><body><controls><snbuttons buttonsize="40" /></controls></body></config>'
	,buttonsStyle: 'fullLogo'
	,autoDetectUserProviders: ''
	,redirectURL: domain+"/login/return/"  
	,facepilePosition: 'none',
	
	onLogin: function() {
	
	} 
	
}
gigya.services.socialize.showLoginUI(conf, login_params);

var connect_params = {
	onConnectionAdded: onConnectionAdded
	,showTermsLink: 'false'
	,showWhatsThis: true
	,height: 40
	,width: 130
	,containerID: 'gigya-icon-connect-holder'
	,UIConfig: '<config><body><controls><snbuttons buttonsize="40" /></controls></body></config>'
}
gigya.services.socialize.showConnectUI(conf, connect_params);


function onConnectionAdded(response) {
	//alert(response);
	//console.log(response);

    if (response.user) {
    	console.log(response.user);
    	$.post('/login/return/', response.user, function(html) {
    		console.log(html);
    	})
        // Update the page with the data received in the response:
        // inject the user's nickname to the "divUserName" div
        //document.getElementById('divUserName').innerHTML = response.user.nickname;
        // inject the user's photo to the image "src" attribute.
        //document.getElementById('imgUserPhoto').src=response.user.photoURL;
    } else {
        alert("An error has occurred!" + '\n' +
            "Error details: " + response + '\n' +
            "In method: ");
    }
}  




var act = new gigya.services.socialize.UserAction();

var share_params= {
	userAction: act
	,containerID: 'share-holder'
	,showEmailButton: true
	,showMoreButton: true
	,cid: ''
}

if ("#share-holder") {
	gigya.services.socialize.showShareUI(conf,share_params);
}