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
<script>
	function validate() {
		
		if (document.forms[0].tFolder.value=='') {
			alert('Nama Folder silahkan diisi terlebih dahulu !');
				document.forms[0].tFolder.focus();
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
	
	function loop($id,$space){
	 	global $ora;
		global $bit_app;
		
		$qry="select * from m_doc_folder where parent_id = ".$id;
		$rs=$ora->sql_fetch($qry,$bit_app["db"]);
		if ($rs->jumrec==0)
			return false;
		
		
		for ($i=1;$i<=$rs->jumrec;$i++) {
			
			$space="";
			for ($j=1;$j<=$rs->value[$i]["folder_level"];$j++)
				$space .="...";

			$str .="<option value=".$rs->value[$i]["id"].">".$space." ".$rs->value[$i]["folder_name"]."</option>";
			$qry="select * from m_doc_folder where id = ".$rs->value[$i]["id"];
			$rsC=$ora->sql_fetch($qry,$bit_app["db"]);
			if ($rsC->jumrec==0)
				return false;
			else {
				$str .=loop($rs->value[$i]["id"],$space);
			}
		}
		return $str;
	}
?>
<table width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitleAdd("Tambah Folder");?>
		</td>
	</tr>
	<tr>
		<td valign="top">Parent</td>
		<td>
		<?
			$qry="select * from m_doc_folder";
			echo "<select class='inputBox' name='slParent'>";
			echo "<option value='0'>Root</option>";
			echo loop(0);
			echo "</select>";
		?>
		</td>
	</tr>
	<tr>
		<td width="30%">Folder (*)</td>
		<td>
			<? 
				$f->textbox("tFolder","tFolder",$_POST["tFolder"],"inputBox",50,50);
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
		
		$qry="select (folder_level+1) from m_doc_folder where id = ".$_POST["slParent"];
		$rsLevel=$ora->sql_fetch($qry,$bit_app["db"]);
		
		$qry="insert into m_doc_folder(parent_id,folder_name,updated_date,updated_by,folder_level)
				values(
				'".$_POST["slParent"]."',
				'".$_POST["tFolder"]."',
				sysdate(),
				'".$_SESSION["userid_portal"]."',
				".(int)$rsLevel->value[1][1]."
				)";
				
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Tambah Folder Gagal. Silahkan hubungi administrator !');
		} else {
			alert('Tambah Folder berhasil !');
			parent_opener_submit();
			close();
		}
	}
	
	$ora->logoff();
	
?>
</body>
</html>
 