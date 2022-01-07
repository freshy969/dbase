<?php include '../_config.php';

// GET VARIABLES
$token = $_POST['deviceToken'];  
$message = $_POST['message'];
$pushType = $_POST['pushType'];
$registrationIDs = array($token);

$headers = array(
	'Content-Type:application/json',
    'Authorization:key=' . $FCM_SERVER_KEY
);

$fields = array(
	'registration_ids' => $registrationIDs,
	'data' => array(
		"body" => $message,
		"pushType" => $pushType,
		'vibrate'	=> 1,
		'sound'		=> 1,
		'largeIcon'	=> 'large_icon',
		'smallIcon'	=> 'small_icon'
	),
	'notification' => array(
		"body" => $message,
		'pushType'	=> $pushType,
	)
);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );

echo $result;
?>