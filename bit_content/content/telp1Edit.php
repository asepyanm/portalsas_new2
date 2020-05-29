<?
	session_start();
	include_once("../../bit_config.php");
?>
<html>
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tNama.focus()">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
	$qry="select * from m_telp_pejabat where id=".$_GET["id"];
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	
	if (!$_POST["ok"]) {
		$_POST["tNama"]=$rs->value[1][2];
		$_POST["tNIK"]=$rs->value[1][3];
		$_POST["tJabatan"]=$rs->value[1][4];
		$_POST["tLoker"]=$rs->value[1][5];
		$_POST["tKantor"]=$rs->value[1][6];
		$_POST["tHP"]=$rs->value[1][7];
		$_POST["tFlexi"]=$rs->value[1][8];
		$_POST["tArea"]=$rs->value[1][9];
	}			
?>
<table width="100%">
	<tr>
		<td colspan="2">
			<? echo setTitleEdit("Edit Telp");?>
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
		$sValue = escape(stripslashes( $_POST['fck']));
		
		$qry="update m_telp_pejabat
				set 
					nama='".$_POST["tNama"]."',
					nik='".$_POST["tNIK"]."',
					jabatan='".$_POST["tJabatan"]."',
					loker='".$_POST["tLoker"]."',
					kantor='".$_POST["tKantor"]."',
					hp='".$_POST["tHP"]."',
					flexi='".$_POST["tFlexi"]."',
					area='".$_POST["tArea"]."'
				where id=".$_GET["id"];
		
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
 