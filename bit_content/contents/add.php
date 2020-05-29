<?php
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
<script language="javascript" src="<? echo $bit_app["path_url"]?>/bit_mod/mod_js.js" type="text/javascript"></script>
<script>
	function validate() {
		if (document.forms[0].tInput[0].value=='') {
			alert('Judul silahkan diisi terlebih dahulu !');
			document.forms[0].tInput[0].focus();
			return false;
		}
		
		if (document.forms[0].userfile.value!='') {
			if (checkFileExtensionImage(document.forms[0].userfile)!=1) {
				alert(checkFileExtensionImage(document.forms[0].userfile)); 
				return false;
			}
		}	
		
		var text=FCKeditorAPI.GetInstance("fck").GetXHTML(true);
		FCKeditorAPI.GetInstance("fck").SetHTML(CleanWord(text));
		return true;
	}
</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput[0].focus()">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
?>
<table width="95%" border="0" class="table">
	<tr>
		<td colspan="2">
			<? echo setTitle2("Tambah News");?>
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
				if ($_GET["cat_id"]==$rs->value[$i][1])
					echo "<option value='".$rs->value[$i][1]."' selected>".$rs->value[$i][2]."</option>";
				else
					echo "<option value='".$rs->value[$i][1]."'>".$rs->value[$i][2]."</option>";
			}
			echo "</select>";
		?>
		</td>
	</tr>
	<tr>
		<td>Gambar</td>
		<td>
			<? $f->file("userfile","userfile","browse","inputBox",50,255,"
				if (checkFileExtensionImage(this)!=1) {
					alert(checkFileExtensionImage(this));
				}")?>
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
		<td width="30%">Kata Kunci</td>
		<td><? $f->textbox("tInput[]","tInput",$_POST["tInput"][1],"inputBox",50,255)?></td>
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
			<? $f->submit("bOKContent","Simpan","button")?>
			<? $f->button("reset","Close","button","parent.$.fn.colorbox.close()")?>
		</td>
	</tr>
</table>
<?
	$f->hidden("img","img",$img);
	$f->closeForm();
	
	if ($_POST["bOKContent"]) {
		
		$sValue = escape(stripslashes( $_POST['fck']));
		
		$img=do_upload_img("userfile");
		
		#Get Publishers
		$qry="select nik from p_publisher_news
				where 
					loker2='".getUserLoker()."'";
		$rsPublisher=$ora->sql_fetch($qry,$bit_app["db"]);
		
		$qry="insert into m_contents
				(title,content,category_id,publish_flag,created_date,created_by,image,tag,publisher,
					created_by_info,status)
				values(
				'".$_POST["tInput"][0]."',
				'".$sValue."',
				'".$_POST["slcategory"]."',
				0,
				sysdate(),
				'".getUserID()."',
				'".$img."',
				'".$_POST["tInput"][1]."',
				'".$rsPublisher->value[1][1]."',
				'".getUser(getUserID())."',
				1
				)";
		
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Tambah News gagal!');
		} else {
			#Mail
			$dtPublisher=explode(",",$rsPublisher->value[1][1]);
			for ($i=0;$i<count($dtPublisher);$i++) {
				$to=$dtPublisher[$i]."@telkom.co.id";
				$subject="[ News Web Infratel ] Request Approved : ".$_POST["tInput"][0];
				$info["author"]=getUser(getUserID());
				$info["judul"]=$_POST["tInput"][0];
				send_email("submit",$to,$subject,$info,"News");
			}
			
			$qry="select last_insert_id() id";
			$rsID=$ora->sql_fetch($qry,$bit_app["db"]);
			
			$qry="insert into h_contents(content_id,keterangan,updated_date,updated_by,status)
					values(".$rsID->value[1][1].",'Submit News',sysdate(),'".getUserID()."',3)";
			$ora->sql_no_fetch($qry,$bit_app["db"]);
			
			$ora->logoff();
			//exit();
			refresh_parent('../../home.php');
		}
	}
	
	$ora->logoff();
?>
</body>
</html>
 