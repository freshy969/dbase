<?php include '../_config.php';
$email = $_GET['email'];
$emailExists = false;

// get JSON data
$data = file_get_contents('Users.json');
$data_array = json_decode($data, true);

foreach ($data_array as $item) {
	if ($item['ST_email'] == $email) {
		if ($item['ST_signInWith'] == '') {
			$index = array_search($item, $data_array);
			$row = $data_array[$index];
			$emailExists = true;
		} else {
			echo 'e_302';
			return;
		}
	}
}

// Send reset password email
if ($emailExists) {

	/* Default sendmail function
	$to = htmlspecialchars($email);
	$message = 'Please click the following link to reset your password:'."\n".htmlspecialchars($DATABASE_PATH).'_Tables/reset-password.php?email='.$email;
	$subject = 'Reset Password link | '.htmlspecialchars($APP_NAME);
	$headers = 'From: no-reply@'.htmlspecialchars($APP_NAME).'.com';
	mail($to, $subject, $message, $headers);
	*/

	/* Pear Mail.php function */
	require_once "Mail.php";
	$from = 'no-reply@'.$APP_NAME.'.com';
	$to = $email;
	$subject = 'Reset Password link | '.htmlspecialchars($APP_NAME);
	$message = 'Please click the following link to reset your password:'."\n".htmlspecialchars($DATABASE_PATH).'_Tables/reset-password.php?email='.$email;
	$headers = array('From' => $from, 'To' => $to, 'Subject' => $subject, 'Reply-To' => $from);
	$smtp = Mail::factory('smtp', array(
		'host' => $SMTP_HOST,
		'port' => '465',
		'auth' => true,
		'username' => $EMAIL_FOR_SENDMAIL, 
		'password' => $PASSWORD_FOR_SENDMAIL 
	));
	$mail = $smtp->send($to, $headers, $message);
	

	echo 'Thanks, an email with a link to reset your password has been sent. Check your Inbox or Junk folder in a little while.';

// Email doesn't exists
} else { echo 'e_301'; }

?>
