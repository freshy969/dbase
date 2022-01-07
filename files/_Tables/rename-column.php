<?php include '../header.php';

$tableName = $_GET['tableName'];
$colToRename = $_GET['colToRename'];
$newColName = $_GET['newColName'];

// HTML sanitization
if (strpos($tableName, '<') !== false || strpos($tableName, '>') !== false
){ $tableName = preg_replace("/[^a-zA-Z]/", "", $tableName); }

// Get JSON data
$data = file_get_contents($tableName. '.json');
$data_array = json_decode($data, true);


function replaceKey($oldKey, $newKey, array $input){
    $return = array(); 
    foreach ($input as $key => $value) {
        if ($key===$oldKey)
            $key = $newKey;

        if (is_array($value))
            $value = replaceKey( $oldKey, $newKey, $value);

        $return[$key] = $value;
    }
    return $return; 
}
$data_array = replaceKey($colToRename, $newColName, $data_array);

// Encode $data back to JSON
$data = json_encode(array_values($data_array), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents($tableName. '.json', $data);


include '../footer.php';
?>
<script>showSuccessDeletionAlert('<?php echo $tableName ?>', "Column successfully renamed into '<?php echo $newColName ?>'");</script>
