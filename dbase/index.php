<?php include '_config.php'; 
    // Logout Admin from SESSION
    unset($_SESSION['username']);
    unset($_SESSION['password']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $APP_NAME ?> | Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo htmlspecialchars($DATABASE_PATH) ?>/assets/img/favicon.png" />
    <script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/js/fontawesomekit.js"></script>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/swal2.js"></script>
    <link href="<?php echo htmlspecialchars($DATABASE_PATH) ?>/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($DATABASE_PATH) ?>/assets/css/style.css">
</head>
<body>
<div class="text-center login-body">
	<img class="logo-img" src="assets/img/favicon.png" alt="logo" width="34">
	<br>
	<h2><strong style="color: #121212;"><?php echo $APP_NAME ?></strong></h2>
	<h3 style="color: #121212;">Admin panel</h3>
	<p style="color: #333;">Sign In to enter the Database</p>
	
	<form action="login.php" method="POST">
		<div class="form-group">
			<input class="login-form-control login-input-top" id="username" name="username" type="text" placeholder="Username" autocomplete="off">
		</div>
		<div class="form-group">
			<input class="login-form-control login-input-bottom" id="password" name="password" type="password" placeholder="Password" autocomplete="off">
		</div>
		<button class="login-button" type="submit" name="login"> Login </button>
	</form>
</div>

</body>
</html>
