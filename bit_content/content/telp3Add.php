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
		<td valign="top">Nama Instansi</td>
		<td><? $f->textbox("tNama","tNama",$_POST["tNama"],"inputBox",50,255)?>&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">Alamat </td>
		<td><? $f->textbox("tAlamat","tAlamat",$_POST["tAlamat"],"inputBox",50,255)?>&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">Telepon Kantor</td>
		<td><? $f->textbox("tKantor","tKantor",$_POST["tKantor"],"inputBox",20,255)?>&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">HP</td>
		<td><? $f->textbox("tHP","tHP",$_POST["tHP"],"inputBox",20,255)?>&nbsp;</td>
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
		
		$qry="insert into m_telp_pejabat_penting
				(nama,alamat,kantor,hp)
				values(
				'".$_POST["tNama"]."',
				'".$_POST["tAlamat"]."',
				'".$_POST["tKantor"]."',
				'".$_POST["tHP"]."'
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
 