<?php include '_config.php';

// Error on upload
$err = $_FILES["file"]["error"];
if ($err > 0) {
    if ($err == 1) { echo "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
    } else if ($err == 2) { echo "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
    } else if ($err == 3) { echo "The uploaded file was only partially uploaded.";
    } else if ($err == 4) { echo "No file was uploaded."; } 

// Upload is OK
} else {
    // Check file size
    if ($_FILES["file"]["size"] > 20485760) { // 20 MB
        echo "ERROR: Your file is larger than 20 MB. Please upload a smaller one.";    
    } else { uploadFile(); }

}// ./ If


// Upload file ------------------------------------------
function uploadFile() {
    // generate a unique random string
    $randomStr = generateRandomString();
    $filePath = "uploads/".$randomStr;

    // upload file into the 'uploads' folder
    move_uploaded_file($_FILES['file']['tmp_name'], $filePath);

    // echo the link of the uploaded file
    echo $filePath;
}

// Generate a random string ---------------------------------------
function generateRandomString() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i<20; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString."_".$_POST['fileName'];
}
?>