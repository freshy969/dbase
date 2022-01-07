<?php include '../_config.php';

   // GET VARIABLES
   $token = $_POST['deviceToken']; 
   $message = $_POST['message'];
   $pushType = $_POST['pushType'];

   // Get JSON Table's data (to update badge number)
   $data = file_get_contents('../_Tables/Users.json');
   $data_array = json_decode($data, true);
   
   // Get User
   foreach ($data_array as &$obj) {
      if ($obj['ST_iosDeviceToken'] == $token) {
         $badge = $obj['NU_badge'];
         echo 'badge: ' .$badge.'<br>';
         
         // JSON message structure
         $body['aps'] = array(
            'alert' => array(
               'body' => $message,
               'pushType' => $pushType,
            ),
            'badge' => $badge + 1,
            'sound' => 'bingbong.aiff',
         );
         $payload = json_encode($body);

         $key = openssl_pkey_get_private('file://'.$AUTH_KEY_FILE);
         $header = ['alg'=>'ES256','kid'=>$APN_KEY_ID];
         $claims = ['iss'=>$TEAM_ID,'iat'=>time()];
         $header_encoded = base64($header);
         $claims_encoded = base64($claims);
         $signature = '';
         openssl_sign($header_encoded . '.' . $claims_encoded, $signature, $key, 'sha256');
         $jwt = $header_encoded . '.' . $claims_encoded . '.' . base64_encode($signature);
         // only needed for PHP prior to 5.5.24
         if (!defined('CURL_HTTP_VERSION_2_0')) { define('CURL_HTTP_VERSION_2_0', 3); }
         $http2ch = curl_init();
         curl_setopt_array($http2ch, array(
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
            CURLOPT_URL => "$APN_URL/3/device/$token",
            CURLOPT_PORT => 443,
             CURLOPT_HTTPHEADER => array(
               "apns-topic: {$BUNDLE_ID}",
               "authorization: bearer $jwt"
            ),
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HEADER => 1
         ));

         $result = curl_exec($http2ch);
         if ($result === FALSE) { 
            throw new Exception("Curl failed: ".curl_error($http2ch));
         }
         $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
         if ($status == "200") { echo $payload;

            // Update badge number
            $obj['NU_badge'] = (int)$badge + 1;
            $data = json_encode(array_values($data_array), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            file_put_contents('../_Tables/Users.json', $data);

         } else if ($status == "400") { echo "e_104";
         } else { echo $status; }

      }// ./ If
   }// ./ For

   function base64($data) {
      return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
   }  
?>