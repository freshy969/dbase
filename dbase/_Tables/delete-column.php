<?php include '../header.php';
$tableName = $_GET['tableName'];
$colName = $_GET['colName'];

// get json data
$data = file_get_contents($tableName. '.json');
$data_array = json_decode($data, true);

// loop through array
foreach($data_array as $key => $item){
    // delete column
    unset($data_array[$key][$colName]);
}

// encode back to json
$data = json_encode(array_values($data_array), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents($tableName. '.json', $data);

include '../footer.php' 
?>
<script>showSuccessDeletionAlert('<?php echo htmlspecialchars($tableName) ?>', "The column has been successfully deleted");</script>