<?php include '../_config.php';
$tableName = $_POST['tableName'];
$key = $_POST['columnName'];
$orderBy = $_POST['orderBy'];


// HTML sanitization
if (strpos($tableName, '<') !== false || strpos($tableName, '>') !== false
){ $tableName = preg_replace("/[^a-zA-Z]/", "", $tableName); }

// Get JSON Table's data
$data = file_get_contents($tableName. '.json');
$data_array = json_decode($data, true);

// Order data
if (isset($key)) {
	if ($orderBy != ""){
		// Ascending
		if ($orderBy == "ascending") {
			usort($data_array, function  ($item1, $item2) use ($key)  {
				return $item1[$key] <=> $item2[$key];
			});
		// Descending
		} else if ($orderBy == "descending") {
			usort($data_array, function  ($item1, $item2) use ($key)  {
				return $item2[$key] <=> $item1[$key];
			});
		}
	// Descending (default)
	} else {
		usort($data_array, function  ($item1, $item2) use ($key)  {
			return $item2[$key] <=> $item1[$key];
		});
	}
} else {
	usort($data_array, function  ($item1, $item2) use ($key)  {
		return $item2['DT_createdAt'] <=> $item1['DT_createdAt'];
	});
}

// Remove ID with '---' in case there's only the default empty object (for column names)
for ($i=0; $i<count($data_array); $i++) {
	if ($data_array[$i]['ID_id'] == '---') {  unset($data_array[$i]); }
}

echo json_encode(array_values($data_array), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>