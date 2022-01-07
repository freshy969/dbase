<?php include '../_config.php';
$email = $_GET['email'];

// Get json data
$data = file_get_contents('Users.json');
$data_array = json_decode($data, true);
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $APP_NAME ?> | Email verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo htmlspecialchars($DATABASE_PATH) ?>/assets/img/favicon.png" />
    <script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/js/fontawesomekit.js"></script>
	<link rel="stylesheet" href="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/bootstrap/css/bootstrap.min.css">    
    <script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/swal2.js"></script>
    <link href="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/css/style.css">
    <link href="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/lity/dist/lity.css" rel="stylesheet">
</head>
<body>
	<div class="dashboard-main-wrapper">
	<div class="row">
		<div class="col-md-8 offset-md-2 text-center">
			<img src="<?php echo htmlspecialchars($DATABASE_PATH) ?>/assets/img/favicon.png" width="30">
			<br><br>
			
			<?php
				$done = false;
				foreach ($data_array as $item) {
					$index = array_search($item, $data_array);
					if ($item['ST_email'] == $email) { ?>
						<h3>You email has been successfully verified!</h3>
						
						<?php // update emailVerified into True
							$data_array[$index]['BL_emailVerified'] = true;
							$data = json_encode(array_values($data_array), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
							file_put_contents('Users.json', $data);
							$done = true;
					}
				}	
				foreach ($data_array as $item) {
					if (!$done) {
						if ($item['ST_email'] != $email) { ?>
							<h3>Sorry, this email address does not exists in our database, the verification failed.</h3>
					<?php $done = true; }
					}
				}
			?>
		</div>
	</div>
</div>
</body>
</html>