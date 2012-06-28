<?php

$token = $_COOKIE['ccauth']; 

echo '<h2>OAuth 2.0 Client flow demo</h2>';

// Checks for deauth parameter to delete ccauth cookie and reload this script if user chooses to start over after authentication (see below)
	if (isset($_GET['deauth'])) {
		setcookie ('ccauth', '', time() - 3600);
		header('Location: index.php');
	} else {
	// if authentication token was found, display token, request username, offer chance to remove authentication credentials and start over.
		if (isset($token) & $token != "") { 
			echo '<p>A "ccauth" cookie is present. Either you have just successfully authenticated, or someone has previously authenticated using this Demo with this client/web browser.<br />';
			echo 'Your access token is: '.$token.'</p>';
			echo "<p>Now that you have successfully authenticated, let's use this access token to request the username to which this access token applies...</p>";
		
		// This sets up a POST request using cURL to obtain the username of the authenticated account
			$url = 'https://oauth2.constantcontact.com/oauth2/tokeninfo.htm?access_token='.$token;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, array("Content-Type: application/x-www-form-urlencoded;charset=UTF-8"));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
	
		// Below, the JSON response from above request is decoded, username extracted from the JSON object, and welcome message printed
			$userinfo = json_decode($response);
			$username = $userinfo->user_name;
			echo '<p>The username of the authenticated account is: <strong>'.$username.'</strong>!</p>';
	
		// Offers user a chance to start authentication over
			echo '<a href="index.php?deauth=true">Click here</a> to remove stored authentication data and start over.';
	
		} else { 
		// If no cookie is found when script loads, offer to perform client flow authentication

			echo '<p><a href="ccclientflow.html" >Start Client Flow Authentication</a></p>';
			echo '<p>This will redirect you to cccclientflow.html, which contains basic Javascript code that carries out the client flow authentication, obtaining and storing an access token in the client in a cookie.</p>';
			echo '<p>Afterward, a link will be provided to return to this Demo PHP application, which will retrieve the access token from the client\'s cookie, and use it to retrieve the username for the authenticated account.</p>';
		}
	}

?>