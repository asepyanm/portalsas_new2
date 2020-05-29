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
			<? echo setTitleAdd("Upload Dokumen");?>
		</td>
	</tr>
	<tr>
		<td valign="top">Parent</td>
		<td>
		<?
			$qry="select * from m_doc_folder";
			echo "<select class='inputBox' name='slParent'>";
			echo loop(0);
			echo "</select>";
		?>
		</td>
	</tr>
	<tr>
		<td valign="top">Dokumen </td>
		<td>
		<? $f->file("tFile","tFile","browse","inputBox",50,255)?>
		</td>
	</tr>
	<tr>
		<td width="30%" valign="top">Keterangan</td>
		<td>
			<? 
				$f->textarea("tInput","tInput",$_POST["tInput"],"inputBox",4,50);
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
			<? $f->button("button","Close","button","window.close()")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
	
	if ($_POST["ok"]) {
		
		$img1=do_upload("tFile");
		$qry="insert into m_doc_file
				(file_desc,file_name,folder_id,updated_date,updated_by)
				values(
				'".$_POST["tInput"]."',
				'".$img1."',
				'".$_POST["slParent"]."',
				sysdate(),
				'".$_SESSION["userid_portal"]."'
				)";
				
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Upload File Gagal. Silahkan hubungi administrator !');
		} else {
			alert('Upload File berhasil !');
			parent_opener_submit();
			close();
		}
	}
	
	$ora->logoff();
	
?>
</body>
</html>
 