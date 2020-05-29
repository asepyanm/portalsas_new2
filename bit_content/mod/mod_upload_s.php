<?
function do_upload($userfile) {
	global $pathFolder;

	$pos = strrpos($_FILES[$userfile]["name"], ".");
	if(!$_FILES[$userfile]["extention"]) { 
		$_FILES[$userfile]["extention"] = substr($_FILES[$userfile]["name"], $pos, strlen($_FILES[$userfile]["name"]));
	}

	$_FILES[$userfile]['raw_name'] = substr($_FILES[$userfile]["name"], 0, $pos);
	
	while(file_exists($pathFolder . $_FILES[$userfile]['raw_name'] . $copy . $_FILES[$userfile]["extention"])) {
		$copy = "_copy" . $n;
		$n++;
	}
		
	$_FILES[$userfile]["name"]  = $_FILES[$userfile]['raw_name'] . $copy . $_FILES[$userfile]["extention"];
	$temp_name = $_FILES[$userfile]['tmp_name'];
	$file_name = $_FILES[$userfile]['name']; 
	$file_type = $_FILES[$userfile]['type']; 
	$file_size = $_FILES[$userfile]['size']; 
	$result    = $_FILES[$userfile]['error'];
	$file_path = $pathFolder.$file_name;
	
	$result  = move_uploaded_file($temp_name, $file_path);
    if ($result) 
		return $file_name;
	else
		return "";
}
?>
