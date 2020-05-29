<?
	session_start();
	include_once("../../bit_config.php");
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
	$id=$_GET["id"];
	
	if ($_POST["hRate"]==1) {
		$qry="insert into m_foto_rating(foto_id,rating,updated_date,updated_by)
				values('$id',".getPoint(1).",sysdate(),'".getUserID()."')";
		$ora->sql_no_fetch($qry,$bit_app["db"]);
	} elseif ($_POST["hRate"]==2) {
		$qry="insert into m_foto_rating(foto_id,rating,updated_date,updated_by)
				values('$id',".getPoint(2).",sysdate(),'".getUserID()."')";
		$ora->sql_no_fetch($qry,$bit_app["db"]);
	}
	
	echo "<form method='POST' name='frRate' id='frRate'>";
	$qry="select 
				rating,count(1)
			from 
				m_foto_rating
			where 
				foto_id=".$id."
			group by rating";
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	for ($i=1;$i<=$rs->jumrec;$i++) {
		$dtRate[$rs->value[$i][1]]=$rs->value[$i][2];
	}
	
	$qry="select 
				*
			from 
				m_foto_rating
			where 
				foto_id=".$id." and updated_by='".getUserID()."'";
	$rsCheck=$ora->sql_fetch($qry,$bit_app["db"]);
	
	echo "<br />";
	if ($rsCheck->jumrec==0) {
		echo "<a title='Good News' onClick='document.frRate.hRate.value=1;dtRate($id)' ><img src='bit_images/good.png' border=0></a> <span class='bit_rate'>(".format($dtRate[1]).")</span>";
		echo "<a title='Bad News' onClick='document.frRate.hRate.value=2;dtRate($id)'><img src='bit_images/worst.png'></a> <span class='bit_rate'>(".format($dtRate[0]).")</span>";
	} else {
		echo "<img title='Good News' src='bit_images/good.png' border=0> <span class='bit_rate'>(".format($dtRate[1]).")</span>";
		echo "<img title='Bad News' src='bit_images/worst.png'> <span class='bit_rate'>(".format($dtRate[0]).")</span>";
	}

	echo "<input type='hidden' name='hRate' />";
	echo "</form>";
?>