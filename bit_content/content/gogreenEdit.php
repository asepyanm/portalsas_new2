<?
	session_start();
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
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput[0].focus()">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
	$ora=new clsMysql;
		$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
		$qry="select pengumuman,date_format(expired,'%Y-%m-%d') from m_gogreen where id=".$_GET["id"];
		$rs=$ora->sql_fetch($qry,$bit_app["db"]);
		
		if (!$_POST["fck"])
			$_POST["fck"]=$rs->value[1][1];
		else
			$_POST["fck"]=stripslashes($_POST["fck"]);
			
		if (!$_POST["tExp"])
			$_POST["tExp"]=$rs->value[1][2];
			
?>
<table width="100%">
	<tr>
		<td colspan="2">
			<? echo setTitleEdit("Edit Running Text");?>
		</td>
	</tr>
	<tr>
		<td valign="top">Tgl. Expired </td>
		<td><? $f->tanggal("tExp","tExp",$_POST["tExp"],"inputBox",20,20)?>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" height="100%">
		<?
			$fc=new FCKeditor("fck");
			$fc->ToolbarSet="Basic";
			$fc->Value=$_POST["fck"];
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
	$f->closeForm();
	
	if ($_POST["ok"]) {
		$sValue = escape(stripslashes( $_POST['fck']));
		
		$qry="update m_gogreen
				set 
					pengumuman='".$sValue."',
					updated_date=sysdate(),
					updated_by='".getUserID()."',
					expired='".$_POST["tExp"]."'
				where id=".$_GET["id"];
		
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
 