<?
	session_start();
	include_once("../../bit_config.php");
?>
<html>
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
<script type="text/javascript" src="../../bit_mod/mod_js.js"></script>
<script>
	function validate() {
		
		if (document.forms[0].tMulai.value=='') {
			alert('Tanggal Mulai mohon diisi !'); 
			return false;
		}
		
		if (document.forms[0].tSelesai.value=='') {
			alert('Tanggal Selesai mohon diisi !'); 
			return false;
		}
		
		return true;
	}
</script>
<?
	getCalendarModule();
?>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput.focus()">
<?
	
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
	$qry="select keterangan,date_format(tgl_mulai,'%Y-%m-%d'),date_format(tgl_selesai,'%Y-%m-%d') from m_calender where id=".$_GET["id"];
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	if (!$_POST["ok"]) {
		$_POST["tInput"]=$rs->value[1][1];
		$_POST["tMulai"]=$rs->value[1][2];
		$_POST["tSelesai"]=$rs->value[1][3];
	}
	
?>
<table width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitleAdd("Edit Event");?>
		</td>
	</tr>
	<tr>
		<td valign="top">Tgl. Mulai </td>
		<td><? $f->tanggal("tMulai","tMulai",$_POST["tMulai"],"inputBox",20,20)?>&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">Tgl. Selesai </td>
		<td><? $f->tanggal("tSelesai","tSelesai",$_POST["tSelesai"],"inputBox",20,20)?>&nbsp;</td>
	</tr>
	<tr>
		<td width="30%" valign="top">Event</td>
		<td>
			<? 
				$f->textarea("tInput","tInput",$_POST["tInput"],"inputBox",8,50);
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
			<? $f->submit("ok","simpan","button")?>
			<? $f->button("button","close","button","window.close()")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
	
	if ($_POST["ok"]) {
		
		$img1=do_upload("tFile");
		$img2=do_upload("tFileImage");
		$qry="update m_calender
				set 
					keterangan = '".$_POST["tInput"]."',
					tgl_mulai ='".$_POST["tMulai"]."',
					tgl_selesai = '".$_POST["tSelesai"]."',
					updated_date = sysdate(),
					updated_by = '".$_SESSION["userid_portal"]."'
				where 
					id = ".$_GET["id"];
				
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Edit Event Gagal. Silahkan hubungi administrator !');
		} else {
			alert('Edit Event berhasil !');
			parent_opener_submit();
			close();
		}
	}
	
	$ora->logoff();
	
?>
</body>
</html>
 