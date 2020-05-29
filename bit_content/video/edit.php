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
<script type="text/javascript" src="../../bit_third/flowplayer web/anchors_files/flowplayer-3.js"></script>

<script>
	function validate() {
		
		if (document.forms[0].tJudul.value=='') {
			alert('Judul silahkan diisi terlebih dahulu !');
			document.forms[0].tJudul.focus();
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
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
	$qry="select keterangan,file,updated_date,updated_by,image,title from m_video where id=".$_GET["id"];
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	if (!$_POST["ok"]) {
		$_POST["tInput"]=$rs->value[1][1];
		$_POST["tFile"]=$rs->value[1][2];
		$_POST["tJudul"]=$rs->value[1][6];
	}
	
?>
<table width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitle2("Edit Video");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Judul *</td>
		<td><? $f->textbox("tJudul","tJudul",$_POST["tJudul"],"inputBox",50,255)?></td>
	</tr>
	
	<tr>
		<td valign="top">Video </td>
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
			<? $f->button("reset","Close","button","window.close()")?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
		<br />
		<?
		
		if (!$rs->value[1]["image"])
		    $rs->value[1]["image"]="telkom_movie.png";
			
		echo "<a  
			 href='".$bit_app["folder_url"]."/".$rs->value[1]["file"]."'  
			 style='display:block;width:270px;height:160px;z-index:-999'  
			 id='player'> 
			 
			 <img src='".$bit_app["folder_url"]."/".$rs->value[1]["image"]."' alt='Video 1'  width='270px' height='160px' border=0/> 
		</a>";
		
		echo "
			<script>
				flowplayer('player', '".$bit_app["third_url"]."/flowplayer/flowplayer-3.1.5.swf');
			</script>";
		?>
		</td>
	</tr>
</table>
<?
$qry="select date_format(updated_date,'%W, %d/%m/%Y %h:%i') updated_date,updated_by,keterangan,status from h_video where video_id=".$_GET["id"]." order by id asc";
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
<?
	
	$f->closeForm();
	
	if ($_POST["ok"]) {
		
		$img=do_upload_limit("tFile",10000000);
		if ($img)
			$andFile = ", file = '$img'";
		
		$img1=do_upload_img("tFileImage");
		if ($img1)
			$andFile .= ", image = '$img1'";
			
		$qry="update m_video
				set 
					title='".$_POST["tJudul"]."',
					keterangan='".$_POST["tInput"]."',
					updated_date=sysdate(),
					updated_by='".getUserID()."'
					$andFile
				where
					id = ".$_GET["id"];
				
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Edit Video Gagal. Silahkan hubungi administrator !');
			$ora->logoff();
		} else {
			$qry="insert into h_video(video_id,keterangan,updated_date,updated_by,status)
					values(".$_GET["id"].",'Edit Video',sysdate(),'".getUserID()."',4)";
			$ora->sql_no_fetch($qry,$bit_app["db"]);
			
			$ora->logoff();
			parent_opener_submit();
			close();
		}
	}
?>
</body>
</html>
 