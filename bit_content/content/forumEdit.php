<?
	session_start();
	include_once("../../bit_config.php");
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/bit_css.css">
<?
	getCalendarModule();
?>
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
	
	$qry="select title,content,category_id from m_forum where id=".$_GET["id"];
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	
	if (!$_POST["ok"]) {
		$_POST["tInput"][0]=$rs->value[1][1];
		$_POST["slcategory"]=$rs->value[1][3];
		$_POST["fck"]=$rs->value[1][2];
	}		
?>
<table width="100%" border="0">
	<tr>
		<td colspan="2">
			<? echo setTitleEdit("Edit Forum");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Judul (*)</td>
		<td><? $f->textbox("tInput[]","tInput",$_POST["tInput"][0],"inputBox",50,50)?></td>
	</tr>
	<tr>
		<td valign="top">Kategori</td>
		<td>
		<?
			$qry="select id,name from p_forum_category";
			$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			echo "<select class='inputBox' name='slcategory'>";
			for ($i=1;$i<=$rs->jumrec;$i++) {
				echo "<option value='".$rs->value[$i][1]."'>".$rs->value[$i][2]."</option>";
			}
			echo "</select>";
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2" height="100%">
		<?
			$fc=new FCKeditor("fck");
			$fc->ToolbarSet="Basic";
			$fc->Height='320';
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
			<? $f->submit("ok","Simpan","button")?>
			<? $f->button("button","close","button","window.close()")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
	
	if ($_POST["ok"]) {
		
		$sValue = str_replace("'","''",stripslashes( $_POST['fck'] )) ;
		
		$qry="update m_forum
				
				set 
					title='".$_POST["tInput"][0]."',
					content='".$sValue."',
					category_id='".$_POST["slcategory"]."',
					updated_date=sysdate(),
					updated_by='".getUserID()."'
				where
					id=".$_GET["id"];
		
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Edit Forum gagal!');
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
 