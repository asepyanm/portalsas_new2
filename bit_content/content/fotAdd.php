<?php
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
</head>
<body topmargin="0" leftmargin="0" rightmargin="0">
<?
	
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data");
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
?>
<table width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitleAdd("Upload Foto");?>
		</td>
	</tr>
	<tr>
		<td valign="top">Foto (Maks 200 KB)</td>
		<td>
		<? $f->file("tFile1","tFile1","browse","inputBox",50,255)?>
		<? $f->file("tFile2","tFile2","browse","inputBox",50,255)?>
		<? $f->file("tFile3","tFile3","browse","inputBox",50,255)?>
		<? $f->file("tFile4","tFile4","browse","inputBox",50,255)?>
		<? $f->file("tFile5","tFile5","browse","inputBox",50,255)?>
		<input type="hidden" name="MAX_FILE_SIZE" value="5M" />
		</td>
	</tr>
	<tr>
		<td width="30%" valign="top">Judul</td>
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
	$f->closeForm();
	
	if ($_POST["ok"]) {
		
		$sValue = escape(stripslashes( $_POST['fck']));
		
		$qry="insert into m_foto
				(description,updated_date,created_date,updated_by,title)
				values(
				'".$sValue."',
				sysdate(),
				sysdate(),
				'".getUserID()."',
				'".$_POST["tInput"]."'
				)";
				
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Upload Foto Gagal. Silahkan hubungi administrator !');
			$ora->logoff();
		} else {
			
			$qry="select last_insert_id() id";
			$rsID=$ora->sql_fetch($qry,$bit_app["db"]);
			
			for ($i=1;$i<=5;$i++) {
				$file=do_upload_img("tFile".$i);
				if ($file) {
					$qry="insert into m_foto_detail
							(foto_id,foto_name,updated_date,updated_by)
							values(
							'".$rsID->value[1][1]."',
							'".$file."',
							sysdate(),
							'".getUserID()."'
							)";
					$ora->sql_no_fetch($qry,$bit_app["db"]);
				}
			}
			
			$ora->logoff();
			parent_opener_submit();
			close();
		}
	}
?>
</body>
</html>
 