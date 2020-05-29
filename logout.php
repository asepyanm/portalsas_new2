<?php
	include_once("bit_config.php");
	session_start();
	
	#DEACTIVE USER
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	$qry="delete from c_user where user_id='".getUserID()."'";
	$ora->sql_no_fetch($qry,$bit_app["db"]);
	$ora->logoff();
	
	unset($_SESSION);
	session_destroy();
	//session_unregister("userid_portal");
	//session_unregister("username_portal");
	//session_unregister("userlevel_portal");
	//session_unregister("userloker_portal");

	echo "<script>window.location.href='home.php'</script>";
?>