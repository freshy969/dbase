<?php include '../_config.php';
$tableName = $_GET['tableName'];

// HTML sanitization
if (strpos($tableName, '<') !== false || strpos($tableName, '>') !== false
){ $tableName = preg_replace("/[^a-zA-Z]/", "", $tableName); }

// Create an empry array of  data
$data_array = array();
$fp = fopen($tableName .'.json', 'w');
fwrite($fp, json_encode($data_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
fclose($fp);

echo 'ok';
?>