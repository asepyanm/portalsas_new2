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
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/bit_css.css">
</head>
<body topmargin="0" leftmargin="0" rightmargin="0">
<?
	
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data");
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
	$qry="select * from m_banner where id=".$_GET["id"];
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	
	if (!$_POST["ok"] && $_POST["hFlag"]==0) {
		if ($rs->value[1]["banner"])
			$_POST["rBanner"]=1;
		else
			$_POST["rBanner"]=2;
			
		$_POST["fck"]=$rs->value[1]["description"];
	}		
?>
<table width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitleEdit("Edit Banner");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Tipe</td>
		<td>
			<?
				if (!$_POST["rBanner"])
					$_POST["rBanner"]=1;
			?>
			<input type="radio" name="rBanner" value="1" <? echo ($_POST["rBanner"]==1?"checked":"")?> onclick="document.forms[0].hFlag.value=1;document.forms[0].submit()" />Flash 
			<input type="radio" name="rBanner" value="2" <? echo ($_POST["rBanner"]==2?"checked":"")?> onclick="document.forms[0].hFlag.value=1;document.forms[0].submit()" />Image
		</td>
	</tr>
	<? if ($_POST["rBanner"]==1) { ?> 
	<tr>
		<td valign="top">Banner (200x300 pixel)</td>
		<td>
		<a href="<? echo $bit_app["folder_url"].$rs->value[1]["banner"]?>" target="_blank"><? echo $rs->value[1]["banner"]?></a>
		<? $f->file("tFile","tFile","browse","inputBox",50,255)?>
		</td>
	</tr>
	<tr>
		<td width="30%">URL</td>
		<td><? $f->textbox("tURL","tURL",$_POST["tURL"],"inputBox",50,50)?></td>
	</tr>
	<? } else { ?>
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
	<? } ?>
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
<input type="hidden" name="hFlag" />
<?
	$f->closeForm();
	
	if ($_POST["ok"]) {
		
		$sValue = stripslashes($_POST['fck']);
		
		$img1=do_upload_limit("tFile",200000);
		if ($img1)
			$fieldBanner="banner='".$img1."',";
			
		if ($_POST["rBanner"]==2) 	
			$fieldBanner="banner='',";
			
		$qry="update m_banner
				set 
					$fieldBanner
					description='".$sValue."',
					updated_date=sysdate(),
					updated_by='".getUserID()."'
				where
					id=".$_GET["id"];
				
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Upload Banner Gagal. Silahkan hubungi administrator !');
			$ora->logoff();
		} else {
			$ora->logoff();
			parent_opener_submit();
			close();
		}
	}
?>
</body>
</html>
 