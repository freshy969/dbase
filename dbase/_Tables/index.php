<?php include '../header.php'; 
$tableName = $_GET['tableName'];

// HTML sanitization
if (strpos($tableName, '<') !== false || strpos($tableName, '>') !== false
){ $tableName = preg_replace("/[^a-zA-Z]/", "", $tableName); }

//fetch data from json
$data = file_get_contents($tableName. '.json');
$data_array = json_decode($data, true);     


// [ SEARCH FILTERS ] -----------------------------------------
$results = array();
$forCount = -1;

// [ ORDER BY KEY AND ASCENDING/DESCENDING ]
if (isset($_GET['key'])) {
   if ($_GET['orderBy'] != ""){
      // Ascending
      if ($_GET['orderBy'] == "ascending") {
         usort($data_array, function  ($item1, $item2) use ($key)  {
            return $item1[$_GET['key']] <=> $item2[$_GET['key']];
         });
      // Descending
      } else if ($_GET['orderBy'] == "descending") {
         usort($data_array, function  ($item1, $item2) use ($key)  {
            return $item2[$_GET['key']] <=> $item1[$_GET['key']];
         });
      }
   // Descending (default)
   } else {
      usort($data_array, function  ($item1, $item2) use ($key)  {
         return $item2[$_GET['key']] <=> $item1[$_GET['key']];
      });
   }

// Sort createdAt date by Descending (default)
} else {
   usort($data_array, function  ($item1, $item2)  {
      return $item2['DT_createdAt'] <=> $item1['DT_createdAt'];
   });
}

// [ FOREACH FOR FILTERS ]
foreach ($data_array as $obj) {
   foreach($obj as $k=>$v){
      $keysArr = explode("_", $k);
      $kType = $keysArr[0];
      $kName = $keysArr[1];

      if(isset($_GET[$k])){
         
         // [Array or GPS column type]
         if($kType == 'AR' || $kType == 'GPS'){
            foreach ($_GET[$k] as $key2=>$value) {
               $up = implode(",", $obj[$k]);

               if($_GET['condition'] == 'contains'){
                  $up = implode(",", $obj[$k]);
                  if (strpos($up, $value) !== false) { array_push($results, $obj); $data_array = $results; } 
               } else {
                  if ($up == $value) { array_push($results, $obj); $data_array = $results; } 
               }
               
               $forCount++;
               if ($forCount == count($data_array)-1) {
                  // No search results
                  if (count($results) == 0){ $data_array = array(); ?>
                     <script> 
                        Swal.fire({
                           title: 'Oops..',
                           text: "No results for this search.",
                           icon: 'warning',
                           showCancelButton: false,
                           confirmButtonText: 'Refresh',
                           allowOutsideClick: false
                        }).then((result) => {
                           if (result.value) {
                              document.location.href = 'index.php?tableName=' + '<?php echo $tableName ?>';
                           }// ./ If
                        });
                     </script>
                  <?php }
               } //./ If

            } //./ foreach


         // [Other Column types]
         } else {
            foreach ($_GET[$k] as $key2=>$value) {
               if($_GET['condition'] == 'contains'){
                  if (strpos($obj[$k], $value) !== false) { array_push($results, $obj); $data_array = $results; }

               } else if($_GET['condition'] == 'equalTo'){
                  if ($obj[$k] == $value) { array_push($results, $obj); $data_array = $results; }

               } else if($_GET['condition'] == 'notEqualTo'){
                  if ($obj[$k] == $value) { 
                     $index = array_search($obj, $data_array);
                     unset($data_array[$index]);
                     $data_array = array_values($data_array);     
                  }
               }

               $forCount++;
               if ($forCount == count($data_array)-1) {
                  // No search results
                  if (count($results) == 0){ $data_array = array(); ?>
                     <script> 
                        Swal.fire({
                           title: 'Oops..',
                           text: "No results for this search.",
                           icon: 'warning',
                           showCancelButton: false,
                           confirmButtonText: 'Refresh',
                           allowOutsideClick: false
                        }).then((result) => {
                           if (result.value) {
                              document.location.href = 'index.php?tableName=' + '<?php echo $tableName ?>';
                           }// ./ If
                        });
                     </script>
                  <?php }
               } //./ If

            } // ./ foreach

         } // ./ If 

      } // ./ If isset($_GET[$k]
   } // ./ foreach
} // ./ [ FOREACH FOR FILTERS ]
// ./ [ SEARCH FILTERS ] -----------------------------------------------
?>

<!-- wrapper  -->
<div class="dashboard-wrapper">
   <div class="container-fluid  dashboard-content">
	 
        <!-- pageheader -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title"><?php echo $tableName ?></h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                <?php 
                                    $count = count($data_array);
                                    foreach($data_array as $row){
                                        if ($row["ID_id"] == "---") { $count = $count-1; }
                                    }
                                    echo $count;
                                ?> 
                                objects
                                </li>
                            </ol>
                        </nav>
                        
                        <!-- Quick Buttons -->
                        <div class="quick-buttons">
                        	<ol class="breadcrumb">
                                <li class="quick-btn">
			                        <!-- Add Row -->
			                        <?php if ($_SESSION['username'] == "demo") { ?>
			                        	<a  href="#" onclick="demoAlert()" class="nav-link text-dark"><i class="las la-window-minimize"></i> <span> Add Row </span></a>
			                        <?php } else { 
                                    if (count($data_array) != 0) { ?>
			                        	  <a href="javascript:void(0)" class="nav-link text-dark" onclick="addRow('<?php echo $tableName ?>')"><i class="las la-window-minimize"></i> <span> Add Row </span></a>
			                        <?php } } ?>
	                        	</li>
	                        	<li class="quick-btn">
			                        <!-- Add Column -->
			                        <?php if ($_SESSION['username'] == "demo") { ?>
			                        	<a href="#" onclick="demoAlert()" class="nav-link text-dark"><i class="las la-columns"></i> <span> Add Column </span></a>
			                        <?php } else { ?>
			                        	<a href="#" data-toggle="modal" data-target="#addColumnModal" class="nav-link text-dark"><i class="las la-columns"></i> <span> Add Column </span></a>
			                        <?php } ?>
		                    	</li>

		                    	<li class="quick-btn">
		                    		<!-- Delete Row(s) -->
                              <?php if ($_SESSION['username'] == "demo") { ?>
                                 <a href="#" onclick="demoAlert()" id="deleteSelectedRowsButton2" class="nav-link text-danger"><i class="las la-trash"></i> <span> Delete Row(s) </span></a>
                              <?php } else { ?>
		                                <a href="#" id="deleteSelectedRowsButton2" class="nav-link text-danger" onclick="deleteSelectedRows('<?php echo $tableName ?>')"><i class="las la-trash"></i> <span> Delete Row(s) </span></a>
		                            <?php } ?>
		                        </li>

                              <li class="quick-btn">
                                 <!-- Delete Row(s) -->
                                 <?php if ($_SESSION['username'] == "demo") { ?>
                                    <a href="#" id="duplicateSelectedRowButton2" class="nav-link text-dark" onclick="demoAlert()"><i class="las la-copy"></i> <span> Duplicate Row</span></a>
                                 <?php } else { ?>
                                    <a href="#" id="duplicateSelectedRowButton2" class="nav-link text-dark" onclick="duplicateRow('<?php echo $tableName ?>')"><i class="las la-copy"></i> <span> Duplicate Row</span></a>
                                 <?php } ?>
                              </li>
	                    </div>

                    </div>
                </div>
            </div>
        </div><!-- end pageheader -->

        <!-- table data -->
        <div class="row">        
            <div class="col-md-12 col-12">
                <div class="card">
                	<p class="table-info-txt">Double-click on a cell to edit it.</p>
                    

                    <table class="table table-bordered table-responsive table-striped table-light table-hover">
                        <thead>
                            <tr>
                                <!-- Select All Rows checkbox -->
                                <th class="top-cell"><input type="checkbox" class="form-check-input first-select-checkbox" id="selectAllRows"></th>

                                <?php // [TABLE HEADER ] ----------------------------- 
                                    foreach($data_array[0] as $k => $v) {
                                        $keysArr = explode("_", $k);
                                        $keyType = $keysArr[0];
                                        $keyName = $keysArr[1]; 
                                        switch ($keyType) {
                                        	case 'ID':  $type = 'String'; break;
                                        	case 'ST':  $type = 'String'; break;
                                        	case 'NU':  $type = 'Number'; break;
                                        	case 'AR':  $type = 'Array'; break;
                                        	case 'FL':  $type = 'File'; break;
                                        	case 'BL':  $type = 'Boolean'; break;
                                        	case 'GPS': $type = 'GPS'; break;
                                        	case 'PO':  $type = 'Pointer -> '.$keysArr[2]; break;
                                        	case 'DT':  $type = 'Date'; break;
                                        	default: break;
                                        }
                                        ?>
                                        <th>
                                            <p style="text-align: center; font-size: 14px; line-height: 16px;"> <?php echo htmlspecialchars($keyName) ?>
                                            <br>
                                            <span style="font-size: 11px; font-weight: 400; text-align: center;">[<?php echo htmlspecialchars($k); ?>]</span>
                                            <br>
                                            <span style="font-size: 10px; font-weight: 400; text-align: center;"><?php echo htmlspecialchars($type); ?></span>
                                        </th>
                                <?php } //./ foreach ?>
                            </tr>
                        </thead>

                        

                        <tbody>
                            <tr>
                            <?php // [ TABLE DATA ] ------------------------------------
                            $noRow = false;
                            foreach($data_array as $row){
                                foreach($row as $k=>$v){
                                    $keysArr = explode("_", $k);
                                    $key = $keysArr[0];
                                    $keyName = $keysArr[1];
                                    if (isset($keysArr[2])) { $pointerTable = $keysArr[2]; }
                                        
                                    // Hide a default row with ID = '---'
                                    if ($row["ID_id"] == "---") { $noRow = true; } else { $noRow = false; }
                                    if (!$noRow) { ?>

                                       <?php if($k == 'ID_id'){
                                            $rowID = $v; ?>
                                            
                                            <td><input type="checkbox" class="form-check-input select-checkbox" id="<?php echo $rowID ?>"></td>

                                            <td id="<?php echo $rowID .'~~~~'. $k .'~~~~'. $v ?>">
                                                <?php echo $rowID ?>
                                            </td>
                                        <?php }

                                        if($key == 'ST'){ 
                                            // hide password
                                            if ($k == 'ST_password') { ?>
                                                <td id="<?php echo $rowID .'~~~~'. $k .'~~~~'. $v ?>">••••</td>
                                                
                                            <?php } else {  ?>
                                                <td id="<?php echo $rowID .'~~~~'. $k .'~~~~'. htmlspecialchars($v) ?>"><?php echo htmlspecialchars($v) ?></td>
                                            <?php }
                                        }

                                        if($key == 'NU'){ ?>
                                            <td id="<?php echo $rowID .'~~~~'. $k .'~~~~'. $v ?>">
                                                <?php echo htmlspecialchars($v); ?>
                                            </td>
                                        <?php }
                                    
                                       if($key == 'DT'){ ?>
                                            <td id="<?php echo $rowID .'~~~~'. $k .'~~~~'. $v ?>">
                                                <?php echo date("M d Y ~ h:i:s A", strtotime( htmlspecialchars($v) )); ?>
                                            </td>
                                        <?php }
                                        
                                        if($key == 'GPS'){
                                            $coords = str_replace(str_split('[]"'), '', json_encode($v));
                                            if ($coords != "") { ?>
                                                <td id="<?php echo $rowID .'~~~~'. $k .'~~~~'. $coords ?>"><?php echo str_replace(",", " | ", $coords);
                                            } else { ?>
                                                <td id="<?php echo $rowID .'~~~~'. $k .'~~~~'. $coords ?>" class="link-btn"><i class="fas fa-ban"></i></td>
                                            <?php } ?>
                                            </td>
                                        <?php }
                                        
                                        if($key == 'AR'){ ?>
                                          <?php $arrStr = str_replace(str_split('[]"'), '', json_encode($v)); ?>
                                             <td id="<?php echo $rowID .'~~~~'. $k .'~~~~'. $arrStr ?>">
                                          <?php echo json_encode( $v ); ?>  
                                            </td>
                                        <?php }
                                        
                                        if($key == 'BL'){ ?>
                                            <td id="<?php echo $rowID .'~~~~'. $k .'~~~~'. $v ?>">
                                                <?php if($v == 1) { echo "true"; } else { echo "false"; } ?>
                                            </td>
                                        <?php }
                                        
                                        if($key == 'PO'){ ?>
                                            <?php if($v != "") { ?>
                                            <td id="<?php echo $rowID .'~~~~'. $k .'~~~~'. $v ?>">
                                                <a class="pointer-btn" href="<?php echo $TABLES_PATH.''.$pointerTable. '&ID_id[]='.$v.'&condition=equalTo' ?>"> <?php echo htmlspecialchars($v) ?> </a>
                                            </td>
                                            <?php } else { ?>
                                                <td id="<?php echo $rowID .'~~~~'. $k .'~~~~'. $v ?>" class="link-btn"><i class="fas fa-ban"></i></td>
                                            <?php } ?>
                                        <?php }
                                    
                                       if($key == 'FL'){ ?>
                                          <?php if($v != "") { ?>
                                             <td id="<?php echo $rowID .'~~~~'. $k .'~~~~'. $v ?>" class="link-btn">

                                                   <?php // Image file 
                                                      if( strpos($v, '.jpg') || strpos($v, '.png') 
                                                      ){ ?> <i class="far fa-image link-btn"></i>

                                                   <?php } // GIF file 
                                                      else if( strpos($v, '.gif') 
                                                      ){ ?> <i class="far fa-grin-alt link-btn"></i>
																		  
                                                   <?php } // Google Profile photo
                                                      else if (strpos($v, 'lh3.googleusercontent.com') 
                                                      ){ ?><i class="fab fa-google link-btn"></i>
                                                      
                                                   <?php } // Facebook Profile photo
                                                      else if (strpos($v, 'http://graph.facebook.com')
                                                      ){ ?> <i class="fab fa-facebook-f link-btn"></i>
																			
                                                   <?php } // PDF files 
                                                      else if (strpos($v, '.pdf') 
                                                      ){ ?> <i class="fas fa-file-pdf link-btn"></i>
																	
                                                   <?php } // Audio or Video files 
                                                      else if (strpos($v, '.mp3') || strpos($v, '.mp4') || strpos($v, '.m4a') || strpos($v, '.wav') || strpos($v, '.aac') || strpos($v, '.ogg') || strpos($v, '.wma') || strpos($v, '.3gp') || strpos($v, '.mp4') || strpos($v, '.mov') || strpos($v, '.avi') || strpos($v, '.flv')
                                                      ){ ?> <i class="fas fa-play-circle link-btn"></i>

                                                   <?php } else { ?>
                                                      <i class="far fa-file link-btn"></i>
                                                   <?php } ?> 
                                            <?php } else { ?>
                                                    <td id="<?php echo $rowID .'~~~~'. $k .'~~~~'. $v ?>" class="link-btn"><i class="fas fa-ban"></i></td>
                                            <?php } ?>
                                            </td>
                                       <?php } 

                                    } //./ If noRow == false ?>

                                <?php } //./ foreach2 ?>
                            </tr>

                            <?php } // ./ foreach1
                            // ./ [ TABLE DATA ] -----------------------------
                            ?>
                        </tbody>
                    </table>


         </div><!-- ./ card -->
      </div><!-- end bordered table -->

   </div><!-- ./ container -->


<!------------------------------
   Modals
-------------------------------->
    
<!-- searchModal -->
<div id="searchModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Search Filters</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label class="badge badge-dark">Column</label><br>
                        <select id="sKey">
                            <?php foreach($data_array[0] as $k => $v) {
                                $keysArr = explode("_", $k); ?>
                                <option value="<?php echo $k ?>"><?php echo $k ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label class="badge badge-dark">Condition</label><br>
                        <select id="sCondition">
                            <option value="equalTo">equal to</option>
                            <option value="notEqualTo">not equal to</option>
                            <option value="contains">contains</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="badge badge-dark">Value</label><br>
                        <input class="form-control" type="text" id="filterValue" name="filterValue" placeholder="Type a value" autocomplete="off">
                    </div>
                </div><!-- row -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="applyQueryFilters('<?php echo $tableName ?>')">Apply Filters</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="las la-times-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div><!-- ./ searchModal -->


<!-- addColumnModal -->
<div id="addColumnModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Add a column</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        
                        <label class="badge badge-dark">Type</label><br>
                        <select id="columnType">
                            <option value="string">String</option>
                            <option value="number">Number</option>
                            <option value="array">Array</option>
                            <option value="file">File</option>
                            <option value="boolean">Boolean</option>
                            <option value="gps">GPS</option>
                            <option value="pointer">Pointer</option>
                            <option value="date">Date</option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label class="badge badge-dark">Column name</label><br>
                        <input class="form-control" type="text" id="columnName" name="columnName" placeholder="Type a name" autocomplete="off">
                    </div>

                    <!-- for Pointer column -->
                    <div class="col-md-8 offset-md-4">
                        <label id="pointerTableLabel" class="badge badge-dark">Pointer Table</label><br>
                        <select id="pointerTable">
                            <?php $dir = htmlspecialchars($DATABASE_PATH). "_Tables/";
                                $jsonFiles = glob('*.json');
                                for ($i=0; $i<count($jsonFiles); $i++) {
                                    $jFile = str_replace( '.json', '', $jsonFiles[$i] ); ?>
                                    <option value="<?php echo $jFile ?>"><?php echo $jFile ?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name="isPointerTable" id="isPointerTable" value="no">
                    </div>

                </div><!-- row -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addColumn('<?php echo $tableName ?>')">Add column</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="las la-times-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div><!-- ./ addColumnModal -->


<!-- renameColumnModal -->
<div id="renameColumnModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Rename a Column</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        
                        <label class="badge badge-dark">Select the Column you want to rename</label><br>
                        <select id="colToRename">
                            <?php 
                            		foreach($data_array[0] as $k => $v) {
                                    $keysArr = explode("_", $k);
                                    $keyType = $keysArr[0]; 
                                    $colName = $keysArr[1]; 
                                    if(    $k != 'ID_id' 
                                        && $k != 'DT_createdAt' 
                                        && $k != 'DT_updatedAt' 
                                        && $k != 'ST_username' 
                                        && $k != 'ST_email' 
                                        && $k != 'ST_password' 
                                        && $k != 'ST_iosDeviceToken' 
                                        && $k != 'ST_androidDeviceToken' 
                                        && $k != 'NU_badge'
													 && $k != 'BL_emailVerified'
													 && $k != 'ST_signInWith'
												){ ?>
												<option value="<?php echo $k ?>"><?php echo $colName ?></option>
											<?php } ?>
                            <?php } ?>
                        </select>
                        <br><br>

                        <label class="badge badge-dark">Type a new Column name</label><br>
                        <div style="font-size: 12px;"> No spaces allowed - Ex: columnName</div>
                        <input class="form-control" type="text" id="newColName" name="newColName" placeholder="Type a name with no spaces" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="renameColumn()">Rename Column</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="las la-times-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div><!-- ./ renameColumnModal -->


<!-- deleteColumnModal -->
<div id="deleteColumnModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><strong>Delete a column</strong></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                        
                            <label class="badge badge-dark">Select a column</label><br>
                            <select id="colName">
                                <?php foreach($data_array[0] as $k => $v) {
                                    $keysArr = explode("_", $k);
                                    // $colName = $keysArr[1]; 
                                    if(    $k != 'ID_id' 
                                        && $k != 'DT_createdAt' 
                                        && $k != 'DT_updatedAt' 
                                        && $k != 'ST_username' 
                                        && $k != 'ST_email' 
                                        && $k != 'ST_password' 
                                        && $k != 'ST_iosDeviceToken' 
                                        && $k != 'ST_androidDeviceToken' 
                                        && $k != 'NU_badge'
													 && $k != 'BL_emailVerified'
													 && $k != 'ST_signInWith'
									){ ?>
                                        <option value="<?php echo $k ?>"><?php echo $k ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div><!-- row -->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteColumn('<?php echo $tableName ?>')">Delete column</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="las la-times-circle"></i> Close</button>
                </div>
            </div>
    </div>
</div><!-- ./ deleteColumnModal -->


<!-- orderByModal -->
<div id="orderByModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Order By</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">

                        <!-- select a column -->
                        <label class="badge badge-dark">Select a column</label><br>
                        <select id="oKey">
                            <?php foreach($data_array[0] as $k => $v) { ?>
                                <option value="<?php echo $k ?>"><?php echo $k ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <!-- Order by -->
                    <div class="col-md-6">
                        <label class="badge badge-dark">Order By</label><br>
                        <select id="oOrderBy">
                            <option value="ascending">Ascending</option>
                            <option value="descending">Descending</option>
                        </select>
                    </div>

                </div><!-- row -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="orderData('<?php echo $tableName ?>')">Order data</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="las la-times-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div><!-- ./ orderByModal -->


<!-- addTableModal -->
<div id="addTableModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Add Table</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12">
                        <label class="badge badge-dark">Table name</label><br>
                        <input class="form-control" type="text" id="aTableName" name="aTableName" placeholder="Type a name for your Table" autocomplete="off">
                    </div>

                </div><!-- row -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addTable()"> Add Table</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="las la-times-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div><!-- ./ addTableModal -->



<!-- renameTableModal -->
<div id="renameTableModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Rename a Table</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        
                        <label class="badge badge-dark">Select the Table you want to rename</label><br>
                        <select id="tableToRename">
                            <?php $dir = htmlspecialchars($DATABASE_PATH). "_Tables/";
                                $jsonFiles = glob('*.json');
                                for ($i=0; $i<count($jsonFiles); $i++) {
                                    $jFile = str_replace( '.json', '', $jsonFiles[$i] ); 
                                    if ($jFile != 'Users') { ?>
                                        <option value="<?php echo $jFile ?>"><?php echo $jFile ?></option>
                                    <?php }
                                } ?>
                        </select>
                        <br><br>

                        <label class="badge badge-dark">Type a new Table name</label><br>
                        <div style="font-size: 12px;"> No spaces allowed - Ex: NewName</div>
                        <input class="form-control" type="text" id="newTableName" name="newTableName" placeholder="Type a name with no spaces" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="renameTable()">Rename Table</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="las la-times-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div><!-- ./ renameTableModal -->



<!-- DeleteTableModal -->
<div id="deleteTableModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Delete a Table</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        
                        <label class="badge badge-dark">Select a Table</label><br>
                        <select id="selTableName">
                            <?php $dir = htmlspecialchars($DATABASE_PATH). "_Tables/";
                                $jsonFiles = glob('*.json');
                                for ($i=0; $i<count($jsonFiles); $i++) {
                                    $jFile = str_replace( '.json', '', $jsonFiles[$i] ); 
                                    if ($jFile != 'Users') { ?>
                                        <option value="<?php echo $jFile ?>"><?php echo $jFile ?></option>
                                    <?php }
                                } ?>
                        </select>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteTable()">Delete Table</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="las la-times-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div><!-- ./ DeleteTableModal -->


<!-- InfoModal -->
<div id="infoModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Info</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>
                        	<strong>Edit a cell:</strong> Double click on a cell and edit data
                        </p>
                        <p>
                        	<strong>Delete a row: </strong> Select one or more row's checkboxes <i class="far fa-check-square"></i> | (<i class="fas fa-cog"></i> Options) | (<i class="fas fa-minus-circle"></i> Delete row(s))
                        </p>

                       
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="las la-times-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div><!-- ./ InfoModal -->


<!-- PushModal -->
<div id="pushModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Info</strong></h4>
            </div>
            <div class="modal-body">
                
               <!-- audience -->
               <div class="form-group">
                  <label class="badge badge-dark">Select audience</label>
                  <select class="form-control" id="audience">
                     <option>All</option>
                     <option>iOS</option>
                     <option>Android</option>
                  </select>
                  <input class="form-control" type="hidden" id="audienceInput" name="audienceInput">
               </div>

               <!-- push message --> 
               <div class="form-group"><label class="badge badge-dark">Message</label>
                  <textarea class="form-control" id="message" name="message" placeholder="Type some text" rows="3"></textarea>
               </div>
               
               <!-- push type --> 
               <div class="form-group"><label class="badge badge-dark">Push Type(optional)</label>
                  <input class="form-control" id="pushType" name="pushType" placeholder="Ex: chat">
               </div>


            </div>
            <div class="modal-footer">
               <button class="btn btn-info save-update-button" onclick="sendPushViaWeb()"><i class="las la-paper-plane"></i> Send Push Notification </button>
               <button type="button" class="btn btn-default" data-dismiss="modal"><i class="las la-times-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div><!-- ./ PushModal -->
        
</div><!-- ! wrapper -->
</div><!-- end main wrapper -->
<?php include '../footer.php' ?>
<script>
   $(function () {
      "use-strict";

      // show/hide pointerTable select
      $("select#columnType").change(function () {
         // show Pointer Selection
         var columnType = $('#columnType').find(":selected").val();
         if (columnType == 'pointer') {
            $("#pointerTable").css("display", "block");
            $("#pointerTableLabel").css("display", "inline-block");
            $("#isPointerTable").val('yes');
         } else {
            $("#pointerTable").css("display", "none");
            $("#pointerTableLabel").css("display", "none");
            $("#isPointerTable").val('no');
         }
      });


      // Set Audience for Push Notifications
      setAudience('All');
      $("select#audience").change(function () {
         var selAudience = $('#audience').find(":selected").val();;
         setAudience(selAudience);
      });

   }); // ./ function()
</script>