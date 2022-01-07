<?php include "_config.php";

/* Pear Mail.php function */
require_once "Mail.php";
$from = htmlspecialchars($_POST['from']);
$to = htmlspecialchars($_POST['email']);
$message = htmlspecialchars($_POST['message']);
$subject = htmlspecialchars($_POST['subject']);
$headers = array('From' => $from, 'To' => $to, 'Subject' => $subject, 'Reply-To' => $from);
$smtp = Mail::factory('smtp', array(
    'host' => $SMTP_HOST,
    'port' => '465',
    'auth' => true,
    'username' => $EMAIL_FOR_SENDMAIL, 
    'password' => $PASSWORD_FOR_SENDMAIL
));
// Send the mail
$mail = $smtp->send($to, $headers, $message);
// Check if mail has been sent (optional)
if (PEAR::isError($mail)) { echo 'Email not sent :(';
} else { echo 'Email sent!'; }


/*
// Default sendmail //
$to = htmlspecialchars($_POST['email']);
$message = htmlspecialchars($_POST['message']);
$subject = htmlspecialchars($_POST['subject']);
$headers = "From: " .htmlspecialchars($_POST['from']);
mail($to, $subject, $message, $headers);
*/
?>
