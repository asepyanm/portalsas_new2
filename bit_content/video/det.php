<?
	include_once("../../bit_config.php");
?>
<html>
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
<script type="text/javascript" src="../../bit_third/flowplayer web/anchors_files/flowplayer-3.js"></script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST");
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	$qry="select keterangan,file,updated_date,updated_by,image from m_video where id=".$_GET["id"];
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	
?>
<table width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitle2("Detail Video");?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
		<?
		if (!$rs->value[1]["image"])
		    $rs->value[1]["image"]="telkom_movie.png";
		
		echo "<a  
			 href='".$bit_app["folder_url"]."/".$rs->value[1]["file"]."'  
			 style='display:block;width:270px;height:160px;z-index:-999'  
			 id='player'> 
			 
			 <img src='".$bit_app["folder_url"]."/".$rs->value[1]["image"]."' alt='Video 1'  width='270px' height='160px' border=0/> 
		</a>";
		
		echo "
			<script>
				flowplayer('player', '".$bit_app["third_url"]."/flowplayer/flowplayer-3.1.5.swf');
			</script>";
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr class="article_layout_hr">
			<? 
				echo nl2br($rs->value[1]["keterangan"]);
			?>
			<hr class="article_layout_hr">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<? $f->button("button","Close","button","window.close()")?>
		</td>
	</tr>
	
</table>
<?
$qry="select date_format(updated_date,'%W, %d/%m/%Y %h:%i') updated_date,updated_by,keterangan,status from h_video where video_id=".$_GET["id"]." order by id asc";
$rsHistory=$ora->sql_fetch($qry,$bit_app["db"]);
if ($rsHistory->jumrec>=1) { 
?>
<table border="0" cellpadding="3" cellspacing="1" width="100%">
<tr>
	<th colspan="2" class="af_th" align="left"><? echo setTitle2("History Workflow");?></th>
</tr>
<?
$dtUser=getUserDB();
for ($ii=1;$ii<=$rsHistory->jumrec;$ii++) {
	echo "<tr>";
	echo "<td class='row_line'>
				<table cellpadding=0 cellspacing=3 cellpadding=0><tr><td>";
				echo "<a href='".$dtUser[$rsHistory->value[$ii]["updated_by"]][3]."' target='_blank'>
				<img 
					class='bit_image'
					src='".$dtUser[$rsHistory->value[$ii]["updated_by"]][3]."'
					width='50'
					alt='".$dtUser[$rsHistory->value[$ii]["updated_by"]][3]."' 
					title='".$dtUser[$rsHistory->value[$ii]["updated_by"]][3]."' 
					border=0 /></a>";
				echo "</td><td valign='top'><span class='infoLogin'>".$dtUser[$rsHistory->value[$ii]["updated_by"]][5]."</span><br><span class='bit_row_date'>".$rsHistory->value[$ii]["updated_date"]."</span><br><span class='bit_row_date'>Status : </span><b><span class='bit_row_date'>".getStatus($rsHistory->value[$ii]["status"])."</span></b></td></tr></table>
				<br>
				<span class='af_content'>".nl2br($rsHistory->value[$ii]["keterangan"])."</span>
	</td>";
	echo "</tr>";
}
?>
</table>
<? } ?>
<?
	$f->closeform();
?>
</body>
</html>
 