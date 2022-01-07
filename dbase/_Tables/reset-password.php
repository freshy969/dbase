<?php include '../_config.php';
	$email = $_GET['email']; 
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>XServer | Reset Password</title>
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
	<br><br>
    <div class="row" style="padding: 15px">
    	<div class="col-md-6 offset-md-3 text-center">
				<img src="../assets/img/favicon.png" width="40">
				<br><br>
		
				<div class="page-header text-center">
					<h2 class="pageheader-title">Reset Password</h2>
				</div>
				<!-- password --> 
				<div class="form-group">
					<input class="form-control" type="password" id="ST_password" name="ST_password" placeholder="Type a new password">
				</div>
				<!-- reset password button -->
				<button class="btn btn-info btn-block" onclick="resetPassword()"><i class="fas fa-lock"></i> Reset Password</button>
			</div>
		</div><!-- ./ row --> 
<?php include '../footer.php'; ?>
<script>
	function resetPassword() {
		"use strict";

		var password = $('#ST_password').val();
		var email = '<?php echo $email ?>';
		
		// Password field has email
		if (password != null) {
			$.ajax({
				url : 'rp-function.php',
				type: 'POST',
				data: 'email=' + email + '&ST_password=' + password,
					success: function(data) {
						Swal.fire({title: 'Yeah!', text: 'Password successfully updated!', icon: 'success', showCancelButton: false, confirmButtonText: 'OK', allowOutsideClick: false });
					// error
					}, error: function(e) {  
						Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong.', });
					}
			});

		// Password field is empty!
		} else {
			Swal.fire({title: 'Oops', text: 'Please type a password!', icon: 'error', showCancelButton: false, confirmButtonText: 'OK', allowOutsideClick: false });
		}
}
</script>
