<?
	session_start();
	include_once("../../bit_config.php");
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
	$id=$_GET["flv"];
	
	#Hits
	$qry="select hits from m_video where id=".$id;
	$rsHits=$ora->sql_fetch($qry,$bit_app["db"]);
	
	#Hits
	$qry="update m_video set hits=".($rsHits->value[1][1]+1)." where id=".$id;
	$ora->sql_no_fetch($qry,$bit_app["db"]);
	
	$ora->logoff();
?>