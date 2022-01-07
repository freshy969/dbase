<?php include '_config.php'; 
$tableName = $_GET['tableName'];

// HTML sanitization
if (strpos($tableName, '<') !== false || strpos($tableName, '>') !== false
){ $tableName = preg_replace("/[^a-zA-Z]/", "", $tableName); }

// If Admin is not logged in, go back to login page
if ($_SESSION['username'] == null || $_SESSION['password'] == null){
	header('Refresh:0; url=../index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>
   <?php echo $APP_NAME; if(isset($tableName) ){ echo ' | ' .htmlspecialchars($tableName); } ?></title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="shortcut icon" href="<?php echo htmlspecialchars($DATABASE_PATH) ?>/assets/img/favicon.png" />
   <script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/js/fontawesomekit.js"></script>
	<link rel="stylesheet" href="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/bootstrap/css/bootstrap.min.css">    
   <script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/swal2.js"></script>
   <link href="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
   <link rel="stylesheet" href="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/css/style.css">
   <link href="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/lity/dist/lity.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.css">
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/themes/dark.css">
</head>
<body>
	<div class="dashboard-main-wrapper">

		<!-- HUD -->
		<div id="hud"><i class="fas fa-circle-notch fa-spin"></i></div>

		<div id="top-navbar">

	        <!-- Navbar -->
	        <div class="dashboard-header">
	            <nav class="navbar navbar-expand-lg fixed-top">
	                <div class="navbar-brand"><img src="<?php echo  htmlspecialchars($DATABASE_PATH) ?>assets/img/favicon.png" width="30"> <?php echo $APP_NAME ?></div>

	                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	                    <span class="navbar-toggler-icon"><i class="las la-caret-down"></i></span>
	                </button>

	                <div class="collapse navbar-collapse " id="navbarSupportedContent">
	                    <ul class="navbar-nav ml-auto navbar-right-top">
	                       									
									<li class="nav-item dropdown connection">	
										<!-- Tools button -->
	                        	<a class="nav-link text-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="las la-tools"></i> Tools </a>
	                        	<div class="dropdown-menu dp-header" aria-labelledby="dropdownMenuLink">
	                        		
								   <!-- Add table -->
								   <?php if ($_SESSION['username'] == "demo") { ?>
								   	<a href="#" onclick="demoAlert()" class="nav-link text-dark"><i class="las la-plus"></i> <span> Add Table </span></a>
								   <?php } else { ?>
								   	<a href="#" data-toggle="modal" data-target="#addTableModal" class="nav-link text-dark"><i class="las la-plus"></i> <span> Add Table </span></a>
		                     <?php } ?>

		                     <!-- Import Tables -->
		                     <?php if ($_SESSION['username'] == "demo") { ?>
		                     	<a href="#" onclick="demoAlert()" class="nav-link text-dark"><i class="las la-file-upload"></i> <span> Import Tables</span></a>
		                     <?php } else { ?>
		                     	<a href="#" class="nav-link text-dark" onclick="importTables()"><i class="las la-file-upload"></i> <span> Import Tables</span></a>
		                     <?php } ?>

		                     <!-- Export a Table -->
		                     <?php if ($_SESSION['username'] == "demo") { ?>
		                           <a href="#" class="nav-link text-dark" onclick="demoAlert()"><i class="las la-file-download"></i> <span> Export a Table</span></a>
		                     <?php } else { ?>
		                        	<a href="#" class="nav-link text-dark" onclick="exportTable()"><i class="las la-file-download"></i> <span> Export a Table</span></a>   
		                     <?php } ?>
		                     
		                     <!-- Rename a table -->
								   <?php if ($_SESSION['username'] == "demo") { ?>
								   	<a href="#" onclick="demoAlert()" class="nav-link text-dark"><i class="las la-pen-alt"></i> <span> Rename a Table</span></a>
								   <?php } else { ?>
								   	<a href="#" data-toggle="modal" data-target="#renameTableModal" class="nav-link text-dark"><i class="las la-pen-alt"></i> <span> Rename a Table</span></a>
								   <?php } ?>

								   <!-- Delete a Table -->
								   <?php if ($_SESSION['username'] == "demo") { ?>
								   	<a href="#" onclick="demoAlert()" class="nav-link text-dark"><i class="las la-trash-alt"></i> <span> Delete a Table </span></a>
								   <?php } else { ?>
								   	<a href="#" class="nav-link text-dark" data-toggle="modal" data-target="#deleteTableModal" ><i class="las la-trash-alt"></i> <span> Delete a Table </span></a>
								   <?php } ?>

								   <div class="separator"></div>

								   <!-- Add Row -->
								   <?php if ($_SESSION['username'] == "demo") { ?>
								   	<a href="#" onclick="demoAlert()" class="nav-link text-dark"><i class="las la-window-minimize"></i> <span> Add Row </span></a>
								   <?php } else { 
								   	// fetch data from json
								   	$data = file_get_contents($tableName. '.json');
								   	$data_array = json_decode($data, true);  
								   	if (count($data_array) != 0) { ?>
								   		<a href="javascript:void(0)" class="nav-link text-dark" onclick="addRow('<?php echo $tableName ?>')"><i class="las la-window-minimize"></i> <span> Add Row </span></a>
								   <?php } } ?>   

								   <!-- Duplicate Row -->
								   <?php if ($_SESSION['username'] == "demo") { ?>
								   	<a href="#" onclick="demoAlert()" class="nav-link text-dark"><i class="las la-copy"></i> <span> Duplicate Row</span></a>
								   <?php } else { ?>
								   	<a href="#" id="duplicateSelectedRowButton" class="nav-link text-dark" onclick="duplicateRow('<?php echo $tableName ?>')"><i class="las la-copy"></i> <span> Duplicate Row</span></a>
								   <?php } ?>

								   <!-- Delete Row(s) -->
								   <?php if ($_SESSION['username'] == "demo") { ?>
								   	<a href="#" onclick="demoAlert()" id="deleteSelectedRowsButton" class="nav-link text-dark"><i class="las la-trash-alt"></i>  <span> Delete Row(s) </span></a>
								   <?php } else { ?>
								   	<a href="#" id="deleteSelectedRowsButton" class="nav-link text-dark" onclick="deleteSelectedRows('<?php echo $tableName ?>')"><i class="las la-trash-alt"></i>  <span> Delete Row(s) </span></a>
								   <?php } ?>

		                     <div class="separator"></div>

		                     <!-- Add Column -->
		                     <?php if ($_SESSION['username'] == "demo") { ?>
		                     	<a href="#" onclick="demoAlert()" class="nav-link text-dark"><i class="las la-columns"></i>  <span> Add Column </span></a>
		                     <?php } else { ?>
		                     	<a href="#" data-toggle="modal" data-target="#addColumnModal" class="nav-link text-dark"><i class="las la-columns"></i>  <span> Add Column </span></a>
		                     <?php } ?>

		                     <!-- Rename a column -->
								   <?php if ($_SESSION['username'] == "demo") { ?>
								   	<a href="#" onclick="demoAlert()" class="nav-link text-dark"><i class="las la-pen-alt"></i>  <span> Rename a Column </span></a>
								   <?php } else { ?>
								   	<a href="#" data-toggle="modal" data-target="#renameColumnModal" class="nav-link text-dark"><i class="las la-pen-alt"></i>  <span> Rename a Column </span></a>
								   <?php } ?>

								   <!-- Delete Column -->
		                     <?php if ($_SESSION['username'] == "demo") { ?>
		                     	<a href="#" onclick="demoAlert()" class="nav-link text-dark"><i class="las la-trash-alt"></i> <span> Delete a Column </span></a>
		                     <?php } else { ?>
		                     	<a href="#" data-toggle="modal" data-target="#deleteColumnModal" class="nav-link text-dark"><i class="las la-trash-alt"></i>  <span> Delete a Column </span></a>
		                     <?php } ?>

		                     <div class="separator"></div>

		                     <!-- Search filters -->
		                     <?php if (count($data_array) >= 2) { ?>
		                     	<a href="#" data-toggle="modal" data-target="#searchModal" class="nav-link text-dark"><i class="las la-search"></i> <span> Search filters</span></a>
		                     <?php } ?>

		                     <!-- Order by -->
		                     <a href="#" data-toggle="modal" data-target="#orderByModal" class="nav-link text-dark"><i class="las la-sort"></i> <span> Sort Data </span></a>

		                     <!-- Order columns -->
		                     <?php if (count($data_array) != 0) { ?>
		                     	<a href="javascript:void(0)" data-toggle="modal" data-target="#orderColumnsModal" class="nav-link text-dark"><i class="las la-arrows-alt-h"></i> <span> Order Columns </span></a>
		                 	<?php } ?>
								
		                  </li>
	                        
	                     <!-- Refresh -->
	                     <li class="nav-item dropdown connection">
	                     	<a href="<?php echo  htmlspecialchars($TABLES_PATH.$tableName) ?>" class="nav-link text-dark"> <i class="las la-redo-alt"></i>  Refresh </a>
	                     </li>

	                     <!-- Logout -->
	                     <li class="nav-item dropdown nav-user">
	                     	<a href="../index.php" class="nav-link text-danger"> <i class="las la-sign-out-alt"></i>  Sign out </a>
	                     </li>

	                  </ul>
	               </div>
	            </nav>
	        </div><!-- ./ Navbar -->
	        
	        <!-- Left sidebar -->
	        <div class="nav-left-sidebar sidebar-dark">
	            <div class="menu-list">
	                <nav class="navbar navbar-expand-lg navbar-light">
	                    <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
	                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"> <i class="las la-bars"></i> </span></button>
	                    <div class="collapse navbar-collapse" id="navbarNav">
	                        <ul class="navbar-nav flex-column">

	                        	<!-- Tables -->
	                           <li class="nav-divider"> Tables </li>
	                           <?php $dir = htmlspecialchars($DATABASE_PATH). "_Tables/";
	                           $jsonFiles = glob('*.json');
	                           for ($i=0; $i<count($jsonFiles); $i++) {
	                           $jFile = str_replace( '.json', '', $jsonFiles[$i] ); ?>

		                           <li class="nav-item ">
		                           	<a <?php if($jFile == $tableName){ ?> class="nav-link active" <?php } else { ?> class="nav-link" <?php } ?> href="<?php echo $TABLES_PATH.$jFile ?>"><i class="las la-cube"></i> <?php echo $jFile ?>
		                           	<span class="tables-count">
		                           		<?php 
		                           			$data = file_get_contents($jFile. '.json');
		                           			$data_array = json_decode($data, true);
		                           			$count = count($data_array);
		                           			foreach($data_array as $row){
		                           				if ($row["ID_id"] == "---") { $count = $count-1; }
		                           			} //./ For
		                           			echo $count;
		                           		?>
		                           	</span>
		                           	</a>
		                        	</li>
	                           <?php } //./ For ?>

	                           <!-- Push Notiifcations -->
	                           <li class="nav-divider">Push Notifications </li>
	                           <li class="nav-item">
	                           	<a href="#" data-toggle="modal" data-target="#pushModal" class="nav-link"><i class="las la-paper-plane"></i>  Send Push Notification</a>
	                           </li>


	                        </ul>
	                    </div>
	                </nav>
	            </div>
	        </div><!-- ./ Left sidebar -->

    	</div><!-- ./ top-navbar -->