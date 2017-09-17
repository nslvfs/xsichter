<?php

// A list of permitted file extensions
$allowed = array('png', 'jpg', 'jpeg');

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}



	if ($_FILES['upl']['error'] !== UPLOAD_ERR_OK) {
		echo '{"status":"Upload failed with some error code"}';
		exit;
	}

	$info = getimagesize($_FILES['upl']['tmp_name']);
	if ($info === FALSE) {
		echo '{"status":"Unable to determine image type of uploaded file"}';
		exit;
	}

	if (($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
		echo '{"status":"ERROR: Not a jpeg/png"}';
		exit;
	}
	$check = getimagesize($_FILES["up"]["tmp_name"]);

	$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
	$detectedType = @exif_imagetype($_FILES["up"]["tmp_name"]);
	$error = !in_array($detectedType, $allowedTypes);

	if($error){
		 echo '{"status": "file is not an image}';
	}

	if ($_FILES["fileToUpload"]["size"] > 500000) {
		echo '{"status": "file is too large"}';
	exit;
	}

	if(move_uploaded_file($_FILES['upl']['tmp_name'], '../twistlerpics/user_uploads/'.$_FILES['upl']['name'])){
		echo '{"status":"success"}';
		exit;
	}


}

echo '{"status":"error"}';
exit;
