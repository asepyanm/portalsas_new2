<?
	session_start();
	include_once("../../bit_config.php");
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
	$id=$_GET["id"];
	
	if (!$_GET["foto"]) {
		$qry="select foto_name,id,hits from m_foto_detail where foto_id=".$_GET["id"]." order by id asc";
		$rsDetail=$ora->sql_fetch($qry,$bit_app["db"]);
		$idChild=$rsDetail->value[1][2];
	} else {
		$qry="select foto_name,id,hits from m_foto_detail where foto_id=".$_GET["id"]." and id=".$_GET["foto"];
		$rsDetail=$ora->sql_fetch($qry,$bit_app["db"]);
		$idChild=$_GET["foto"];
	}
	#Hits
	$qry="select hits from m_foto where id=".$id;
	$rsHits=$ora->sql_fetch($qry,$bit_app["db"]);
	
	#Hits
	$qry="update m_foto set hits=".($rsHits->value[1][1]+1)." where id=".$id;
	$ora->sql_no_fetch($qry,$bit_app["db"]);
	
	#Hits
	$qry="select hits from m_foto_detail where foto_id=".$id." and id=".$idChild;
	$rsHits=$ora->sql_fetch($qry,$bit_app["db"]);
	
	#Hits
	$qry="update m_foto_detail set hits=".($rsHits->value[1][1]+1)." where foto_id=".$id." and id=".$idChild;
	$ora->sql_no_fetch($qry,$bit_app["db"]);
	
	
	$qry="select 
				date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,
				title,funcGetUser(updated_by) updated_by,description,funcGetUser(created_by) created_by
			from 
				m_foto where id=".$id;
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	
	echo "<div class='headtitle'><a>".$rs->value[1]["title"]."</a></div>";
	echo "<div class='headdate'><span class='headby'>".$rs->value[1]["created_by"]."</span> - ".$rs->value[1]["created_date"]."</div>";
	echo "<div class=''><img class='bit_foto_image' width='400' src='".$bit_app["folder_url"].$rsDetail->value[1][1]."'></div>";
	echo "<div class='headcontent'>".$rs->value[1]["description"]."</div> <span class='headhits'>  [ ".format($rsDetail->value[1]["hits"])." hits ]</span>";
	echo "<br />";
	echo "<br />";
	
	$qry="select foto_name,id from m_foto_detail where foto_id=".$_GET["id"];
	$rsDetail=$ora->sql_fetch($qry,$bit_app["db"]);
	
	for ($i=1;$i<=$rsDetail->jumrec;$i++) {
		echo "<a onClick='dtShowFoto(".$id.",".$rsDetail->value[$i][2].");dtComment(".$id.",".$rsDetail->value[$i][2].");' target='_parent' class='bit_row_title'><img class='bit_foto_image' width='100' src='".$bit_app["folder_url"].$rsDetail->value[$i][1]."'></a>";
		
		if ($i%4==0)
			echo "<br />";
		
	}
	
	$ora->logoff();
?>