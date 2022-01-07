<?php include '../header.php';
$tableToRename = $_GET['tableToRename'];
$newTableName = $_GET['newTableName'];

$dir = htmlspecialchars($DATABASE_PATH). "_Tables/";
$jsonFiles = glob('*.json');
for ($i=0; $i<count($jsonFiles); $i++) {
	// Rename Table (the JSON file)
	if($jsonFiles[$i] == $tableToRename.'.json') {
		rename($jsonFiles[$i], $newTableName.'.json');
	}
}

include '../footer.php' 
?>
<script>showSuccessDeletionAlert('<?php echo $newTableName ?>', "Table successfully renamed into " + '<?php echo $newTableName ?>');</script>
