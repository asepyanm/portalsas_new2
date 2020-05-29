<?php
/*
	$is_production = getenv('APP_IS_PRODUCTION');
	if($is_production == 1){
		error_reporting(0);
		ini_set('display_errors', 0);
	}else{
		error_reporting(E_ALL & ~E_NOTICE);	
		ini_set('display_errors', 1);
	}
*/
error_reporting(0);
ini_set('display_errors', 0);

// error_reporting(E_ALL & ~E_NOTICE);
// ini_set('display_errors', 1);

/*
	define("DB_HOST", ($is_production == 1 ? getenv('PROD_DB_HOST') : getenv('DEV_DB_HOST')));
	define("DB_USER", ($is_production == 1 ? getenv('PROD_DB_UNAME') : getenv('DEV_DB_UNAME')));
	define("DB_PASS", ($is_production == 1 ? getenv('PROD_DB_PWD') : getenv('DEV_DB_PWD')));
	define("DB_NAME", ($is_production == 1 ? getenv('PROD_DB_SCHEMA') : getenv('DEV_DB_SCHEMA')));
	define("DB_PORT", ($is_production == 1 ? getenv('PROD_DB_PORT') : getenv('DEV_DB_PORT')));
	*/

if (getenv('DB_PORTALSAS_HOST')) {
	define("DB_PORTALSAS_HOST", getenv('DB_PORTALSAS_HOST'));
	define("DB_PORTALSAS_USER", getenv('DB_PORTALSAS_USER'));
	define("DB_PORTALSAS_PASS", getenv('DB_PORTALSAS_PASS'));
	define("DB_PORTALSAS_NAME", getenv('DB_PORTALSAS_NAME'));
	define("DB_PORTALSAS_PORT", getenv('DB_PORTALSAS_PORT'));
} else {
	define("DB_PORTALSAS_HOST", "localhost");
	define("DB_PORTALSAS_USER", "root");
	define("DB_PORTALSAS_PASS", "");
	define("DB_PORTALSAS_NAME", "portalsas");
	define("DB_PORTALSAS_PORT", "3306");
}


define("LDAP_HOST", getenv('LDAP_HOST'));
define("LDAP_PORT", getenv('LDAP_PORT'));


ini_set("register_globals", 1);
require_once("bit_mod/bit_ldap.php");
require_once("bit_mod/mod_upload_s.php");
require_once("bit_mod/mod_upload.php");
require_once("bit_mod/mod_mysql.php");
require_once("bit_mod/mod_block.php");
require_once("bit_mod/mod_func.php");
require_once("bit_mod/mod_form.php");
require_once("bit_mod/mod_resize.php");
require_once("bit_mod/mod_oracle.php");

//S3
require_once("bit_mod/S3.php");

#PHPExcel
#require_once('PHPExcel.php');
#require_once('PHPExcel/Reader/Excel5.php');


#Email
require_once("bit_mod/sendmail.php");
require_once("bit_mod/class_mime_decode.php");
require_once("bit_mod/class_smtp.php");

#FCKEditor
//require_once("bit_mod/mod_fckEditor.php");
require_once("bit_third/FCKeditor2/fckeditor.php");

#Databse
// define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
// define('DB_PORT', getenv('OPENSHIFT_MYSQL_DB_PORT')); 
// define('DB_USER', getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
// define('DB_PASS', getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
// define('DB_NAME', "portalsas2");


$bit_app["db"] = DB_PORTALSAS_NAME;
$bit_app["user_db"] = DB_PORTALSAS_USER;
$bit_app["pass_db"] = DB_PORTALSAS_PASS;

#App
$bit_app["title"] = "Portal SAS";
$bit_app["ldap"] = 0;

#Paging
$bit_app["sumOfRow"] = 25;
$bit_app["sumOfPage"] = 10;

$bit_app["new_width"] = 300;

#PORTAl
$bit_app["app"] = "portalsas_new2/";
//$bit_app["app"]="";
$bit_app["path"] = $_SERVER['DOCUMENT_ROOT'] . "/" . $bit_app["app"];
$bit_app["path_url"] = "https://" . $_SERVER['HTTP_HOST'] . "/" . $bit_app["app"];
$bit_app["upload"] = $_SERVER['DOCUMENT_ROOT'] . "/" . $bit_app["app"] . "bit_upload/";
$bit_app["folder_dir"] = $_SERVER['DOCUMENT_ROOT'] . "/" . $bit_app["app"] . "bit_folder/";
$bit_app["folder_url"] = "https://" . $_SERVER['HTTP_HOST'] . "/" . $bit_app["app"] . "bit_folder/";
$bit_app["folder_banner_url"] = "https://" . $_SERVER['HTTP_HOST'] . "/" . $bit_app["app"] . "bit_banner/";
$bit_app["image_url"] = "https://" . $_SERVER['HTTP_HOST'] . "/" . $bit_app["app"] . "bit_images/";

//echo $bit_app["path_url"];
$gHomePageUrl = $bit_app["path_url"];
#CSS
$bit_app["css"] = "https://" . $_SERVER['HTTP_HOST'] . "/" . $bit_app["app"] . "bit_theme/default/css/";

#Third
$bit_app["third"] = $_SERVER['DOCUMENT_ROOT'] . "/bit_third";
$bit_app["third_url"] = "https://" . $_SERVER['HTTP_HOST'] . "/bit_third";

#Email
$bit_app["email"] = 0;

//BEGIN S3 CONFIG
define('AWSACCESSKEY', 'coll_storage');
define('AWSSECRETKEY', 'fzcohdOCkrVBqlntIG/j0YGp6amczrKnDIUahYJo');
define('BUCKETNAME', 'PORTALSAS');
define('ENDPOINT', 'ecsdropup.telkom.co.id:9020');
define('PORTALSASDIR_DATA', 'PORTALSAS/');

$params['awsAccessKey'] = AWSACCESSKEY;
$params['awsSecretKey'] = AWSSECRETKEY;
$params['endpoint'] = ENDPOINT;

$s3 = new S3($params);
	
	//$config['s3dir_contractor'] = 'CONTRACTOR/';
	//END S3 CONFIG
