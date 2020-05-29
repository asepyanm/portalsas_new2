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
<script type="text/javascript" src="../../bit_mod/mod_js.js"></script>
<script>
	function validate() {
		
		if (document.forms[0].userfile.value!='') {
			if (checkFileExtensionImage(document.forms[0].userfile)!=1) {
				alert(checkFileExtensionImage(document.forms[0].userfile)); 
				return false;
			}
		}	
		if (document.forms[0].tInput[0].value=='') {
			alert('Alamat silahkan diisi terlebih dahulu !');
			document.forms[0].tInput[0].focus();
			return false;
		}
	
		if (document.forms[0].tInput[1].value=='') {
			alert('Telp. Kantor silahkan diisi terlebih dahulu !');
			document.forms[0].tInput[1].focus();
			return false;
		}
		
		if (document.forms[0].tInput[2].value=='') {
			alert('Telp. Rumah silahkan diisi terlebih dahulu !');
			document.forms[0].tInput[2].focus();
			return false;
		}
		return true;
	}
</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput[0].focus()">
<?
	
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
?>
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="4">
			<? echo setTitle2("Edit Profile");?>
		</td>
	</tr>
	<?
		$qry="select 
				a.user_id,
				b.nama_karyawan,
				b.nama_posisi,
				b.loker2,
				a.user_address,
				a.user_telp_office,
				a.user_telp_home,
				a.user_foto
			from m_users a,m_master_user b where a.user_id=b.nik and a.user_id='".getUserID()."'";
		$rs=$ora->sql_fetch($qry,$bit_app["db"]);
		
		if (!$_POST["ok"]) {
			$_POST["tInput"][0]=$rs->value[1][5];
			$_POST["tInput"][1]=$rs->value[1][6];
			$_POST["tInput"][2]=$rs->value[1][7];
		}
	?>
	<tr>
	<tr>
		<td class="row1" width="40%">NIK</td>
		<td colspan="3" class="row2"><? echo $rs->value[1][1]?></td>
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
		<td valign="top" class="row2" width="1%">
		<?
			if ($rs->value[1][8])
				echo "<img src='../../bit_folder/".$rs->value[1][8]."' width='80' class='bit_image'><br />";
		?>
		</td>
		<td colspan="2" class="row2" valign="bottom">
		<? $f->file("userfile","userfile","browse","inputBox",25,255)?>
		</td>
	</tr>
	<tr>
		<td class="row1" valign="top">Alamat</td>
		<td colspan="3" class="row2" valign="top"><? $f->textarea("tInput[]","tInput",$_POST["tInput"][0],"inputBox",5,50)?> *</td>
	</tr>
	<tr>
		<td class="row1">Telp. Kantor</td>
		<td colspan="3" class="row2"><? $f->textbox("tInput[]","tInput",$_POST["tInput"][1],"inputBox",15,255)?> *</td>
	</tr>
	<tr>
		<td class="row1">Telp. Rumah </td>
		<td colspan="3" class="row2"><? $f->textbox("tInput[]","tInput",$_POST["tInput"][2],"inputBox",15,255)?> *</td>
	</tr>
	<tr>
		<td colspan="4">
			<hr class="article_layout_hr">
			<b>Ket :</b>
			Field yang bertanda (*) harus diisi.
			<hr class="article_layout_hr">
		</td>
	</tr>
	<tr>
		<td colspan="4" align="left">
			<? $f->submit("ok","Simpan","button")?>
			<? $f->button("reset","Close","button","parent.$.fn.colorbox.close()")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
	
	
	if ($_POST["ok"]) {
		$img=do_upload_img("userfile");
		if ($img)
			$wFoto = ",user_foto = '".$img."'";  
		
		$qry="update m_users
				set 
					user_address='".escape($_POST["tInput"][0])."',
					user_telp_office='".escape($_POST["tInput"][1])."',
					user_telp_home='".escape($_POST["tInput"][2])."'
					$wFoto
				where
					user_id='".getUserID()."'";
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Update User Gagal. hubungi administrator !');
			$ora->logoff();
		} else {
			$ora->logoff();
			refresh_parent('../../home.php');
		}
	}
	$ora->logoff();
?>
</body>
</html>
 