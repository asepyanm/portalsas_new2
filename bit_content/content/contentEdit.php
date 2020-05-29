<?
	session_start();
	include_once("../../bit_config.php");
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
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	$qry="select content_name,category_id,content,date_format(expired,'%Y-%m-%d'),headline,image,tag from m_contents where content_id=".$_GET["id"];
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	
	if (!$_POST["ok"]) {
		$_POST["tInput"][0]=$rs->value[1][1];
		$_POST["tInput"][1]=$rs->value[1][2];
		$_POST["fck"]=$rs->value[1][3];
		$_POST["tTag"]=$rs->value[1][7];
		$_POST["image"]=$rs->value[1][6];
	}		
		
			
?>
<table width="100%">
	<tr>
		<td colspan="2">
			<? echo setTitle2("Edit Content");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Judul (*)</td>
		<td><? $f->textbox("tInput[]","tInput",$_POST["tInput"][0],"inputBox",50,255)?></td>
	</tr>
	<tr>
		<td valign="top">Kategori</td>
		<td>
		<?
			$qry="select category_id,category_name from p_category";
			$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			echo "<select class='inputBox' name='slcategory'>";
			for ($i=1;$i<=$rs->jumrec;$i++) {
				if ($rs->value[$i][1]==$_POST["tInput"][1])
					echo "<option selected value='".$rs->value[$i][1]."'>".$rs->value[$i][2]."</option>";
				else
					echo "<option value='".$rs->value[$i][1]."'>".$rs->value[$i][2]."</option>";
			}
			echo "</select>";
		?>
		</td>
	</tr>
	<tr>
		<td valign="top">Gambar</td>
		<td>
			<? 
				if ($_POST["image"]) 
					echo "<img src='".$bit_app["folder_url"]."/".$_POST["image"]."' width=80 height=50>";
			?>
			<? $f->file("userfile","userfile","browse","inputBox",50,255)?>
		</td>
	</tr>
	<tr>
		<td colspan="2" height="100%">
		<?
			$fc=new FCKeditor("fck");
			$fc->ToolbarSet="Basic";
			$fc->Value=$_POST["fck"];
			$fc->Height='280';
			$fc->create();
		?>
		</td>
	</tr>
	<tr>
		<td width="30%">Kata Kunci</td>
		<td><? $f->textbox("tTag","tTag",$_POST["tTag"],"inputBox",50,255)?></td>
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
			<? $f->button("reset","Close","button","window.close()")?>
		</td>
	</tr>
</table>
<?
	$f->hidden("img","img",$img);
	$f->closeForm();
	
	if ($_POST["ok"]) {
		$sValue = str_replace("'","''",stripslashes( $_POST['fck'] )) ;
		
		if ($_FILES["userfile"]["tmp_name"]!="") {
			$img=do_upload("userfile");
			$tm="image='".$img."',";
		}
			
		$qry="update m_contents
				set 
					content_name='".$_POST["tInput"][0]."',
					content='".$sValue."',
					$tm
					category_id='".$_POST["slcategory"]."',
					updated_by='".getUserID()."',
					tag='".$_POST["tTag"]."'
				where content_id=".$_GET["id"];
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Edit News gagal. Silahkan hubungi Administrator !');
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
 