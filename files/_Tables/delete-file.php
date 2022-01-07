<?php
if($_GET['fileURL'] != '') {
    $fileURL = '../uploads/'.$_GET['fileURL'];

    $tableName = $_GET['tableName'];
    $rowID = $_GET['rowID'];
    $columnName = $_GET['columnName'];
    
    if (unlink($fileURL)) {
        echo "ok";

        // Get tableName data
        $data = file_get_contents($tableName. '.json');
        $data_array = json_decode($data, true);
        
        foreach ($data_array as $item) {
			if ($item['ID_id'] == $rowID) {
				$index = array_search($item, $data_array);
        		$obj = $data_array[$index];

        		// Update obj
				$obj[$columnName] = '';
				$data_array[$index] = $obj;
				
				$data = json_encode(array_values($data_array), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				file_put_contents($tableName. '.json', $data);
			}
		} //./ foreach

	// Error
    } else { echo "error"; }

// No file
} else { echo 'no file'; } 
?>
