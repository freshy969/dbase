<?php include '../header.php';
$tableName = $_GET['tableName'];

$dir = htmlspecialchars($DATABASE_PATH). "_Tables/";
$jsonFiles = glob('*.json');
for ($i=0; $i<count($jsonFiles); $i++) {
	// Delete Table (the JSON file)
	if($jsonFiles[$i] == $tableName.'.json') { unlink($jsonFiles[$i]); }
}

include '../footer.php' 
?>
<script>showSuccessDeletionAlert('Users', "Table successfully deleted");</script>