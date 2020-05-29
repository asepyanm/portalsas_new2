<?php

include_once("../bit_config.php");
$params['awsAccessKey'] = AWSACCESSKEY;
$params['awsSecretKey'] = AWSSECRETKEY;
$params['endpoint'] = ENDPOINT;

$s3 = new S3($params);

$target = $_GET['target'];
$file = $bit_app['path'].'bit_folder/'.$target;
$target_redirect='bit_folder/'.$target;

if(!file_exists($file) || filesize($file)==0){ 
    $s3 = $this->s3->getObject(BUCKETNAME, S3DIR_DATA.$target, $file);
}

echo '<script>';
echo 'window.location.replace("'.$bit_app['path_url'].'bit_folder/'.$target.'")';
echo '</script>';