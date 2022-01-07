<?php include '_config.php';
	// session_start(); 
	$username = $_POST['username'];
	$password = $_POST['password'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $APP_NAME ?> | Sign In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo htmlspecialchars($DATABASE_PATH) ?>/assets/img/favicon.png" />
    <script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/libs/js/fontawesomekit.js"></script>
	<link rel="stylesheet" href="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/bootstrap/css/bootstrap.min.css">    
    <script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/swal2.js"></script>
    <link href="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/libs/css/style.css">
    <link href="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/lity/dist/lity.css" rel="stylesheet">
</head>
<body>
<br><br>
	<div class="col-md-4 offset-md-4 text-center">

<!-- Admin user -->
<?php if ($username === $ADMIN_USERNAME && $password === $ADMIN_PASSWORD){ 
	$_SESSION['username'] = $username;
	$_SESSION['password'] = $password;
?>
<script>document.location.href = '_Tables/index.php?tableName=Users';</script>

<!-- Demo user -->
<?php } else if ($username === "demo" && $password === "demo"){ 
	$_SESSION['username'] = $username;
	$_SESSION['password'] = $password;
?>
<script>document.location.href = '_Tables/index.php?tableName=Users';</script>

<?php 
// wrong username and/or password	
} else { ?>
<script>
    Swal.fire({
		title: '<?php echo htmlspecialchars($APP_NAME) ?>',
		text: "	Wrong username or password!",
		icon: 'error',
		showCancelButton: false,
		confirmButtonColor: '#212121',
		confirmButtonText: 'Retry',
		allowOutsideClick: false,
	}).then((result) => {
		if (result.value) {
			document.location.href = 'index.php';
		}// ./ If
	});
</script>
</div>
<?php } ?>

</body>
</html>