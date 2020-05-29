<?
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
<script>
	function validate() {
		if (document.forms[0].tInput.value=='') {
			alert('Pesan dipilih terlebih dahulu !');
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
?>
<table width="100%" border="0">
	<tr>
		<td colspan="2">
			<? echo setTitle2("Submit Forum");?>
		</td>
	</tr>
	<tr>
		<td width="30%" valign="top">Pesan</td>
		<td><? $f->textarea("tInput","tInput",$_POST["tInput"],"inputBox",6,60)?></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr class="article_layout_hr">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<? $f->submit("sApr","Submit","button")?>
			<? $f->button("reset","Close","button","window.close()")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
	
	if ($_POST["sApr"]) {
		
		#Get Publishers
		$qry="select nik from p_publisher_news
				where 
					loker2='".getUserLoker()."'";
		$rsPublisher=$ora->sql_fetch($qry,$bit_app["db"]);
		
		$qry="update m_foto
				set status=1
				where
					id=".$_GET["id"];
		
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Submit Foto gagal, mohon hubungi admin !');
			$ora->logoff();
		} else {
			$qry="select title,created_by,publisher from m_foto where id=".$_GET["id"];
			$rsData=$ora->sql_fetch($qry,$bit_app["db"]);
			
			#Mail
			$dtPublisher=explode(",",$rsData->value[1][3]);
			for ($i=0;$i<count($dtPublisher);$i++) {
				$to=$dtPublisher[$i]."@telkom.co.id";
				$subject="[ Foto Web Infratel ] Request Approved : ".$rsData->value[1][1];
				$info["author"]=getUser($rsData->value[1][2]);
				$info["judul"]=$rsData->value[1][1];
				send_email("submit",$to,$subject,$info,"Foto");
			}
				
			$qry="insert into h_foto(foto_id,keterangan,updated_date,updated_by,status)
					values(".$_GET["id"].",'".$_POST["tInput"]."',sysdate(),'".getUserID()."',3)";
			$ora->sql_no_fetch($qry,$bit_app["db"]);
			
			$ora->logoff();
			parent_opener_submit();
			close();
		}
	}
?>
</body>
</html>
 