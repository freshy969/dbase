<?php
$tableName = $_POST['tableName'];
$reorderedData = $_POST['reorderedData'];

$data_array = json_decode($reorderedData, true);

// Encode back to JSON
$data = json_encode(array_values($data_array), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents($tableName. '.json', $data);

// echo JSON object
echo json_encode($data);
?>