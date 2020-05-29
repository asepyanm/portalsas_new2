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
<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput.focus()">
<?
	
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
?>
<table width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitleAdd("Tambah Profile");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Profile (*)</td>
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
				$qry="select * from menu_icon order by posisi asc";
				$rs=$ora->sql_fetch($qry,$bit_app["db"]);
				for ($i=1;$i<=$rs->jumrec;$i++) {
					if ($aksesMenu[0][$rs->value[$i]["menu_id"]])
						echo "<input type='checkbox' value='".$rs->value[$i]["menu_id"]."' name='cMenu[]' id='cMenu' checked><b>".$rs->value[$i]["menu_name"]."</b><br />";
					else
						echo "<input type='checkbox' value='".$rs->value[$i]["menu_id"]."' name='cMenu[]' id='cMenu'><b>".$rs->value[$i]["menu_name"]."</b><br />";
					
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
		$akses = substr($akses,0,strlen($akses)-1)."|";
		
		for ($i=0;$i<count($_POST["cMenu1"]);$i++) {
			$akses .=$_POST["cMenu1"][$i].",";
		}
		$akses = substr($akses,0,strlen($akses)-1)."|";
		
		for ($i=0;$i<count($_POST["cMenu2"]);$i++) {
			$akses .=$_POST["cMenu2"][$i].",";
		}
		$akses = substr($akses,0,strlen($akses)-1)."|";
		
		for ($i=0;$i<count($_POST["cMenu3"]);$i++) {
			$akses .=$_POST["cMenu3"][$i].",";
		}
		$akses = substr($akses,0,strlen($akses)-1);
		
		
		$qry="insert into p_profile_icon
				(profile_name,akses)
				values(
				'".$_POST["tInput"][0]."','$akses'
				)";
				
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Tambah Profile Gagal. Silahkan hubungi administrator !');
		} else {
			alert('Tambah Profile  berhasil !');
			parent_opener_submit();
			close();
		}
	}
	
	$ora->logoff();
	
?>
</body>
</html>
 