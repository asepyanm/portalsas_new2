<?
	include_once("../../bit_config.php");
?>
<html>
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
<?
	getCalendarModule();
?>
<script>
	function validate() {
		
		if (document.forms[0].tInput.value=='') {
			alert('Nama Profile silahkan diisi terlebih dahulu !');
				document.forms[0].tInput.focus();
				return false;
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
	
		
	$qry="select profile_name,akses from p_profile where profile_id=".$_GET["id"];
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	
	if (!$_POST["tInput"][0])
		$_POST["tInput"][0]=$rs->value[1][1];
	
	$arrAkses = explode(",",$rs->value[1][2]);	
	for ($i=0;$i<count($arrAkses);$i++) {
		$aksesMenu[$arrAkses[$i]]=1;
	}

?>
<table  width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitleEdit("Edit Profile");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Nama Profile (*)</td>
		<td>
			<? 
					$f->textbox("tInput[]","tInput",$_POST["tInput"][0],"inputBox",50,50);
			?>
		</td>
	</tr>
	<tr>
		<td valign="top">Menu</td>
		<td>
			<?
				$arrMenu=array(
								1=>"Home",
								2=>"News",
								3=>"Menu",
								4=>"Flow",
								6=>"Module",
								8=>"User",
								9=>"Profile"
								);
				$arrSubMenu=array(
								2=>array(
											21=>"Kategori",
											22=>"Daftar News"
										),
								5=>array(
											51=>"Kategori",
											52=>"Daftar News"
										),
								4=>array(
											42=>"News / Video",
										),
								6=>array(
											66=>"Movie",
											69=>"Running Text",
											62=>"Foto",
											65=>"Running Text Go Green",
											61=>"Nomor Telepon",
											64=>"Data Personal",
											63=>"Call Sign",
										),
								);
				
				while (list($k,$v)=each($arrMenu)) {
					if ($aksesMenu[$k])
						echo "<input type='checkbox' value='$k' name='cMenu[]' id='cMenu' checked>$v <br />";
					else
						echo "<input type='checkbox' value='$k' name='cMenu[]' id='cMenu'>$v <br />";
					
					
					while (list($k1,$v1)=each($arrSubMenu[$k])) {
						if ($aksesMenu[$k1])
							echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' value='$k1' name='cMenu[]' id='cMenu' checked>$v1 <br />";
						else
							echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' value='$k1' name='cMenu[]' id='cMenu'>$v1 <br />";
						
					}	
				}
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
		for ($i=0;$i<count($_POST["cMenu"]);$i++) {
			$akses .=$_POST["cMenu"][$i].",";
		}
		$akses = substr($akses,0,strlen($akses)-1);
		
		$qry="update
					p_profile
				set	
					profile_name='".$_POST["tInput"][0]."',
					akses = '".$akses."'
				where
					profile_id=".(int)$_GET["id"];
				
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Edit Profile Gagal. Silahkan hubungi administrator !');
		} else {
			alert('Edit Profile berhasil !');
			parent_opener_submit();
			close();
		}
	}
	
	$ora->logoff();
	
?>
</body>
</html>
 