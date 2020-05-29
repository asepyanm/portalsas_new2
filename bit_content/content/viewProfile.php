<?
	session_start();
	include_once("../../bit_config.php");
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
?>
<html>
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput[0].focus()">
<?
	
	$f=new clsForm;
?>
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="4">
			<? echo setTitle2("View Profile");?>
		</td>
	</tr>
	<?
		$qry="select 
				a.user_id,
				b.nama_karyawan,
				b.nama_posisi,
				b.loker2,
				a.user_address,
				a.user_telp_home,
				a.user_telp_office,
				a.user_foto
			from m_users a,m_master_user b where a.user_id=b.nik and a.user_id='".$_GET["userid"]."'";
		$rs=$ora->sql_fetch($qry,$bit_app["db"]);
		
		if (!$_POST["ok"]) {
			$_POST["tInput"][0]=$rs->value[1][5];
			$_POST["tInput"][1]=$rs->value[1][6];
			$_POST["tInput"][2]=$rs->value[1][7];
		}
	?>
	<tr>
		<td class="row1">NIK</td>
		<td colspan="3" class="row2"  width="70%"><? echo $rs->value[1][1]?></td>
	</tr>
	<tr>
		<td class="row1">Nama</td>
		<td colspan="3" class="row2"><? echo $rs->value[1][2]?></td>
	</tr>
	<tr>
		<td class="row1">Posisi</td>
		<td colspan="3" class="row2"><? echo $rs->value[1][3]?></td>
	</tr>
	<tr>
		<td class="row1">Loker</td>
		<td colspan="3" class="row2"><? echo $rs->value[1][4]?></td>
	</tr>
	<tr>
		<td valign="top" class="row1">Foto</td>
		<td valign="top" colspan="3" class="row2" width="1%">
		<?
			if ($rs->value[1][8])
				echo "<img src='../../bit_folder/".$rs->value[1][8]."' width='80' class='bit_image'><br />";
		?>
		</td>
	</tr>
	<tr>
		<td class="row1" valign="top">Alamat</td>
		<td colspan="3" class="row2" valign="top"><? echo $_POST["tInput"][0]?></td>
	</tr>
	<tr>
		<td class="row1">Telp. Kantor</td>
		<td colspan="3" class="row2"><? echo $_POST["tInput"][1]?></td>
	</tr>
	<tr>
		<td class="row1">Telp. Rumah </td>
		<td colspan="3" class="row2"><? echo $_POST["tInput"][2]?></td>
	</tr>
	<tr>
		<td colspan="4"><hr class="article_layout_hr"></td>
	</tr>
	<tr>
		<td colspan="4" align="right">
			<? $f->button("reset","Close","button","parent.$.fn.colorbox.close()")?>
		</td>
	</tr>
</table>
<?
	$ora->logoff();
?>
</body>
</html>
 