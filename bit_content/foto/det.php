<?
	session_start();
	include_once("../../bit_config.php");
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
</head>
<body topmargin="0" leftmargin="0" rightmargin="0">
<?
	
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
	$qry="select * from m_foto where id=".$_GET["id"];
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			
?>
<table width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitle2("Detail Foto : ".$rs->value[1]["title"]);?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<?
			
			$qry="select * from m_foto where id=".$_GET["id"];
			$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			
			if (!$_POST["ok"]) {
				$_POST["tInput"]=$rs->value[1]["title"];
				$_POST["fck"]=$rs->value[1]["description"];
			}
			
			$qry="select foto_name from m_foto_detail where foto_id=".$_GET["id"];
			$rsDetail=$ora->sql_fetch($qry,$bit_app["db"]);
			
			echo "<table border=0 width='100%'>";
			for ($j=1;$j<=$rsDetail->jumrec;$j++) {
				
				if ($j%5==1)
					echo "<tr>";
				
				echo "<td><a target='_blank' href='".$bit_app["folder_url"].$rsDetail->value[$j][1]."'><img class='bit_image' class='bit_foto_image' width='100' src='".$bit_app["folder_url"].$rsDetail->value[$j][1]."'></a></td>";
				if ($j%5==0)
					echo "</tr>";
				
			}
			echo "</table>";
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" height="100%">
		<?
			echo $_POST["fck"];
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<? $f->button("reset","Close","button","window.close()")?>
		</td>
	</tr>
</table>
<?
$qry="select date_format(updated_date,'%W, %d/%m/%Y %h:%i') updated_date,updated_by,keterangan,status from h_foto where foto_id=".$_GET["id"]." order by id asc";
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

<input type="hidden" name="hFoto" />
<?
	$f->closeForm();
	$ora->logoff();
?>
</body>
</html>
 