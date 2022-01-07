<?php include '../header.php';
$tableName = htmlspecialchars($_GET['tableName']);

// HTML sanitization
if (strpos($tableName, '<') !== false || strpos($tableName, '>') !== false
){ $tableName = preg_replace("/[^a-zA-Z]/", "", $tableName); }

$ids = explode(",", $_GET['rowIDs']);

// Get JSON data
$data = file_get_contents($tableName. '.json');
$data_array = json_decode($data, true);

$duplicatedObj;

// Duplicate row
foreach ($data_array as $obj) {
	if ($obj['ID_id'] == $ids[0]) {

		$duplicatedObj = $obj;
		$duplicatedObj["ID_id"] = generateRandomID();
		$duplicatedObj["DT_createdAt"] = date("Y-m-d\TH:i:s");
		$duplicatedObj["DT_updatedAt"] = date("Y-m-d\TH:i:s");

		array_push($data_array, $duplicatedObj); 

		// encode back to json
		$data = json_encode(array_values($data_array), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		file_put_contents($tableName. '.json', $data);
	} //./ If

} //./ foreach


include '../footer.php' 
?>
<script>
	Swal.fire({ title: 'Cool!', text: "The row has been duplicated", icon: 'success', showCancelButton: false, confirmButtonText: 'Go back', allowOutsideClick: false
	}).then((result) => {
		if (result.value) { window.location.href = 'index.php?tableName=<?php echo $tableName ?>'; }
	});		
</script>