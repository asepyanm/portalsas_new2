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
<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput[0].focus()">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
?>
<table width="100%">
	<tr>
		<td colspan="2">
			<? echo setTitleAdd("Tambah Running Text");?>
		</td>
	</tr>
	<tr>
		<td valign="top">Tgl. Expired </td>
		<td><? $f->tanggal("tExp","tExp",date("Y-m-d"),"inputBox",20,20)?>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" height="100%">
		<?
			$fc=new FCKeditor("fck");
			$fc->ToolbarSet="Basic";
			$fc->create();
		?>
		</td>
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
	$f->hidden("img","img",$img);
	$f->closeForm();
	
	if ($_POST["ok"]) {
		
		$sValue = escape(stripslashes( $_POST['fck']));
		
		$qry="insert into m_gogreen
				(pengumuman,updated_date,updated_by,expired)
				values(
				'".$sValue."',
				sysdate(),
				'".getUserID()."',
				'".$_POST["tExp"]."'
				)";
		
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Edit Pengumuman gagal. Silahkan hubungi Administrator !');
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
 