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
		
		var ans=confirm('Anda yakin untuk melanjutkan ?');
		if (!ans)
			return false;
		
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
			<? echo setTitle2("Approve / Reject Foto");?>
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
			<? $f->submit("sApr","Approve","button")?>
			<? $f->submit("sRej","Reject","button")?>
			<? $f->button("reset","Close","button","window.close()")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
	
	if ($_POST["sRej"]) {
		
		$qry="update m_foto
				set publish_flag=0,status=2,reject_by_info='".getUser(getUserID())."'
				where
					id=".$_GET["id"];
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Reject Foto gagal, mohon hubungi admin !');
			$ora->logoff();
		} else {
			$qry="select title,created_by,publisher from m_foto where id=".$_GET["id"];
			$rsData=$ora->sql_fetch($qry,$bit_app["db"]);
			
			#Mail
			$to=$rsData->value[1][2]."@telkom.co.id";
			$subject="[ Foto Web Infratel ] Rejected : ".$rsData->value[1][1];
			$info["author"]=getUser($rsData->value[1][2]);
			$info["judul"]=$rsData->value[1][1];
			$info["note"]=$_POST["tInput"];
			send_email("approve",$to,$subject,$info,"Foto");
			
			$qry="insert into h_foto(foto_id,keterangan,updated_date,updated_by,status)
					values(".$_GET["id"].",'".$_POST["tInput"]."',sysdate(),'".getUserID()."',2)";
			$ora->sql_no_fetch($qry,$bit_app["db"]);
					
			$ora->logoff();
			parent_opener_submit();
			close();
		}
	}
	
	if ($_POST["sApr"]) {
		
		$qry="update m_foto
				set publish_flag=1,publish_date=sysdate(),status=3,publish_by_info='".getUser(getUserID())."'
				where
					id=".$_GET["id"];
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Approve Foto gagal, mohon hubungi admin !');
			$ora->logoff();
		} else {
			$qry="select title,created_by,publisher,description from m_foto where id=".$_GET["id"];
			$rsData=$ora->sql_fetch($qry,$bit_app["db"]);
			
			#Mail
			$to=$rsData->value[1][2]."@telkom.co.id";
			$subject="[ Foto Web Infratel ] Telah di publish : ".$rsData->value[1][1];
			$info["author"]=getUser($rsData->value[1][2]);
			$info["judul"]=$rsData->value[1][1];
			$info["note"]=$_POST["tInput"];
			send_email("approve",$to,$subject,$info,"Foto");
			
			$to=$bit_app["milis"];
			$subject="[ Foto Web Infratel ] HOT FOTO : ".$rsData->value[1][1];
			$info["author"]=getUser($rsData->value[1][2]);
			$info["judul"]=$rsData->value[1][1];
			$info["description"]=$rsData->value[1][4];
			send_email("milis",$to,$subject,$info,"Foto");
			
			$qry="insert into h_foto(foto_id,keterangan,updated_date,updated_by,status)
					values(".$_GET["id"].",'".$_POST["tInput"]."',sysdate(),'".getUserID()."',1)";
			$ora->sql_no_fetch($qry,$bit_app["db"]);
		
			$ora->logoff();
			parent_opener_submit();
			close();
		}
	}
?>
</body>
</html>
 