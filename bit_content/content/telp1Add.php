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
<?
	getCalendarModule();
?>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tNama.focus()">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
?>
<table width="100%">
	<tr>
		<td colspan="2">
			<? echo setTitleAdd("Tambah Telp");?>
		</td>
	</tr>
	<tr>
		<td valign="top">Nama </td>
		<td><? $f->textbox("tNama","tNama",$_POST["tNama"],"inputBox",30,255)?>&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">NIK </td>
		<td><? $f->textbox("tNIK","tNIK",$_POST["tNIK"],"inputBox",30,255)?>&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">Jabatan</td>
		<td><? $f->textbox("tJabatan","tJabatan",$_POST["tJabatan"],"inputBox",30,255)?>&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">Loker</td>
		<td><? $f->textbox("tLoker","tLoker",$_POST["tLoker"],"inputBox",30,255)?>&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">Area</td>
		<td><? $f->textbox("tArea","tArea",$_POST["tArea"],"inputBox",30,255)?>&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">Telepon Kantor</td>
		<td><? $f->textbox("tKantor","tKantor",$_POST["tKantor"],"inputBox",30,255)?>&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">HP</td>
		<td><? $f->textbox("tHP","tHP",$_POST["tHP"],"inputBox",30,255)?>&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">Flexi</td>
		<td><? $f->textbox("tFlexi","tFlexi",$_POST["tFlexi"],"inputBox",30,255)?>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">
			<b>Ket :</b>
			Field yang bertanda (*) harus diisi.
			<hr class="article_layout_hr">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left">
			<? $f->submit("ok","s i m p an","button")?>
			<? $f->button("reset","c l o s e","button","window.close()")?>
		</td>
	</tr>
</table>

<?
	$f->closeForm();
	
	if ($_POST["ok"]) {
		
		$qry="insert into m_telp_pejabat
				(nama,nik,jabatan,loker,kantor,hp,flexi,area)
				values(
				'".$_POST["tNama"]."',
				'".$_POST["tNIK"]."',
				'".$_POST["tJabatan"]."',
				'".$_POST["tLoker"]."',
				'".$_POST["tKantor"]."',
				'".$_POST["tHP"]."',
				'".$_POST["tFlexi"]."',
				'".$_POST["tArea"]."'
				)";
		
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Edit Telp gagal. Silahkan hubungi Administrator !');
			$ora->logoff();
		} else {
			parent_opener_submit();
			$ora->logoff();
			close();
		}
	}
	
	
?>
</body>
</html>
 