<?php session_start();

//-----------------------------------------------------
// URL PATH WHERE YOU'VE HOSTED THE DBase FILES
//-----------------------------------------------------
$DATABASE_PATH = "http://yourdomain/dbase/"; 


//---------------------------------
// APPLICATION NAME
//---------------------------------
$APP_NAME = "DBase";


//---------------------------------
// ADMIN LOGIN CREDENTIALS
//---------------------------------
$ADMIN_USERNAME = "admin";
$ADMIN_PASSWORD = "admin";


//---------------------------------
// EMAILS
//---------------------------------
$ADMIN_EMAIL = "myemail@address.com"; // <- your primary email address

// Data for Pear Mail PHP
$EMAIL_FOR_SENDMAIL = "email@example.com"; // <-- set an email address for send-mail.php
$PASSWORD_FOR_SENDMAIL = "your-password"; // <-- set a password
$SMTP_HOST = "ssl://address"; // <-- Your ssl address


//---------------------------------
// GOOGLE SIGN IN - WEB KEY
//---------------------------------
$GOOGLE_SIGNIN_KEY = ''; 


//------------------------------------------------
// IOS PUSH NOTIFICATIONS -> PASTE DATA KEYS BELOW
//------------------------------------------------
$AUTH_KEY_FILE = 'AuthKey_ABC123EFG.p8'; // Your p8 Key file name
$APN_KEY_ID = 'ABC123EFG';    // Your Apple Push Notification Key ID, you can get it by removing 'AuthKey_' and '.p8' from your Key file's name
$TEAM_ID = 'Z123ABC456D';   
$BUNDLE_ID = 'com.yourname.appname';    // Your iOS App's Bundle Identifier, the one you've set in Xcode
$APN_URL = 'https://api.development.push.apple.com'; // OR: 'https://api.push.apple.com';   [for Production environment] */


//---------------------------------------------------------------------------------------
// ANDROID PUSH NOTIFICATIONS -> PASTE YOUR FIREBASE CLOUD MESSAGIN SERVER KEY BELOW
//---------------------------------------------------------------------------------------
$FCM_SERVER_KEY = '';


//-----------------------------------------------------------------------------------------------------
// MARK - SET THIS VARIBALE TO true IF YOU WANT REGISTERED USERS TO GET A VERIFICATION LINK BY EMAIL
//-----------------------------------------------------------------------------------------------------
$IS_EMAIL_VERIFICATION = false;



//------------------------------------------------
//------------------------------------------------
//------------------------------------------------

// UTILITY FUNCTIONS AND GLOBAL VARIABLES
//------------------------------------------------

$TABLES_PATH = $DATABASE_PATH."_Tables/index.php?tableName="; 

// Generate a random ID string
function generateRandomID($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomID = '';
	for ($i = 0; $i < $length; $i++) {
		$randomID .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomID;
}

// Round large numbers into KMGT
function roundNumbersIntoKMGT($n) {
  $n = (0+str_replace(",","",$n));
  if(!is_numeric($n)) return false;
  if($n>1000000000000) return round(($n/1000000000000),1).'T';
  else if($n>1000000000) return round(($n/1000000000),1).'G';
  else if($n>1000000) return round(($n/1000000),1).'M';
  else if($n>1000) return round(($n/1000),1).'K';
  return number_format($n);
}

// Shorten a string
function substrwords($text, $maxchar, $end='...') {
   if (strlen($text) > $maxchar || $text == '') {
      $words = preg_split('/\s/', $text);      
      $output = '';
      $i      = 0;
      while (1) {
         $length = strlen($output)+strlen($words[$i]);
         if ($length > $maxchar) {
            break;
         } else {
            $output .= " " . $words[$i];
            ++$i;
         }
      }
      $output .= $end;
   } else { $output = $text; }
return $output;
}

// String starts with
function startsWith($haystack, $needle){
   $length = strlen($needle);
   return (substr($haystack, 0, $length) === $needle);
}

// String ends with
function endsWith($haystack, $needle){
   $length = strlen($needle);
   if ($length == 0) {
      return true;
   }
   return (substr($haystack, -$length) === $needle);
}
?>