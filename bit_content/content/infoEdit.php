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
<script>
	function validate() {
		if (document.forms[0].tInput.value=='') {
			alert('Judul silahkan diisi terlebih dahulu !');
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
	
	
	$qry="select title,content,image from m_info where id=".$_GET["id"];
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	if (!$_POST["bOKContent"]) {
		$_POST["tInput"][0]=$rs->value[1][1];
		$_POST["fck"]=$rs->value[1][2];
		$_POST["image"]=$rs->value[1][3];
	}		
?>
<table width="100%" border="0">
	<tr>
		<td colspan="2">
			<? echo setTitle2("Edit Info");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Judul (*)</td>
		<td><? $f->textbox("tInput[]","tInput",$_POST["tInput"][0],"inputBox",50,255)?></td>
	</tr>
	<tr>
		<td>Gambar</td>
		<td>
			<? 
				if ($_POST["image"]) 
					echo "<img src='".$bit_app["folder_url"]."/".$_POST["image"]."' width=80 height=50>";
			?>
			<? $f->file("userfile1","userfile1","browse","inputBox",50,255)?>
		</td>
	</tr>
	<tr>
		<td colspan="2" height="100%">
		<?
			$fc=new FCKeditor("fck");
			$fc->ToolbarSet="Basic";
			$fc->Height='220';
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
		<td colspan="2" align="right">
			<? $f->submit("bOKContent","Simpan","button")?>
			<? $f->button("button","close","button","window.close()")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
	
	if ($_POST["bOKContent"]) {
		
		$sValue = str_replace("'","''",stripslashes( $_POST['fck'] )) ;
		
		$img=do_upload("userfile1");
		if (!img) {
			$tm="image='".$img."',";
		}
		
		$qry="update m_info
				set 
					$tm
					title='".$_POST["tInput"][0]."',
					content='".$sValue."'
				where id=".$_GET["id"];
		
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Edit Info gagal, mohon hubungi admin!');
			$ora->logoff();
		} else {
			$ora->logoff();
			parent_opener_submit();
			close();
		}
	}
?>
</body>
</html>
 