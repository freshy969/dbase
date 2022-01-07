<?php include '../header.php';
$tableName = htmlspecialchars($_GET['tableName']);
$id = $_GET['rowID'];

// HTML sanitization
if (strpos($tableName, '<') !== false || strpos($tableName, '>') !== false
){ $tableName = preg_replace("/[^a-zA-Z]/", "", $tableName); }
	
// Get JSON data
$data = file_get_contents($tableName. '.json');
$data_array = json_decode($data, true);

// Create a default row in case $data_array will get empty
$default_row = array();
foreach ($data_array[0] as $k=>$v){
	$keysArr = explode("_", $k);
	$kType = $keysArr[0];
	$kName = $keysArr[1];

	if ($kType == 'ID') { $default_row[$k] = "---"; }
	if ($kType == 'ST') { $default_row[$k] = ""; }
	if ($kType == 'NU') { $default_row[$k] = 0; }
	if ($kType == 'AR') { $default_row[$k] = array(); }
	if ($kType == 'FL') { $default_row[$k] = ""; }
	if ($kType == 'GPS') { $default_row[$k] = ["0","0"]; }
	if ($kType == 'PO') { $default_row[$k] = ""; }
	if ($kType == 'DT') { $default_row[$k] = ""; }
	if ($kType == 'BL') { $default_row[$k] = false; }
}


// Iterate through $data_array's objects
foreach ($data_array as $item) {
	if ($item['ID_id'] == $id) {
		$obj = $item;

		// Delete the obj
		$index = array_search($obj, $data_array);
		unset($data_array[$index]);
		$data_array = array_values($data_array);

		// $data_array is empty -> leave an empty row
		if (count($data_array) == 0) { array_push($data_array, $default_row); }

		// Encode back to JSON
		$data = json_encode($data_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		file_put_contents($tableName. '.json', $data);
	}
}

include '../footer.php' 
?>
<script>showSuccessDeletionAlert('<?php echo $tableName ?>', "The row has been successfully deleted");</script>