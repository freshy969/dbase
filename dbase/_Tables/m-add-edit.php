<?php include '../_config.php';
$tableName = $_POST['tableName'];
$isEditing = false;
$obj = array();

// HTML sanitization
if (strpos($tableName, '<') !== false || strpos($tableName, '>') !== false
){ $tableName = preg_replace("/[^a-zA-Z]/", "", $tableName); }

// Get JSON Table's Data
$data = file_get_contents($tableName. '.json');
$data_array = json_decode($data, true);

// Editing an obj
if (isset($_POST['ID_id'])) { 
	$isEditing = true;
	$id = $_POST['ID_id'];

	foreach ($data_array as $item) {
		if ($item['ID_id'] == $id) {
			$index = array_search($item, $data_array);
			$obj = $data_array[$index];
		}
	}

// Create new obj
} else { $id = generateRandomID(); }


//---------------------------------
// SAVE DATA
//---------------------------------
$newDataArr = array();

foreach ($data_array[0] as $k=>$v){
	$keysArr = explode("_", $k);
	$kType = $keysArr[0];
	$kName = $keysArr[1];

	// Editing Data --------------------------------------------
	if ($isEditing) {

		// Bool
		if ($kType == 'BL') {
			if (isset($_POST[$k]) ){ 
				if ($_POST[$k] == 1) {$newDataArr[$k] = true;}else{$newDataArr[$k] = false;} 
			} else { $newDataArr[$k] = $obj[$k]; }

		// Array or GPS	
		} else if ($kType == 'AR' || $kType == 'GPS') {
			if (isset($_POST[$k])) { 
				if($_POST[$k] != ''){ $newDataArr[$k] = explode(",", $_POST[$k]); 
				} else { $newDataArr[$k] = array(); }
			} else { $newDataArr[$k] = $obj[$k]; }

		// Number
		} else if ($kType == 'NU') {
			if (isset($_POST[$k])) { $newDataArr[$k] = (float)$_POST[$k]; 
			} else { $newDataArr[$k] = $obj[$k]; }

		// updatedAt
		} else if ($k == 'DT_updatedAt') { $newDataArr[$k] = date('Y-m-d\TH:i:s'); 

		// other data types	
		} else {
			if (isset($_POST[$k])) { $newDataArr[$k] = $_POST[$k]; 
			} else { $newDataArr[$k] = $obj[$k]; }
		}


	// Add New data --------------------------------------------
	} else { 

		// ID	
		if ($kType == 'ID') { $newDataArr[$k] = $id; 

		// Bool
		} else if ($kType == 'BL') {
			if (isset($_POST[$k]) ){ 
				if ($_POST[$k] == 1) {$newDataArr[$k] = true;}else{$newDataArr[$k] = false;} 
			} else { $newDataArr[$k] = false; }

		// Array	
		} else if ($kType == 'AR') {
			if (isset($_POST[$k])) {
				if($_POST[$k] != ''){ $newDataArr[$k] = explode(",", $_POST[$k]); 
				} else { $newDataArr[$k] = array(); }
			} else { $newDataArr[$k] = array(); }

		// GPS
		} else if ($kType == 'GPS') {
			if (isset($_POST[$k])) { 
				if($_POST[$k] != ','){ $newDataArr[$k] = explode(",", $_POST[$k]); 
				} else { $newDataArr[$k] = array("0","0"); }
			} else { $newDataArr[$k] = ["0","0"]; }

		// Number
		} else if ($kType == 'NU') {
			if (isset($_POST[$k])) { $newDataArr[$k] = (float)$_POST[$k]; 
			} else { $newDataArr[$k] = 0; }

		// Date
		} else if ($kType == 'DT' && $k != 'DT_createdAt' && $k != 'DT_updatedAt') {
			if (isset($_POST[$k])) { $newDataArr[$k] = date("Y-m-d\TH:i:s", strtotime($_POST[$k])); 
			} else { $newDataArr[$k] = date("Y-m-d\TH:i:s"); }

		} else if ($kType == 'DT' && $k == 'DT_createdAt') {
			$newDataArr['DT_createdAt'] = date('Y-m-d\TH:i:s'); 
		} else if ($kType == 'DT' && $k == 'DT_updatedAt') {
			$newDataArr['DT_updatedAt'] = date('Y-m-d\TH:i:s'); 
		

		// Other data types	
		} else {
			if (isset($_POST[$k])) { $newDataArr[$k] = $_POST[$k]; 
			} else { $newDataArr[$k] = ""; }
		}

	} // ./ If
	

} // ./ foreach


// Update the selected row
if ($isEditing) { $data_array[$index] = $newDataArr;
// Add a new row
} else { array_push($data_array, $newDataArr); }

// Encode back to JSON
$data = json_encode(array_values($data_array), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents($tableName. '.json', $data);

// echo JSON object
echo json_encode($newDataArr);
?>