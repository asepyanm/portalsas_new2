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
<body topmargin="0" leftmargin="0" rightmargin="0" onload="document.forms[0].tInput.focus();">
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
		<td valign="top">Foto</td>
		<td align="right">
		<script language="javascript">
		function add() {
			var type='file';
		    var numi = document.getElementById('theValue');
		    var num = (document.getElementById('theValue').value - 1)+ 2;
		    numi.value = num;
			//Create an input type dynamically.
			var eldiv = document.createElement("div");
		    
			var element = document.createElement("input");
		    var divIdName = 'tFile'+num;
			//Assign different attributes to the element.
			element.setAttribute("type", type);
			element.setAttribute("name", divIdName);
			element.setAttribute('id',divIdName);
			if (ie)
				element.setAttribute('className','inputBox');
			else
				element.setAttribute('class','inputBox');
			
			element.setAttribute('size',50);
			
			var foo = document.getElementById("myDiv");
			eldiv.appendChild(element);
		 
			//Append the element in page (in span).
			foo.appendChild(eldiv);
		}

		</script>
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
		<div id="myDiv"></div>
		<br />
		<? $f->button("tambah","Tambah","button","add()")?>
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
			<? $f->button("reset","Close","button","parent.$.fn.colorbox.close()")?>
		</td>
	</tr>
</table>
<input type="hidden" id="theValue" name="theValue" value="5" />
		
<?
	$f->closeForm();
	
	if ($_POST["ok"]) {
		
		$sValue = escape(stripslashes( $_POST['fck']));
		
		#Get Publishers
		$qry="select nik from p_publisher_news
				where 
					loker2='".getUserLoker()."'";
		$rsPublisher=$ora->sql_fetch($qry,$bit_app["db"]);
		
		$qry="insert into m_foto
				(description,updated_date,created_by,created_date,updated_by,title,publish_flag,publisher,created_by_info,status)
				values(
				'".$sValue."',
				sysdate(),
				'".getUserID()."',
				sysdate(),
				'".getUserID()."',
				'".$_POST["tInput"]."',
				0,
				'".$rsPublisher->value[1][1]."',
				'".getUser(getUserID())."',
				1
				)";
				
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Upload Foto Gagal. Silahkan hubungi administrator !');
			$ora->logoff();
		} else {
			$qry="select last_insert_id() id";
			$rsID=$ora->sql_fetch($qry,$bit_app["db"]);
			
			for ($i=1;$i<=$_POST["theValue"];$i++) {
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
			
			
			#Mail
			$dtPublisher=explode(",",$rsPublisher->value[1][1]);
			for ($i=0;$i<count($dtPublisher);$i++) {
				$to=$dtPublisher[$i]."@telkom.co.id";
				$subject="[ Foto Web Infratel ] Request Approved : ".$_POST["tInput"];
				$info["author"]=getUser(getUserID());
				$info["judul"]=$_POST["tInput"];
				send_email("submit",$to,$subject,$info,"Foto");
			}

			$qry="select last_insert_id() id";
			$rsID=$ora->sql_fetch($qry,$bit_app["db"]);
			
			$qry="insert into h_foto(foto_id,keterangan,updated_date,updated_by,status)
					values(".$rsID->value[1][1].",'Submit Foto',sysdate(),'".getUserID()."',3)";
			$ora->sql_no_fetch($qry,$bit_app["db"]);
			
			$ora->logoff();
			refresh_parent('../../home.php');
		}
	}
?>
</body>
</html>
 