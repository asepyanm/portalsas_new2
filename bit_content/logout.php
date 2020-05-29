<?php
	session_start();
	session_destroy();
	require_once('../bit_config.php');
	header("location:".$bit_app["path_url"]);
	//header("location:login.php");
?>