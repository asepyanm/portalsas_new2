<?php
function do_upload($userfile)
{
	global $bit_app;

	$params['awsAccessKey'] = AWSACCESSKEY;
	$params['awsSecretKey'] = AWSSECRETKEY;
	$params['endpoint'] = ENDPOINT;

	$s3 = new S3($params);

	$pathFolder = $bit_app["folder_dir"];

	$pos = strrpos($_FILES[$userfile]["name"], ".");
	if (!$_FILES[$userfile]["extention"]) {
		$_FILES[$userfile]["extention"] = substr($_FILES[$userfile]["name"], $pos, strlen($_FILES[$userfile]["name"]));
	}

	$_FILES[$userfile]['raw_name'] = substr($_FILES[$userfile]["name"], 0, $pos);

	while (file_exists($pathFolder . $_FILES[$userfile]['raw_name'] . $copy . $_FILES[$userfile]["extention"])) {
		$copy = "_copy" . $n;
		$n++;
	}

	$_FILES[$userfile]["name"]  = $_FILES[$userfile]['raw_name'] . $copy . $_FILES[$userfile]["extention"];
	$temp_name = $_FILES[$userfile]['tmp_name'];
	$file_name = $_FILES[$userfile]['name'];
	$file_type = $_FILES[$userfile]['type'];
	$file_size = $_FILES[$userfile]['size'];
	$result    = $_FILES[$userfile]['error'];
	$file_path = $pathFolder . $file_name;

	//////s3/////////////////////////////////////////////////	
	$s3->putBucket(BUCKETNAME, 'private');
	$s3->putObjectFile($_FILES[$userfile]['tmp_name'], BUCKETNAME, PORTALSASDIR_DATA . $file_name, 'private');
	/////end of s3//////////////////

	$result  = move_uploaded_file($temp_name, $file_path);
	if ($result)
		return $file_name;
	else
		return "";
}

function do_upload_limit($userfile, $limit)
{
	global $bit_app;

	$params['awsAccessKey'] = AWSACCESSKEY;
	$params['awsSecretKey'] = AWSSECRETKEY;
	$params['endpoint'] = ENDPOINT;

	$s3 = new S3($params);

	$pathFolder = $bit_app["folder_dir"];

	$pos = strrpos($_FILES[$userfile]["name"], ".");
	if (!$_FILES[$userfile]["extention"]) {
		$_FILES[$userfile]["extention"] = substr($_FILES[$userfile]["name"], $pos, strlen($_FILES[$userfile]["name"]));
	}

	$_FILES[$userfile]['raw_name'] = substr($_FILES[$userfile]["name"], 0, $pos);

	while (file_exists($pathFolder . $_FILES[$userfile]['raw_name'] . $copy . $_FILES[$userfile]["extention"])) {
		$copy = "_copy" . $n;
		$n++;
	}

	$_FILES[$userfile]["name"]  = $_FILES[$userfile]['raw_name'] . $copy . $_FILES[$userfile]["extention"];
	$temp_name = $_FILES[$userfile]['tmp_name'];
	$file_name = $_FILES[$userfile]['name'];
	$file_type = $_FILES[$userfile]['type'];
	$file_size = $_FILES[$userfile]['size'];
	$result    = $_FILES[$userfile]['error'];
	$file_path = $pathFolder . $file_name;

	//////s3/////////////////////////////////////////////////	
	$s3->putBucket(BUCKETNAME, 'private');
	$s3->putObjectFile($_FILES[$userfile]['tmp_name'], BUCKETNAME, PORTALSASDIR_DATA . $file_name, 'private');
	/////end of s3//////////////////

	if ($file_size > $limit) {
		alert('Ukuran tidak boleh melebihi ' . format($limit / 1000) . ' KB');
		exit;
	}

	$result  = move_uploaded_file($temp_name, $file_path);
	if ($result)
		return $file_name;
	else
		return "";
}


function do_upload_img($userfile)
{

	global $bit_app;

	$params['awsAccessKey'] = AWSACCESSKEY;
	$params['awsSecretKey'] = AWSSECRETKEY;
	$params['endpoint'] = ENDPOINT;

	$s3 = new S3($params);


	$pathFolder = $bit_app["folder_dir"];

	$pos = strrpos($_FILES[$userfile]["name"], ".");
	if (!$_FILES[$userfile]["extention"]) {
		$_FILES[$userfile]["extention"] = substr($_FILES[$userfile]["name"], $pos, strlen($_FILES[$userfile]["name"]));
	}

	$_FILES[$userfile]['raw_name'] = substr($_FILES[$userfile]["name"], 0, $pos);

	while (file_exists($pathFolder . $_FILES[$userfile]['raw_name'] . $copy . $_FILES[$userfile]["extention"])) {
		$copy = "_copy" . $n;
		$n++;
	}

	$_FILES[$userfile]["name"]  = $_FILES[$userfile]['raw_name'] . $copy . $_FILES[$userfile]["extention"];
	$temp_name = $_FILES[$userfile]['tmp_name'];
	$file_name = $_FILES[$userfile]['name'];
	$file_type = $_FILES[$userfile]['type'];
	$file_size = $_FILES[$userfile]['size'];
	$result    = $_FILES[$userfile]['error'];
	$ext 	   = $_FILES[$userfile]["extention"];
	$file_path = $pathFolder . $file_name;

	//////s3/////////////////////////////////////////////////	
	$s3->putBucket(BUCKETNAME, 'private');
	$s3->putObjectFile($_FILES[$userfile]['tmp_name'], BUCKETNAME, PORTALSASDIR_DATA . $file_name, 'private');

	/////end of s3//////////////////

	$result  = move_uploaded_file($temp_name, $file_path);

	if ($imageblob    = create_right_size_image($file_path, $bit_app["new_width"], $ext)) {
		imagejpeg($imageblob, $file_path);
	}

	if ($result)
		return $file_name;
	else
		return "";
}
