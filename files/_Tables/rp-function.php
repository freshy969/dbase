<?php include '../_config.php';
$email = $_POST['email']; 
$password = htmlspecialchars($_POST['ST_password']); 

// Get JSON Table's data
$data = file_get_contents('Users.json');
$data_array = json_decode($data, true);

// Search for email in database
foreach ($data_array as $item) {
	if ($item['ST_email'] == $email) {
		$index = array_search($item, $data_array);
		$obj = $data_array[$index];
	}
}

// Reset password
if(isset($password)){
	if ($password != "") {
		$newDataArr = array();

		foreach ($data_array[$index] as $k=>$v){
			if (isset($_POST[$k])) { $newDataArr[$k] = $_POST[$k]; } else { $newDataArr[$k] = $obj[$k]; }
		}// ./ foreach
		$data_array[$index] = $newDataArr;
		
		// Encode back to JSON
		$data = json_encode(array_values($data_array), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		file_put_contents('Users.json', $data);

		echo 'ok';

	// error
	} else { echo 'error'; }
// error
} else { echo 'error'; }
?>