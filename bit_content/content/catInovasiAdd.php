<?
	include_once("../../bit_config.php");
?>
<html>
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
<?
	getCalendarModule();
?>
<script>
	function validate() {
		
		if (document.forms[0].tInput.value=='') {
			alert('Nama Kategori silahkan diisi terlebih dahulu !');
				document.forms[0].tInput.focus();
				return false;
		}
		return true;
	}
</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput.focus()">
<?
	
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
?>
<table width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitleAdd("Tambah Kategori Inovasi");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Nama Kategori (*)</td>
		<td>
			<? 
				$f->textbox("tInput[]","tInput",$_POST["tInput"][0],"inputBox",50,50);
			?>
			
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr class="article_layout_hr">
			<b>Ket :</b>
			Field yang bertanda (*) harus diisi.
			<hr class="article_layout_hr">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<? $f->submit("ok","Simpan","button")?>
			<? $f->button("button","Tutup Window","button","window.close()")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
	
	if ($_POST["ok"]) {
		
		$qry="insert into p_category_inovasi
				(category_name)
				values(
				'".$_POST["tInput"][0]."'
				)";
				
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Tambah Kategori Gagal. Silahkan hubungi administrator !');
		} else {
			alert('Tambah Kategori  berhasil !');
			parent_opener_submit();
			close();
		}
	}
	
	$ora->logoff();
	
?>
</body>
</html>
 