<?
	session_start();
	include_once("../../bit_config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
<script type="text/javascript" src="../../bit_mod/mod_js.js"></script>
<script>
	function validate() {
		
		if (document.forms[0].tJudul.value=='') {
			alert('Judul silahkan diisi terlebih dahulu !');
			document.forms[0].tJudul.focus();
			return false;
		}
		
		if (document.forms[0].tFile.value=='') {
			alert('File Video mohon di-browse terlebih dahulu !'); 
			return false;
		}
		if (document.forms[0].tFile.value!='') {
			if (checkFileExtension(document.forms[0].tFile)!=1) {
				alert(checkFileExtension(document.forms[0].tFile)); 
				return false;
			}
		}
		
		if (document.forms[0].tFileImage.value!='') {
			if (checkFileExtensionImage(document.forms[0].tFileImage)!=1) {
				alert(checkFileExtensionImage(document.forms[0].tFileImage)); 
				return false;
			}
		}
		
		return true;
	}
</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput.focus()">
<?
	
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
?>
<table width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitle2("Upload Video");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Judul *</td>
		<td><? $f->textbox("tJudul","tJudul",$_POST["tJudul"],"inputBox",50,255)?></td>
	</tr>
	<tr>
		<td valign="top">Video <b>*</b>  (Maks 55 MB) </td>
		<td>
		<? $f->file("tFile","tFile","browse","inputBox",50,255,"
				if (checkFileExtension(this)!=1) {
					alert(checkFileExtension(this))
				}")?>
		</td>
	</tr>
	<tr>
		<td valign="top">Gambar  </td>
		<td>
		<? $f->file("tFileImage","tFileImage","browse","inputBox",50,255,"
				if (checkFileExtensionImage(this)!=1) {
					alert(checkFileExtensionImage(this));
				}")?>
		</td>
	</tr>
	<tr>
		<td width="30%" valign="top">Keterangan</td>
		<td>
			<? 
				$f->textarea("tInput","tInput",$_POST["tInput"],"inputBox",6,60);
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
			<? $f->button("reset","Close","button","parent.$.fn.colorbox.close()")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
	
	if ($_POST["ok"]) {
		
			
		$ora=new clsMysql;
		$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
		
		$img1=do_upload_limit("tFile",55000000);
		$img2=do_upload_img("tFileImage");
		
		#Get Publishers
		$qry="select nik from p_publisher_news
				where 
					loker2='".getUserLoker()."'";
		$rsPublisher=$ora->sql_fetch($qry,$bit_app["db"]);
		
		$qry="insert into m_video
				(title,keterangan,file,image,created_date,created_by,publish_flag,publisher,created_by_info,status)
				values(
				'".$_POST["tJudul"]."',
				'".$_POST["tInput"]."',
				'".$img1."',
				'".$img2."',
				sysdate(),
				'".getUserID()."',
				0,
				'".$rsPublisher->value[1][1]."',
				'".getUser(getUserID())."',
				1
				)";
				
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Upload Video Gagal. Silahkan hubungi administrator !');
			$ora->logoff();
		} else {
			#Mail
			$dtPublisher=explode(",",$rsPublisher->value[1][1]);
			for ($i=0;$i<count($dtPublisher);$i++) {
				$to=$dtPublisher[$i]."@telkom.co.id";
				$subject="[ Movie Web Infratel ] Request Approved : ".$_POST["tInput"];
				$info["author"]=getUser(getUserID());
				$info["judul"]=$_POST["tInput"];
				send_email("submit",$to,$subject,$info,"Movie");
			}
			
			$qry="select last_insert_id() id";
			$rsID=$ora->sql_fetch($qry,$bit_app["db"]);
			
			$qry="insert into h_video(video_id,keterangan,updated_date,updated_by,status)
					values(".$rsID->value[1][1].",'Submit Video',sysdate(),'".getUserID()."',3)";
			$ora->sql_no_fetch($qry,$bit_app["db"]);
					
			$ora->logoff();
			refresh_parent('../../home.php');
		}
	}
?>
</body>
</html>
 