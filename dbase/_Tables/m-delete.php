<?php include '../_config.php';
$tableName = $_POST['tableName'];
$id = $_POST['id'];

// HTML sanitization
if (strpos($tableName, '<') !== false || strpos($tableName, '>') !== false
){ $tableName = preg_replace("/[^a-zA-Z]/", "", $tableName); }

// Get JSON Table's data
$data = file_get_contents($tableName. '.json');
$data_array = json_decode($data, true);

foreach ($data_array as $item) {
	if ($item['ID_id'] == $id) {
		$obj = $item;
		
		// Delete files (if any)
		foreach ($obj as $k=>$v){
			$keysArr = explode("_", $k);
			$kType = $keysArr[0];
			if($kType == 'FL'){
				$fileURL = $obj[$k];
				$fileArr = explode("/", $fileURL);
				$fileName = end($fileArr);
				unlink('../uploads/'.$fileName);
			}
		}

		// Delete the obj
		$index = array_search($obj, $data_array);
		unset($data_array[$index]);
		$data_array = array_values($data_array);

		// Encode back to json
		$data = json_encode($data_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		file_put_contents($tableName. '.json', $data);

		echo 'deleted';
	} 
}
?>
