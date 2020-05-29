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
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
<script language="javascript" src="<? echo $bit_app["path_url"]?>/bit_mod/mod_js.js" type="text/javascript"></script>
<script>
	function validate() {
		if (document.forms[0].tInput.value=='') {
			alert('Judul silahkan diisi terlebih dahulu !');
			document.forms[0].tInput.focus();
			return false;
		}
		
		if (document.forms[0].tFile1.value!='') {
			if (checkFileExtensionImage(document.forms[0].tFile1)!=1) {
				alert(checkFileExtensionImage(document.forms[0].tFile1)); 
				return false;
			}
		}	
		
		if (document.forms[0].tFile2.value!='') {
			if (checkFileExtensionImage(document.forms[0].tFile2)!=1) {
				alert(checkFileExtensionImage(document.forms[0].tFile2)); 
				return false;
			}
		}	
		
		if (document.forms[0].tFile3.value!='') {
			if (checkFileExtensionImage(document.forms[0].tFile3)!=1) {
				alert(checkFileExtensionImage(document.forms[0].tFile3)); 
				return false;
			}
		}	
		
		if (document.forms[0].tFile4.value!='') {
			if (checkFileExtensionImage(document.forms[0].tFile4)!=1) {
				alert(checkFileExtensionImage(document.forms[0].tFile4)); 
				return false;
			}
		}	
		
		if (document.forms[0].tFile5.value!='') {
			if (checkFileExtensionImage(document.forms[0].tFile5)!=1) {
				alert(checkFileExtensionImage(document.forms[0].tFile5)); 
				return false;
			}
		}	
		
		return true;
	}
</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0">
<?
	
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
?>
<table width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitle2("Upload Foto");?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?
			if ($_POST["hFoto"]) {
				$qry="delete from m_foto_detail where id=".$_POST["hFoto"];
				$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			}
		
			$qry="select * from m_foto where id=".$_GET["id"];
			$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			
			if (!$_POST["ok"]) {
				$_POST["tInput"]=$rs->value[1]["title"];
				$_POST["fck"]=$rs->value[1]["description"];
			}
			
			$qry="select foto_name,id from m_foto_detail where foto_id=".$_GET["id"];
			$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			
			echo "<table border=0 width='100%'>";
			for ($j=1;$j<=$rs->jumrec;$j++) {
				if ($j%5==1)
					echo "<tr>";
					
				echo "<td align='center'>";
				echo "<table>";
				echo "<tr><td>";
				echo "<a  target='_blank' href='".$bit_app["folder_url"].$rs->value[$j]["foto_name"]."'><img class='bit_image' class='bit_foto_image' width='40' src='".$bit_app["folder_url"].$rs->value[$j]["foto_name"]."'></a>";
				echo "</td></tr>";
				echo "<tr><td align='center'>";
				echo "<img style='cursor:pointer' src='".$bit_app["path_url"]."/bit_images/hapus.png' onClick=\"var ans;ans=confirm('Anda yakin untuk menghapus Foto ?'); if (ans) {document.forms[0].hFoto.value='".$rs->value[$j]["id"]."';document.forms[0].submit();}\">";
				echo "</td></tr></table>";
				echo "</td>";
				
				if ($j%5==0)
					echo "</tr>";
			}
			echo "</table>";
			?>
		</td>
	</tr>
	<tr>
		<td valign="top">Foto</td>
		<td>
		<? $f->file("tFile1","tFile1","browse","inputBox",50,255,"
				if (checkFileExtensionImage(this)!=1) {
					alert(checkFileExtensionImage(this));
				}")?>
		<? $f->file("tFile2","tFile2","browse","inputBox",50,255,"
				if (checkFileExtensionImage(this)!=1) {
					alert(checkFileExtensionImage(this));
				}")?>
		<? $f->file("tFile3","tFile3","browse","inputBox",50,255,"
				if (checkFileExtensionImage(this)!=1) {
					alert(checkFileExtensionImage(this));
				}")?>
		<? $f->file("tFile4","tFile4","browse","inputBox",50,255,"
				if (checkFileExtensionImage(this)!=1) {
					alert(checkFileExtensionImage(this));
				}")?>
		<? $f->file("tFile5","tFile5","browse","inputBox",50,255,"
				if (checkFileExtensionImage(this)!=1) {
					alert(checkFileExtensionImage(this));
				}")?>
		<input type="hidden" name="MAX_FILE_SIZE" value="5M" />
		</td>
	</tr>
	<tr>
		<td width="30%" valign="top">Judul *</td>
		<td>
			<? 
				$f->textbox("tInput","tInput",$_POST["tInput"],"inputBox",40,255);
			?>
			
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
			<hr class="article_layout_hr">
			<b>Ket :</b>
			Field yang bertanda (*) harus diisi.
			<hr class="article_layout_hr">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<? $f->submit("ok","simpan","button")?>
			<? $f->button("reset","Close","button","window.close()")?>
		</td>
	</tr>
</table>
<?
$qry="select date_format(updated_date,'%W, %d/%m/%Y %h:%i') updated_date,updated_by,keterangan,status from h_foto where foto_id=".$_GET["id"]." order by id asc";
$rsHistory=$ora->sql_fetch($qry,$bit_app["db"]);
if ($rsHistory->jumrec>=1) { 
?>
<table border="0" cellpadding="3" cellspacing="1" width="100%">
<tr>
	<th colspan="2" class="af_th" align="left"><? echo setTitle2("History Workflow");?></th>
</tr>
<?
$dtUser=getUserDB();
for ($ii=1;$ii<=$rsHistory->jumrec;$ii++) {
	echo "<tr>";
	echo "<td class='row_line'>
				<table cellpadding=0 cellspacing=3 cellpadding=0><tr><td>";
				echo "<a href='".$dtUser[$rsHistory->value[$ii]["updated_by"]][3]."' target='_blank'>
				<img 
					class='bit_image'
					src='".$dtUser[$rsHistory->value[$ii]["updated_by"]][3]."'
					width='50'
					alt='".$dtUser[$rsHistory->value[$ii]["updated_by"]][3]."' 
					title='".$dtUser[$rsHistory->value[$ii]["updated_by"]][3]."' 
					border=0 /></a>";
				echo "</td><td valign='top'><span class='infoLogin'>".$dtUser[$rsHistory->value[$ii]["updated_by"]][5]."</span><br><span class='bit_row_date'>".$rsHistory->value[$ii]["updated_date"]."</span><br><span class='bit_row_date'>Status : </span><b><span class='bit_row_date'>".getStatus($rsHistory->value[$ii]["status"])."</span></b></td></tr></table>
				<br>
				<span class='af_content'>".nl2br($rsHistory->value[$ii]["keterangan"])."</span>
	</td>";
	echo "</tr>";
}
?>
</table>
<? } ?>

<input type="hidden" name="hFoto" />
<?
	$f->closeForm();
	
	if ($_POST["ok"]) {
		$sValue = escape(stripslashes( $_POST['fck']));
		
		$qry="update m_foto
				set 
					description='".$sValue."',
					updated_date=sysdate(),
					updated_by='".getUserID()."',
					title='".$_POST["tInput"]."'
				where
					id=".$_GET["id"];
				
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Update Foto Gagal. Silahkan hubungi administrator !');
			$ora->logoff();
		} else {
			for ($i=1;$i<=5;$i++) {
				$file=do_upload_img("tFile".$i);
				if ($file) {
					$qry="insert into m_foto_detail
							(foto_id,foto_name,updated_date,updated_by)
							values(
							'".$_GET["id"]."',
							'".$file."',
							sysdate(),
							'".getUserID()."'
							)";
					$ora->sql_no_fetch($qry,$bit_app["db"]);
				}
			}
			
			$qry="insert into h_foto(foto_id,keterangan,updated_date,updated_by,status)
					values(".$_GET["id"].",'Edit Foto',sysdate(),'".getUserID()."',4)";
			$ora->sql_no_fetch($qry,$bit_app["db"]);
			
			$ora->logoff();
			parent_opener_submit();
			close();
		}
	}
?>
</body>
</html>
 