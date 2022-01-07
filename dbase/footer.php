<script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/jquery/jquery-3.4.1.min.js"></script>
<script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/slimscroll/jquery.slimscroll.js"></script>
<script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/js/main-js.js"></script>
<script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/js/moment.min.js"></script>
<script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/lity/dist/lity.js"></script>
<script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/js/jquery-ui.js"></script>
<script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>bkgjobs.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.js"></script>
<script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/js/jquery-ui-touch.js"></script>
<script src="<?php echo htmlspecialchars($DATABASE_PATH) ?>assets/vendor/dropzone/dist/dropzone.js"></script>

<!-- OrderColumnsModal -->
<div id="orderColumnsModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Order Columns</strong></h4>
            </div>
            <div class="modal-body">

            	<ul class="ui-sortable" id="sortableColumns">
            		<?php 
            			foreach($data_array[0] as $k2 => $v2) { 
	            			if ($k2 == 'ID_id') { ?>
	            				<li style="display: none;" class="ui-sortable-handle" sort-id="<?php echo $k2 ?>"><?php echo $k2 ?><i class="fas fa-grip-lines"></i></li>
	            			<?php } else { ?>
	            				<li class="ui-sortable-handle" sort-id="<?php echo $k2 ?>"><?php echo $k2 ?><i class="fas fa-grip-lines"></i></li>
	            			<?php }
            			}
            		?>
                </ul>
        	
               <script>
               	var newOrderArray = [];

               	$(function () {
              			
		              	$('#sortableColumns').sortable({ update: function(event, ui) {
		              		newOrderArray = $(this).find('li').map(function(i, el) {
		              			return $(el).attr('sort-id');
		              		}).get()
		              	}});
		            });


		            function reorderTableData() {
		              	if(newOrderArray.length != 0){
		              		var data_arrayStr = JSON.stringify(<?php echo json_encode($data_array) ?>);
		              		var jsonData = JSON.parse(data_arrayStr);
								var reorderedData = JSON.stringify(jsonData, newOrderArray);

								$.ajax({
									url : "reorder-table-data.php",
									type: 'POST',
									data: 'tableName='+'<?php echo $tableName ?>'+'&reorderedData='+encodeURIComponent(reorderedData),
									success: function(data) {
										location.reload();

									// error
									}, error: function(e) {  
									  Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong: ' + e.message, });
								}});

							} else {
								$('#orderColumnsModal').modal('hide');
							} // If
		            }

               </script>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="reorderTableData()"><i class="las la-check"></i> Apply</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="las la-times-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div><!-- ./ Order Columns Modal -->


<script>
	"use strict"; 
	
	/* GLOBAL VARIABLES */
	var rowIDs = [];

	// ON page load
	$(function () {

		//---------------------------------
		// DATE PICKER
		//---------------------------------
		if ('<?php echo $datePicker ?>' != '') {
			var dp = '<?php echo $datePicker ?>';
			if (dp != "") {
				if($("#<?php echo $datePicker ?>").length != 0){
			        $('#<?php echo $datePicker ?>').datetimepicker({
			            icons: {
			                time: "fa fa-clock-o",
			                date: "fa fa-calendar",
			                up: "fa fa-chevron-up",
			                down: "fa fa-chevron-down",
			                previous: 'fa fa-chevron-left',
			                next: 'fa fa-chevron-right',
			                today: 'fa fa-screenshot',
			                clear: 'fa fa-trash',
			                close: 'fa fa-remove'
			            },
			            format: 'YYYY-MM-DDTHH:mm:ss',
			            defaultDate: new Date(),
			            debug: false
			        });
		    	};
	    	}
    	}



    	//---------------------------------
    	// MARK - EDIT CELL BY DOUBLE-CLICK
    	//---------------------------------
    	$('tbody >tr >td').on('dblclick', function() {
    		var ID = $(this).attr("id");
			
			var idArr = ID.split('~~~~');
			var ID_id = idArr[0];
			var keyArr = idArr[1].split('_');
			var key = idArr[1];
			var colType = keyArr[0];
			var colName = keyArr[1];
			var value = idArr[2];

			var html = '';

			// ID_id
			if (key == 'ID_id') {
				var temp = $("<input>");
				$("body").append(temp);
				temp.val(value).select();
				document.execCommand("copy");
				temp.remove();
				Swal.fire({title: value, text: 'This ID has been copied to your clipboard.', icon: 'success' });
				return;
			}

			
			// String
			if (colType == 'ST') {
				if (key == 'ST_password') {
					html = '<input class="form-control" type="password" id="'+key+'" name="'+key+'" value="'+value+'">';
				} else { 
					html = '<textarea class="form-control" id="'+key+'" name="'+key+'" placeholder="Type some text" rows="3">'+value+'</textarea>';
				}
				html += '<p class="swal2-info-p">Type any text</p>';
			}

			// Number
			if (colType == 'NU') {
				html = '<input class="form-control" type="number" step="any" id="'+key+'" name="'+key+'" value="'+value+'">'; 
				html += '<p class="swal2-info-p">Type an integer or float number.<br>Use the dot (.) as decimal separator - EX: 12.45</p>';
			}

			// Array
			if (colType == 'AR') {
				if (value == '[]') { value = ''; }
				html = '<textarea class="form-control" id="'+key+'" name="'+key+'" placeholder="Ex: lorem,ipsum,dolor">'+value+'</textarea><br>'+
						 '<p class="swal2-info-p">Type values separated by comma (,).<br>Ex: lorem,ipsum,dolor</p>'
				; 
			}

			// GPS
			if (colType == 'GPS') {
				var gpsArr = value.split(',');
				var lat = gpsArr[0]; var lng = gpsArr[1];
				if (gpsArr[0] == 0) { lat = ''; }
				if (gpsArr[1] == 0) { lng = ''; }
				html = '<div class="input-group">'+
						 '<input type="number" step="any" id="latitude" name="latitude" class="form-control" placeholder="latitude" value="'+lat+'">'+
						 '<input type="number" step="any" id="longitude" name="longitude" class="form-control" placeholder="longitude" value="'+lng+'"></div>'
				;
				html += '<p class="swal2-info-p">Insert the latitude and longitude coordinates of a location.<br>Use the dot (.) as decimal separator - Ex: 12.4584</p>';
			}

			// Boolean 
			if (colType == 'BL') {
				var checked = '';
				if (value == true || value == '1') { checked = 'checked'; 
				} else if (value == '' || value == '0') { checked = ''; }
				html = '<label class="switch"><input type="checkbox" id="'+key+'" name="'+key+'"'+ checked +'><span class="slider round"></span></label>';
				html += '<p class="swal2-info-p">Set the switch either True or False</p>';
			}


			// File
			if (colType == 'FL') {
				var fileInput = colName;
				var fileURLInput = key;
				var viewButton = key + '_btn';

				html = '<div class="custom-file">'+
					'<form class="dropzone">'+
					'<input type="file" class="custom-file-input" id="'+fileInput+'" onchange="uploadFile(\''+fileInput+'\', \''+fileURLInput+'\', \''+viewButton+'\')">'+
					'</form>'+
					'<div class="custom-file-label2"><i class="las la-upload"></i> Drop a file <br><span style="font-size: 12px;">[or click here to upload]</span></div>'+
					'<!-- hidden input -->'+
					'<input class="form-control" type="hidden" id="'+fileURLInput+'" name="'+fileURLInput+'" readonly value="'+value+'">'+
					'<br><strong><p id="uploadInfo"></strong></p>'+
					'<div class="row">'+
					'<div class="col-md-6">'+
					'<a class="btn btn-primary btn-block" id="'+viewButton+'" href="'+value+'" data-lity>View File</a>'+
					'</div>'+
					'<div class="col-md-6">'+
					'<a class="btn btn-dark btn-block" href="javascript:void(0)" onclick="removeFileURL(\''+fileURLInput+'\', \''+viewButton+'\', \''+ID_id+'\', \''+key+'\')">Remove File</a><br><br>'+
					'</div></div></div>'
				;
			}


			// Pointer
			if (colType == 'PO') {
				html = '<div class="input-group mb-3">'+
							'<input class="form-control" type="text" id="'+key+'" name="'+key+'" placeholder="Paste an ID_id form the <?php echo $pointerTable ?> table" value="'+value+'">'+
							'<div class="input-group-append">'+
								'<a href="<?php echo $TABLES_PATH.$pointerTable ?>" class="btn btn-primary" target="_blank">See <?php echo $pointerTable ?></a>'+
						'</div></div>';
				html += '<p class="swal2-info-p">Paste an "ID_id" from the <strong><?php echo $pointerTable ?></strong> Table</p>';
			}


			// Date 
			if (colType == 'DT') {
				// DT_createdAt and DT_updatedAt
				if (key == 'DT_createdAt' || key == 'DT_updatedAt') {
					Swal.fire({title: 'Hey', text: 'This Date cannot be edit.', icon: 'warning' });
					return;
				}

				html = '<div class="input-group date">'+
							'<input style="font-size: 14px;" class="flatpickr flatpickr-input form-control datepicker" type="text" id="'+key+'" placeholder="Select Date.." data-id="datetime" value="'+value+'">'+
								'<div class="input-group-append">'+
									'<span class="input-group-text" id="basic-addon2"><i class="las la-calendar"></i></span>'+
						'</div></div>';
				html += '<p class="swal2-info-p">Click the field and choose a date/time from the calendar</p>';
			}


			// Fire Swal
			Swal.fire({
				title: colName,
				html: html,
				confirmButtonText: 'Update',
				showCancelButton: true,

				onOpen: function() {
					// Date
					if (colType == 'DT') {
						$("#"+key).flatpickr({
							enableTime: true,
				    		dateFormat: "Y-m-d\TH:i:ss",
						});
					}},

				}).then((result) => {
					if (result.value) {

						var cuLogin = '<?php echo $_SESSION['username'] ?>';
						if(cuLogin != "demo") {

						var v = $('#'+key+'').val();
						
						if (v == null) { v = ''; }

						if (v.includes('<script')) {
							Swal.fire({ title: 'Ouch!', text: '<script> tag is not allowed!', icon: 'error' });
							return;
						}

						var theTableCell = $(this);

						// [GPS]
						if (colType == 'GPS') {
							var lat = $('#latitude').val(); var lng = $('#longitude').val();
							if (lat == '') { lat = '0'; }
							if (lng == '') { lng = '0'; }
							v = lat + ','+ lng;
						}

						// Boolean
						if (colType == 'BL') {
							if ($('#'+key).prop('checked') == true){ v = '1';
							} else { v = '0'; }
						}
						

						// Update Cell
						$.ajax({
							url : "<?php echo htmlspecialchars($DATABASE_PATH).'_Tables/m-add-edit.php?' ?>",
							type: 'POST',
							data: 'tableName=<?php echo $tableName ?>&ID_id=' +ID_id+ '&'+key+'=' + encodeURIComponent(v),
							success: function(data) {
								// console.log(data);
								// Set data in the table cell
								theTableCell.attr('id', ID_id +'~~~~'+ key +'~~~~'+ v);
								theTableCell.html(v);
								
								// [Password]
								if (key == 'ST_password') {
									theTableCell.html('••••');
								}
						
								// [Array]
								if (colType == 'AR') {
									if (v == '') {
										theTableCell.html('[]');
									} else {
										var array = v.split(',');
										var arrText = '[';
										for(var a=0; a<array.length; a++) {
											arrText += '"' + array[a] + '",';
											if (a == array.length-1) { 
												arrText = arrText.slice(0, arrText.length-1);
												arrText += ']';
											}
										}
										theTableCell.html(arrText);
									}
								}


								// [GPS]
								if (colType == 'GPS') {
									// console.log('gps: ' + v);
									var gpsArr2 = v.split(',');
									theTableCell.html(gpsArr2[0] + ' | ' + gpsArr2[1]);
								}


								// [Boolean]
								if (colType == 'BL') {
									var boolText = '';
									if (v == '1') { boolText = 'true'; } else if (v == '0'){ boolText = 'false'; }
									theTableCell.html(boolText);
								}
								

								// [File]
								if (colType == 'FL') {
									// Image file
									if (v.endsWith('.jpg') || v.endsWith('.png') ){
										theTableCell.html('<i class="far fa-image link-btn"></i>');

									} else if (v.endsWith('.gif')) {
										theTableCell.html('<i class="far fa-grin-alt link-btn"></i>');

									// Audio or Video file
									} else if (v.endsWith('.mp4') || v.endsWith('.mp3') || v.endsWith('.mp4') || v.endsWith('.m4a') || v.endsWith('.wav') || v.endsWith('.aac') || v.endsWith('.ogg') || v.endsWith('.wma') || v.endsWith('.3gp') || v.endsWith('.mp4') || v.endsWith('.mov') || v.endsWith('.avi') || v.endsWith('.flv') ) {
										theTableCell.html('<i class="fas fa-play-circle link-btn"></i>');
									
									// PDF file 
									} else if (v.endsWith('.pdf')){
										theTableCell.html('<i class="fas fa-file-pdf link-btn"></i>');
									
									// Generic file
									} else {
										theTableCell.html('<i class="far fa-file link-btn"></i>');
									}
								}


								// [Pointer]
								if (colType == 'PO') {
									if (v != '') {
										var pointerTable = '<?php echo $TABLES_PATH.''.$pointerTable ?>';
										theTableCell.html('<a class="pointer-btn" href="'+pointerTable+'&ID_id[]='+v+'&condition=equalTo">'+v+'</a>');
									} else {
										theTableCell.html('<i class="fas fa-ban"></i>');
									}
								}


								// [Date]
								if (colType == 'DT') {
									var d = moment(v).format('MMM DD YYYY ~ hh:mm:ss A');
									theTableCell.html(d);
								}

								// console.log('Updated: '+ ID_id +' ~~~~ '+ key +' ~~~~ '+ v);
							// error
							}, error: function(e) {  
								Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong: ' + e.message, });
						}});

						} else { Swal.fire({title: 'Oops...', text: "Updating functions are disabled in DEMO mode", icon: 'warning' }); }

	  				} // ./ If
			});

    	});



    	
    	//---------------------------------
    	// SELECT ROWS CHECKBOXES
    	//---------------------------------
    	$('input[type="checkbox"]').on('click', function() {
    		var id = $(this).attr('id');

    		// Single rows selection
    		if (id != 'selectAllRows') {
	            if($(this).is(":checked")){	
	                rowIDs.push(id);
	                $('#deleteSelectedRowsButton').css('display', 'inline-block');
	                $('#deleteSelectedRowsButton2').css('display', 'inline-block');
	                $('#editSelectedRowButton').css('display', 'inline-block');

	                $('#duplicateSelectedRowButton').css('display', 'inline-block');
	                $('#duplicateSelectedRowButton2').css('display', 'inline-block');

	                if (rowIDs.length > 1) {
	                	$('#editSelectedRowButton').css('display', 'none');
	                	$('#duplicateSelectedRowButton').css('display', 'none');
	                	$('#duplicateSelectedRowButton2').css('display', 'none');

	                } else if (rowIDs.length == 1) {
	                	$('#duplicateSelectedRowButton').css('display', 'inline-block');
	                	$('#duplicateSelectedRowButton2').css('display', 'inline-block');
	                }

	            } else if($(this).is(":not(:checked)")){
	                rowIDs.splice(rowIDs.indexOf(id), 1);
	                $('#editSelectedRowButton').css('display', 'none');
	                
	                if (rowIDs.length == 1) {
	                	$('#duplicateSelectedRowButton').css('display', 'inline-block');
	                	$('#duplicateSelectedRowButton2').css('display', 'inline-block');
	                }
	            }

	            if (rowIDs.length == 0) {
	            	$('#deleteSelectedRowsButton').css('display', 'none');
	            	$('#deleteSelectedRowsButton2').css('display', 'none');
	            	$('#editSelectedRowButton').css('display', 'none');
	            	$('#selectAllRows').prop("checked", false);

	            	$('#duplicateSelectedRowButton').css('display', 'none');
	            	$('#duplicateSelectedRowButton2').css('display', 'none');
	            }

	        	// Select all rows 
	        	} else {
	        		$('#duplicateSelectedRowButton2').css('display', 'none');

	        		if($(this).is(":checked")){	
	        			rowIDs = [];
	        			$('#deleteSelectedRowsButton').css('display', 'inline-block');
	        			$('#deleteSelectedRowsButton2').css('display', 'inline-block');
	        			$('#editSelectedRowButton').css('display', 'none');

		        		<?php foreach($data_array as $obj) { ?>
		        			var rID = "<?php echo $obj['ID_id'] ?>";
		        			rowIDs.push(rID);
		        			$('#'+rID).prop("checked", true);
		        		<?php } ?>;

		        	// Deselect all rows
		        	} else {
		        		rowIDs = [];
		       		<?php foreach($data_array as $obj) { ?>
		       			var rID = "<?php echo $obj['ID_id'] ?>";
		        			$('#'+rID).prop("checked", false);
		        		<?php } ?>;
		        	}
		        
		        	if (rowIDs.length == 0 || rowIDs[0] == '---') {
		        		$('#deleteSelectedRowsButton').css('display', 'none');
	            	$('#deleteSelectedRowsButton2').css('display', 'none');
	            	$('#editSelectedRowButton').css('display', 'none');
	            	$('#selectAllRows').prop("checked", false);
	            }

	        } // ./ If
        });


    	
    	//---------------------------------
    	// SHOW/HIDE TOP NAVBAR
    	//---------------------------------
    	var path = window.location.pathname;
    	var page = path.split("/").pop();
    	// console.log('Page: ' + page );
    	if (page != 'index.php') {
    		$('#top-navbar').css('display', 'none');
		} else {
			$('#top-navbar').css('display', 'block');
		}
		// Set page title
		if (page == 'send-push.php') {
			document.title = '<?php echo htmlspecialchars($APP_NAME) ?> | Send Push Notifications';
		}
		if (page == 'add-edit.php') {
			document.title = '<?php echo htmlspecialchars($APP_NAME) ?> | Add/Edit row';
		}
		

		//----------------------------------------------------------------
		// CHECK IF BROWSER IS FIREFOX -> SUGGEST TO USE ANOTHER BROWSER
		//----------------------------------------------------------------
		var isFirefox = typeof InstallTrigger !== 'undefined';
		if (isFirefox) { Swal.fire({ icon: 'warning', title: 'Attention', text: 'Please use a different browser, like Chrome, Safari or Edge)', }); }

   });// ./ on page load
   
	

	//-------------------------------------------
	// MARK - ADD ROW
	//-------------------------------------------
	function addRow(tableName) {
		$.ajax({
			url : "<?php echo htmlspecialchars($DATABASE_PATH).'_Tables/m-add-edit.php?' ?>",
			type: 'POST',
			data: 'tableName='+tableName,
			success: function(data) {
				// Reload page
				location.reload();
			// error
			}, error: function(e) {  
				Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong: ' + e.message, });
		}});
	}


	//---------------------------------
	// ORDER DATA
	//---------------------------------
   function orderData(tableName) {
   		var key = $('#oKey').find(":selected").val();
   		var orderBy = $('#oOrderBy').find(":selected").val();
   		document.location.href = 'index.php?tableName=' + tableName + '&key=' + key + '&orderBy=' + orderBy;
	}


	//---------------------------------
	// MARK -  EDIT SELECTED ROW
	//---------------------------------
	function editSelectedRow(tableName) {
		document.location.href = 'add-edit.php?tableName=' + tableName + '&rowID=' + rowIDs[0];	
	}


	//---------------------------------
	// MARK - DELETE SELECTED ROWS
	//---------------------------------
	function deleteSelectedRows(tableName) {
		Swal.fire({
			title: 'Are you sure you want to delete these rows?',
			text: 'Please note that if this is a duplicated row and it contains files form another row, those files will be deleted too.',
			input: 'checkbox',
			inputValue: 0,
			inputPlaceholder:'<strong>CONFIRM ROWS DELETION</strong>',
			showCancelButton: true,
			confirmButtonText:'Delete rows',
			inputValidator: (result) => {
				if (result == 1) {
					document.location.href = 'delete-selected-rows.php?tableName=' + tableName + '&rowIDs=' + rowIDs;
				} else  {
    				return !result && 'You need to check CONFIRM ROWS DELETION';
    			}
  			}
  		});
	}
   	

   	//---------------------------------
	// MARK - 	DELETE A SINGLE ROW
	//---------------------------------
	function deleteRow(rowID, tableName) {
		Swal.fire({
			title: 'Are you sure you want to delete this row?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Delete'
		}).then((result) => {
			if (result.value) {
				document.location.href = 'delete-row.php?tableName=' + tableName + '&rowID=' + rowID;
	  		}// ./ If
		});
	}


	//---------------------------------
	// MARK - 	DUPLICATE A ROW
	//---------------------------------
	function duplicateRow(tableName) {
		document.location.href = 'duplicate-row.php?tableName=' + tableName + '&rowIDs=' + rowIDs;
	}
	

	//---------------------------------
	// MARK - APPLY QUERY FILTERS
	//---------------------------------
	function applyQueryFilters(tableName){
		var queryPath = '<?php echo htmlspecialchars($TABLES_PATH) ?>' + tableName + '&';
		
		var sKey = $('#sKey').find(":selected").val();
		var sCondition = $('#sCondition').find(":selected").val();
		var filterValue = $('#filterValue').val();
		
		// Check for special characters
		var format = /[!@$¥#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
		if(format.test(filterValue)){ 
			Swal.fire({ icon: 'error', title: 'Oops...', text: 'Special characters are not allowed.', });
			return; 
		}

		// Compose filter data
		var fData = sKey + '[]=' + filterValue + '&condition=' + sCondition;
		
		$.ajax({
			url : queryPath,
			success: function(data) {
				// Reload index.php
				window.location.replace(queryPath + fData);
			
			// error
			}, error: function(e) {  
				Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong: ' + e.message, });
		}});
	}



	//----------------------------------------------------
	// MARK - SET AUDIENCE -> FOR WEB SEND PUSH PAGE
	//----------------------------------------------------
	function setAudience(audience){
		console.log('SELECTED AUDIENCE: ' + audience);
		
		$.ajax({
			url : "<?php echo htmlspecialchars($DATABASE_PATH).'_Tables/m-query.php?' ?>",
			type: 'POST',
			data: 'tableName=Users',
			success: function(data) {
				var json = JSON.parse(data);
				var audienceArr = [];

				for(var i=0; i<json.length; i++){
					var obj = json[i];

					// All
					if (audience == "All") { 
						if (obj.ST_iosDeviceToken != "") { audienceArr.push(obj.ST_iosDeviceToken); }
						if (obj.ST_androidDeviceToken != "") { audienceArr.push(obj.ST_androidDeviceToken); }

					// iOS	
					} else  if (audience == "iOS") {
						if (obj.ST_iosDeviceToken != "") { audienceArr.push(obj.ST_iosDeviceToken); }

					// Android
					} else {
						if (obj.ST_androidDeviceToken != "") { audienceArr.push(obj.ST_androidDeviceToken); }
					}
					
            	}// ./ For
				
				console.log('AUDIENCE TOKENS: ' + audienceArr );
				$('#audienceInput').val(audienceArr);

			// error
			}, error: function(xhr, status, error){
				Swal.fire({ icon: 'error', title: 'Oops...', text: JSON.parse(xhr.responseText) });
		}});
	}
	
	
	//----------------------------------------
	// MARK - SEND PUSH NOTIFICATION VIA WEB
	//----------------------------------------
	function sendPushViaWeb() {
		var sessionUsername = '<?php echo $_SESSION['username'] ?>';
		console.log(sessionUsername);
		
		var audience = $('#audience').find(":selected").val();;
		console.log('AUDIENCE: ' + audience);

		var tokensStr = $('#audienceInput').val();
		var tokens = tokensStr.split(",");
		var message = encodeURIComponent($('#message').val());
		var pushType = $('#pushType').val();
		
		if (message.includes('<script>')) {
			Swal.fire({title: 'Oops!',
				text: 'The <script> tag is not allowed, please refresh and correct your data',
				icon: 'error',
				showCancelButton: false,
				confirmButtonText: 'OK'
			});
			return;
		
		} else {
			if(sessionUsername == 'demo') {
				demoAlert();
				
			} else {
				// Send iOS Push
				for(var i = 0 ; i<tokens.length; i++){
					var queryPath = '<?php echo htmlspecialchars($DATABASE_PATH) ?>' + '_Push/send-ios-push.php?';
					$.ajax({
						url : queryPath,
						type: 'POST',
						data: 'deviceToken=' + tokens[i] + '&message=' + message + '&pushType=' + pushType,
						success: function(data) {
							console.log('iOS PUSH: ' + data);

						// error
						}, error: function(e) {  
							Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong.', });
					}});
				}

				// Send Android push
				for(var i = 0 ; i<tokens.length; i++){
					var queryPath = '<?php echo htmlspecialchars($DATABASE_PATH) ?>' + '_Push/send-android-push.php?';
					$.ajax({
						url : queryPath,
						type: 'POST',
						data: 'deviceToken=' + tokens[i] + '&message=' + message + '&pushType=' + pushType,
						success: function(data) {
							console.log('ANDROID PUSH: ' + data);

						// error
						}, error: function(e) {  
							Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong.', });
					}});
				}
				
			}
			
		}//./ If
	}


	//---------------------------------
   // UPLOAD FILE
   //---------------------------------
  	function uploadFile(fileInput, fileURLInput, viewButton) {
  		var cuLogin = '<?php echo $_SESSION['username'] ?>';
  		if(cuLogin != "demo") {
		
	   	var dbPath = '<?php echo htmlspecialchars($DATABASE_PATH) ?>';
			var file = $("#"+fileInput)[0].files[0];
			var fileName = file.name;
			var fileExtension = fileName.slice((fileName.lastIndexOf(".") - 1 >>> 0) + 2);

			if(fileExtension == 'js') {
				Swal.fire({title: 'Oops', text: "You can't upload JavaScript files.", icon: 'error' });
			} else {

				// Show HUD
				$('#hud').css("display", 'flex');

				var data = new FormData();
				data.append('file', file);
				data.append('fileName', fileName);
			
				$.ajax({
					url : dbPath + "upload-file.php?fileName=" + fileName,
					type: 'POST',
					data: data,
					contentType: false,
					processData: false,
					mimeType: "multipart/form-data",
					success: function(data) {
						// Hide HUD
						$('#hud').css("display", 'none');

						var fileURL = dbPath + data;
						console.log('FILE UPLOADED TO: ' + fileURL);

						// error
						if (data.includes("ERROR:")) {
							Swal.fire({ icon: 'error', title: 'Oops...', text: data, });
						// show file data
						} else {
							$('#'+fileURLInput).attr("value", fileURL);
							
							$('#uploadInfo').html('File uploaded.');

							// view file button
							$('#'+viewButton).attr("href", fileURL);
						}
					// error
					}, error: function(e) {  
						Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong: ' + e.message, });
				}});
			}

		} else { Swal.fire({title: 'Oops...', text: "File upload is disabled in DEMO mode", icon: 'warning' }); }
	}


	//---------------------------------
	// REMOVE FILE URL
	//---------------------------------
	function removeFileURL(fileURLInput, viewButton, rowID, columnName) {
		var cuLogin = '<?php echo $_SESSION['username'] ?>';
  		if(cuLogin != "demo") {
			var fileURLVal = $('#'+fileURLInput).val();
			var fileArr = fileURLVal.split('/');
			var fileURL = fileArr[fileArr.length - 1];

			Swal.fire({
				title: 'Are you sure you want to remove this file?',
				text: "Please note that if this file comes from a duplicated object, the original file will be removed as well!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Remove file',
				allowOutsideClick: false
			}).then((result) => {
				if (result.value) { 

					$.ajax({
						url : '<?php echo htmlspecialchars($DATABASE_PATH) ?>_Tables/delete-file.php?fileURL=' + fileURL + '&tableName=<?php echo $tableName ?>&rowID=' + rowID + '&columnName=' + columnName,
						type: 'GET',
						success: function(data) {
							if(data == 'ok'){
								$('#'+fileURLInput).val('');
								$('#'+viewButton).attr("href", '');
						
								Swal.fire({ icon: 'success', title: 'Cool', text: 'The File has been removed!' });
							} else if(data == 'no file'){
								Swal.fire({ icon: 'warning', title: 'Oops...', text: 'There is no file to delete.', });
							} else {
								Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong.', });
							}
						// error
						}, error: function(e) { 
							Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong: ' + e.message, });
					}});
				} // .,/ If
			});

		} else { Swal.fire({title: 'Oops...', text: "File upload is disabled in DEMO mode", icon: 'warning' }); }
	}



	//---------------------------------
	// MARK - ADD A COLUMN
	//---------------------------------
	function addColumn(tableName){
		var queryPath = '<?php echo htmlspecialchars($DATABASE_PATH)."_Tables/add-column.php?tableName=".$tableName."&" ?>';
		
		var columnType = $('#columnType').find(":selected").val();
		var columnName = $('#columnName').val();
		
		var isPointerTable = $('#isPointerTable').val();
		var pointerTable = "" 
		if (isPointerTable == 'yes') { pointerTable = $('#pointerTable').find(":selected").val();
		} else { pointerTable = "" }
		
		// Compose filter data
		var aData = aData = 'columnType=' + columnType + '&columnName=' + columnName + '&pointerTable=' + pointerTable;
		
		// Prohibited column names:
		if(
			columnName == 'id' 
			|| columnName == 'username' 
			|| columnName == 'password' 
			|| columnName == 'createdAt'
			|| columnName == 'updatedAt'
			|| columnName.includes('<')
			|| columnName.includes('>')
			|| columnName == 'badge'
		){
			Swal.fire({ icon: 'error', title: 'Oops...', text: 'This name is not allowed, please choose another name', });
		
		// Add column
		} else {
			// Check for special characters
			var format = /[!@$¥#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
			if(format.test(columnName) || columnName.includes(' ')){ 
				Swal.fire({ icon: 'error', title: 'Oops...', text: 'Special characters are not allowed.', });
				return; 
			}

			$.ajax({
				url : queryPath,
				type: 'GET',
				data: aData,
				success: function(data) {
					if(data == 'ok'){
						window.location.reload();
					} else {
						Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong.', });
					}
				// error
				}, error: function(e) {  
					Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong: ' + e.message, });
			}});
		
		} // ./ If
	}
		


	//---------------------------------
	// MARK - RENAME A COLUMN
	//---------------------------------
	function renameColumn() {
		var colToRename = $('#colToRename').find(":selected").val();
		var newColName = $('#newColName').val();
		var tableName = '<?php echo $tableName ?>';
		
		// Check for special characters
		var format = /[!@$¥#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
		if(format.test(newColName) || newColName.includes(' ')){ 
			Swal.fire({ icon: 'error', title: 'Oops...', text: 'Special characters or spaces are not allowed.', });
			return; 
		}

		var colNameArr = colToRename.split("_");
		var colType = colNameArr[0];
		// Pointer Column 
		if (colNameArr.length == 3) {
			newColName = colType + "_" + newColName + '_' + colNameArr[2];
		// Other Column
		} else {
			newColName = colType + "_" + newColName;
		}
		
  		if (newColName == "") {
  			Swal.fire({
				title: 'Oops...',
				text: 'Please type something.',
				icon: 'error',
				showCancelButton: false,
				confirmButtonText: 'OK'
			});

  		} else {
			if (newColName.indexOf(' ') >= 0){
	  			Swal.fire({
					title: 'Oops...',
					text: 'Please type a name with no spaces.',
					icon: 'error',
					showCancelButton: false,
					confirmButtonText: 'OK'
				});
	  		} else {
				Swal.fire({
					title: 'Rename a Column',
					text: 'Are you sure you want to rename the "' + colToRename + "' column into '" + newColName + "'?",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Rename'
				}).then((result) => {
					if (result.value) {
						document.location.href = 'rename-column.php?tableName=<?php echo $tableName ?>&colToRename=' + colToRename + '&newColName=' + newColName;
			  		} // ./ If
				});
			}
		}
	}


	//---------------------------------
	// MARK - ADD A TABLE
	//---------------------------------
	function addTable(){
		var tableName = $('#aTableName').val();
		// Prohibited Table names:
		if(tableName == 'Users'){
			Swal.fire({ icon: 'error', title: 'Oops...', text: 'You cannot create another Table called "Users", please choose another name', });
		
		// Add table
		} else {
			// Check for special characters
			var format = /[!@$¥#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
			if(format.test(tableName) || tableName.includes(' ')){ 
				Swal.fire({ icon: 'error', title: 'Oops...', text: 'Special characters or spaces are not allowed.', });
				return; 
			}

			$.ajax({
				url : '<?php echo htmlspecialchars($DATABASE_PATH)."_Tables/add-table.php?" ?>',
				type: 'GET',
				data: 'tableName=' + tableName,
				success: function(data) {
					if(data == 'ok'){
						window.location.reload();
					} else {
						Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong.', });
					}
				// error
				}, error: function(e) {  
					Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong: ' + e.message, });
			}});
		} // ./ If
	}



	//---------------------------------
	// MARK - IMPORT TABLES
	//---------------------------------
	function importTables() {
		Swal.fire({
			title: 'Import Tables',
			html: 	'Upload one or more JSON files<br><input multiple id="jsonFileInput" name="jsonFileInput" type="file" class="swal2-input" style="height: 32px; font-size: 12px;" onchange="uploadTableFiles(\'jsonFileInput\')">' + 
					'<label for="jsonFileInput">Choose file(s)</label>' +
					'<p style="font-size:11px;">Please note that Tables with the same name of the choosen one will be overwitten.</p>',
			showCancelButton: true,
			showConfirmButton: false,
		});
	}


	//---------------------------------
   // UPLOAD TABLE FILE
   //---------------------------------
   function uploadTableFiles(fileInput) {
   		var dbPath = '<?php echo htmlspecialchars($DATABASE_PATH) ?>';
		
		var files = $("#"+fileInput)[0].files;
		for (var i=0; i<files.length; i++) {
		    var fileName = files[i].name;
		    var fileType = files[i].type;
		    // console.log("Filename: " + fileName + " , Type: " + fileType);
		    
		    // Not a JSON file
    		if(fileType != 'application/json') {
    		    Swal.fire({icon: 'error', title: 'Oops...', text: 'Please choose a JSON file', allowOutsideClick: false })
    		    return;
    		}
    		
    		var data = new FormData();
    		data.append('file', files[i]);
    		data.append('fileName', fileName);
    		
    		Swal.fire({icon: 'success', title: 'Loading...', showConfirmButton: false, allowOutsideClick: false })
    
    		$.ajax({
    			url : dbPath + 'upload-table-file.php',
    			type: 'POST',
    			data: data,
    			contentType: false,
    			processData: false,
    			mimeType: "multipart/form-data",
    			success: function(data) {
    				Swal.close();
    
    				var fileURL = dbPath + data;
    				// console.log('TABLE FILE UPLOADED TO: ' + fileURL);
    
    				// error
    				if (data.includes("ERROR:")) {
    					Swal.fire({ icon: 'error', title: 'Oops...', text: data, });
    				
    				// Reload page
    				} else { 
    				    // Not a JSON file
                		if(fileType != 'application/json') {
                		    Swal.fire({icon: 'error', title: 'Oops...', text: 'One of your files is not a JSON file so it will not be uploaded.', allowOutsideClick: false })
                		    return;
                		}
    				    document.location.reload(); 
    				}
    				
    			// error
    			}, error: function(e) {  
    				Swal.close();
    				Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong: ' + e.message, });
    		}});
    		
        } //./ for
	}


	//---------------------------------
	// MARK - EXPORT TABLE
	//---------------------------------
	function exportTable() {
		$.ajax({
		  url:'get-tables.php',
		  success:function(data) {
		    var filesdata = data.split(',');
		    filesdata.pop();
		    
		    var filesList = new Object(); 
		    for(var i=0; i<filesdata.length; i++) { filesList[filesdata[i] + '.json'] = filesdata[i]; }
		    
		    Swal.fire({
				title: 'Select a Table',
				text: 'Select a Table and download it as a JSON file',
				input: 'select',
				inputOptions: filesList,
				inputPlaceholder: 'Select a Table',
				showCancelButton: true,
				confirmButtonText: 'Download Table',
				inputValidator: (value) => {
					if (value == '') { Swal.fire({ title: 'Oops...', text: 'Please select a Table', icon: 'error' });

					} else {
						var data_arrayStr = JSON.stringify(<?php echo json_encode($data_array) ?>, null, 2);
		            	downloadJSONFile(data_arrayStr, value);
					} //./ If
				} //./ inputValidator
			});

		  // error
		  },error: function(xhr, status, error) {
		    var err = eval("(" + xhr.responseText + ")");
		    // console.log(err.message);
		}});
		
	}

	function downloadJSONFile(text, name) {
		// console.log(text + " -- " + name);
		const a = document.createElement('a');
		const type = name.split(".").pop();
		a.href = URL.createObjectURL( new Blob([text], { type:`text/${type === "txt" ? "plain" : type}` }) );
		a.download = name;
		a.click();
	}

	//---------------------------------
	// MARK - RENAME A TABLE
	//---------------------------------
	function renameTable() {
		var tableToRename = $('#tableToRename').find(":selected").val();
		var newTableName = $('#newTableName').val();
		
		// Check for special characters
		var format = /[!@$¥#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
		if(format.test(newTableName) || newTableName.includes(' ')){ 
			Swal.fire({ icon: 'error', title: 'Oops...', text: 'Special characters or spaces are not allowed.', });
			return; 
		}

  		if (newTableName == "") {
  			Swal.fire({
				title: 'Oops...',
				text: 'Please type something.',
				icon: 'error',
				showCancelButton: false,
				confirmButtonText: 'OK'
			});

  		} else {
  			if (newTableName.indexOf(' ') >= 0){
	  			Swal.fire({
					title: 'Oops...',
					text: 'Please type a name with no spaces.',
					icon: 'error',
					showCancelButton: false,
					confirmButtonText: 'OK'
				});
	  		} else {
				Swal.fire({
					title: 'Rename a Table',
					text: 'Are you sure you want to rename the "' + tableToRename + "' table into '" + newTableName + "'?",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Rename'
				}).then((result) => {
					if (result.value) {
						document.location.href = 'rename-table.php?tableToRename=' + tableToRename + '&newTableName=' + newTableName;
			  		}// ./ If
				});
			}
		}
	}



	//---------------------------------
	// MARK - DELETE TABLE
	//---------------------------------
	function deleteTable() {
		var tableName = $('#selTableName').find(":selected").val();
  		
		Swal.fire({
			title: 'Are you sure you want to delete the "' + tableName + '" Table?',
			text: "You won't be able to revert this and all the data of this table will be permanently removed!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Delete'
		}).then((result) => {
			if (result.value) {
				document.location.href = 'delete-table.php?tableName=' + tableName;
	  		}// ./ If
		});
	}



	//---------------------------------
	// MARK - DELETE A COLUMN
	//---------------------------------
	function deleteColumn(tableName) {
		var colName = $('#colName').find(":selected").val();
  		
		Swal.fire({
			title: 'Are you sure you want to delete the "' + colName + '" column?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Delete'
		}).then((result) => {
			if (result.value) {
				document.location.href = 'delete-column.php?tableName=' + tableName + '&colName=' + colName;
	  		}// ./ If
		});
	}
		

	//---------------------------------
	// MARK - SHOW CUCCESS DELETION ALERT
	//---------------------------------
	function showSuccessDeletionAlert(tableName, message) {
		Swal.fire({
			title: 'Yeah!',
			text: message,
			icon: 'success',
			showCancelButton: false,
			confirmButtonText: 'Back',
			allowOutsideClick: false
		}).then((result) => {
			if (result.value) {
				document.location.href = 'index.php?tableName=' + tableName;
	  		}
		});
	}

	//---------------------------------
	// MARK -  GO BACK 
	//---------------------------------
	function goBack() { window.history.back(); }

	// Demo alert
	function demoAlert() {
		Swal.fire({ icon: 'error', title: 'Oops...', text: 'This function is disabled in DEMO mode' });
	}
</script>
 
</body>
</html>