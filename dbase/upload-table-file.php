<?php include '_config.php';

if ($_FILES["file"]["error"] > 0) {
 	echo "ERROR: " .$_FILES["file"]["error"]. "<br>";

} else {
    // Check file size
    if ($_FILES["file"]["size"] > 20485760) { // 20 MB
        echo "ERROR: Your file is larger than 20 MB. Please upload a smaller one.";    
    } else { uploadFile(); }

}// ./ If


// Upload file ------------------------------------------
function uploadFile() {
    $filePath = "_Tables/".$_POST['fileName'];

    // Upload file into the 'uploads' folder
    move_uploaded_file($_FILES['file']['tmp_name'], $filePath);

    // Echo link of the uploaded file
    echo $filePath;
}
?>