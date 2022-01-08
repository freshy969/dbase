<?php include '../_config.php';
$currentUserID = generateRandomID();

// POST variables
$username = $_POST['ST_username'];
$password = $_POST['ST_password'];
$email = $_POST['ST_email'];
$iosDeviceToken = $_POST['ST_iosDeviceToken'];
$androidDeviceToken = $_POST['ST_androidDeviceToken'];

// Get JSON Table's data
$data = file_get_contents('Users.json');
$data_array = json_decode($data, true);

// Other variables 
$isSignInWithAppleGoogle = "";
$usernameExists = false;
$emailExists = false;

//---------------------------------------
// MARK - SIGN IN WITH APPLE OR GOOGLE
//---------------------------------------
if ($_POST['signInWith'] != "") {
	foreach ($data_array as $item) {
		if ($item['ST_password'] == $password) {
			$isSignInWithAppleGoogle = "true";
			echo $item['ID_id']. "-" .$isSignInWithAppleGoogle;
			return;
		}
	}

	if ($isSignInWithAppleGoogle == "") {
		$input = array(
			'ID_id' => $currentUserID,
			'ST_username' => $username,
			'ST_password' => $password,
			'ST_email' => $email,
			'ST_iosDeviceToken' => $iosDeviceToken,
			'ST_androidDeviceToken' => $androidDeviceToken,
			'BL_emailVerified' => true,
			'NU_badge' => 0,
			'ST_signInWith' => $_POST['signInWith'],
			// createdAt & updatedASt dates
			'DT_createdAt' => date('Y-m-d\TH:i:s'),
			'DT_updatedAt' => date('Y-m-d\TH:i:s'),
		);
		array_push($data_array, $input);
		$data = json_encode($data_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		file_put_contents('Users.json', $data);
		echo $currentUserID. "-";	
	}
	

//------------------------------------
// MARK - SIGN UP WITH USERNAME & EMAIL
//------------------------------------
} else {
	// check for existing username
	foreach ($data_array as $item) {
		if ($item['ST_username'] == $username) {
			$usernameExists = true;
			echo 'e_101';
			return;
		}
	}

	// check for existing email
	foreach ($data_array as $item) {
		if ($item['ST_email'] == $email) {
			$emailExists = true;
			echo 'e_102';
			return;
		}
	}

	// sign up
	if (!$usernameExists && !$emailExists) {
		$input = array(
			'ID_id' => $currentUserID,
			'ST_username' => $username,
			'ST_password' => $password,
			'ST_email' => $email,
			'ST_iosDeviceToken' => $iosDeviceToken,
			'ST_androidDeviceToken' => $androidDeviceToken,
			'BL_emailVerified' => false,
			'NU_badge' => 0,
			'ST_signInWith' => "",
			// createdAt & updatedASt dates
			'DT_createdAt' => date('Y-m-d\TH:i:s'),
			'DT_updatedAt' => date('Y-m-d\TH:i:s'),
		);

		array_push($data_array, $input);
		$data = json_encode($data_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		file_put_contents('Users.json', $data);

		// echo currentUser ID
		echo $currentUserID;

		// Send email verification email
		if ($IS_EMAIL_VERIFICATION) {

			/* Default sendmail function 
			$to = $email;
			$subject = "Email verification link | ".htmlspecialchars($APP_NAME);
			$message = 'Welcome to '.htmlspecialchars($APP_NAME).'!'."\n";
			$message .= 'Please verify your email address by clicking the following link:'."\n".htmlspecialchars($DATABASE_PATH).'_Tables/email-verification.php?email='.$email;
			$headers = "From: verification@".htmlspecialchars($APP_NAME).".com";
			mail($to,$subject,$message,$headers);
			*/

			/* Pear Mail.php function */
			require_once "Mail.php";
			$from = 'verification@'.$APP_NAME.'.com';
			$to = $email;
			$subject = "Email verification link | ".$APP_NAME;
			$message = 'Welcome to '.$APP_NAME.'!'."\n";
			$message .= 'Please verify your email address by clicking the following link:'."\n".htmlspecialchars($DATABASE_PATH).'_Tables/email-verification.php?email='.$email;
			$headers = array('From' => $from, 'To' => $to, 'Subject' => $subject, 'Reply-To' => $from);
			$smtp = Mail::factory('smtp', array(
			        'host' => $SMTP_HOST,
			        'port' => '465',
			        'auth' => true,
			        'username' => $EMAIL_FOR_SENDMAIL, 
			        'password' => $PASSWORD_FOR_SENDMAIL
			));
			// Send email
			$mail = $smtp->send($to, $headers, $message);
		}

	}// ./ sign up

}// ./ If
?>
