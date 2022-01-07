<?php 
	$files = array();
	$jsonFiles = glob('*.json');
	for ($i=0; $i<count($jsonFiles); $i++) {
		$jFile = str_replace( '.json', '', $jsonFiles[$i] );
		echo $jFile.',';
	}
?>