<?php include '../_config.php';

$tableName = $_GET['tableName'];
$columnType = $_GET['columnType'];
$pointerTable = $_GET['pointerTable'];

// HTML sanitization
if (strpos($tableName, '<') !== false || strpos($tableName, '>') !== false
){ $tableName = preg_replace("/[^a-zA-Z]/", "", $tableName); }
	
if ($columnType == 'string') { 
	$columnValue = ""; 
	$columnName = 'ST_'.$_GET['columnName'];
} else if ($columnType == 'number') { 
	$columnValue = 0; 
	$columnName = 'NU_'.$_GET['columnName'];
} else if ($columnType == 'array') { 
	$columnValue = array(); 
	$columnName = 'AR_'.$_GET['columnName'];
} else if ($columnType == 'boolean') { 
	$columnValue = false; 
	$columnName = 'BL_'.$_GET['columnName'];
} else if ($columnType == 'gps') { 
	$columnValue = array("0","0"); 
	$columnName = 'GPS_'.$_GET['columnName'];
} else if ($columnType == 'pointer') { 
	$columnValue = ""; 
	$columnName = 'PO_'.$_GET['columnName'].'_'.$pointerTable;
} else if ($columnType == 'file') { 
	$columnValue = ""; 
	$columnName = 'FL_'.$_GET['columnName'];
} else if ($columnType == 'date') { 
	$columnValue = date('Y-m-d\TH:i:s'); 
	$columnName = 'DT_'.$_GET['columnName'];
} 

// Get JSON data
$data = file_get_contents($tableName. '.json');
$data_array = json_decode($data, true);

// In case $data_array is empty
$input = array();
if(count($data_array) == 0){
	
	$input = array(
		'ID_id' => '---', // generateRandomID(),
		'DT_createdAt' => date('Y-m-d\TH:i:s'),
		'DT_updatedAt' => date('Y-m-d\TH:i:s'),
	);

	// In case tableName == User
	if($tableName == 'Users'){
		$input = array(
			'ID_id' => '---', // generateRandomID(),
			'DT_createdAt' => date('Y-m-d\TH:i:s'),
			'DT_updatedAt' => date('Y-m-d\TH:i:s'),
			'ST_username' => '',
			'ST_password' => '',
			'ST_email' => '',
			'ST_iosDeviceToken' => '',
			'ST_androidDeviceToken' => '',
			'NU_badge' => 0,
			'BL_emailVerified' => false,
			'ST_signInWith' => "",
		);	
	}
	
	array_push($data_array, $input);
}

// add column
$newDataArr = array();
foreach($data_array as $obj){
    $obj[$columnName] = $columnValue;
    $newDataArr[] = $obj;
}
$data_array = $newDataArr;

// Encode $data back to JSON
$data = json_encode(array_values($data_array), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents($tableName. '.json', $data);

echo 'ok';
?>