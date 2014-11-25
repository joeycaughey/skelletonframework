<html>
<head>
    <title>Gigya Social Demo  - getContacs</title>
		<style>
	 body {font-family:Arial;font-size: 12px; background-color:#fff; line-height:20px;margin:1px;}
	 h5 { font-size: 12px; color: #6e6e6e; padding: 0px; margin: 0px; }
     h6 { font-size: 14px; color: #6e6e6e; padding: 0px; margin: 0px; font-weight:bold; }
	</style>
	<SCRIPT type="text/javascript" lang="javascript" 
	   src="http://cdn.gigya.com/JS/socialize.js?apikey=2_Y82PzwJ_chSFImHXaIDJClnLyJzmk-VFOavSsaNTzl6m901s_NNxRAS0xJ3bd3_N">
	</SCRIPT>
	<script>
	    function onLoad() {
	        // get user info
	        gigya.services.socialize.getUserInfo({}, { callback: renderUI });

	        // register for connect status changes
	        gigya.services.socialize.addEventHandlers({}, 
	                  { onConnectionAdded: renderUI, onConnectionRemoved: renderUI });

	    }
    </script>
    

	<script type="text/javascript">

	    function renderUI(res) {
	        // enable/disable "Get Contacts" button
	        var connected = (res.user != null && res.user.isConnected);
	        document.getElementById('btnGetContacts').disabled = !connected;

	        // clear contact list if not connected
	        if (!connected)
	            document.getElementById('contacts').innerHTML = "";
	    }

	    // Get the user's contacts
	    function getContacts() {
	        gigya.services.socialize.getContacts({}, { callback: getContacts_callback });
	        document.getElementById('btnGetContacts').disabled = true;
	    }

	    // Use the reponse of getContacts and render HTML to display the first five contacts.
	    function getContacts_callback(response) {
	        document.getElementById('btnGetContacts').disabled = false;
	        document.getElementById('contacts').innerHTML = "";
	        if (response.errorCode == 0) {
	            var array = response.contacts.asArray();
	            var html = "You have " + array.length + " contacts, here are some of them:<BR/>";
	            html += "<table cellpadding=20>";
	            for (var i = 0; i < Math.min(10, array.length); i++) {
	                html += "<tr><td align=center valign='bottom'>";
	                if (array[i].photoURL)
	                    html += "<img width='50' height='50' src='"
	                    + array[i].photoURL + "' ><br>";
	                html += array[i].firstName + " " + array[i].lastName + ": <br>";
	                html += array[i].email + "</td></tr>";
	            }
	            html += "</table>";
	            document.getElementById('contacts').innerHTML = html;
	        } else {
		        alert('Error :' + response.errorMessage);
		    }
	    }
    </script>
</head>
<body onload="onLoad()">
	<h5>Step 1: Connect</h5>
	    <div id="divConnect"></div>
    <script type="text/javascript">
        // show 'Add Connections' Plugin in "divConnect"
        gigya.services.socialize.showAddConnectionsUI({}, { 
			height:65
			,width:120
			,showTermsLink:false // remove 'Terms' link
			,hideGigyaLink:true // remove 'Gigya' link
			,requiredCapabilities: "Contacts" // we want to show only providers that support retrieving contacts.
			,containerID: "divConnect" // The component will embed itself inside the divConnect Div 
		});
    </script>    
    <br />
    <h5>Step 2: Get Contacts</h5>
    <div style="margin-top:5px;">
    Click the button below to retrieve your email contacts
    </div>
    <input id="btnGetContacts" type="button" value="Get Contacts" 
            onclick="getContacts()" disabled=true/>
    <div id="contacts"></div>
</body>
</html>