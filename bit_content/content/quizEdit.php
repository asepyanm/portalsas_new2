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
			alert('Judul silahkan diisi terlebih dahulu !');
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
	
	
	if ($_POST["ok"]) {
		$sValue = str_replace("'","''",stripslashes( $_POST['fck'] )) ;
		$qry="update m_quiz
				set judul='".$_POST["tInput"][0]."',
					content='".$sValue."',
					updated_date=sysdate(),
					updated_by='".getUserID()."'
				where
					id=".$_GET["id"];
		
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Update Quiz gagal, silahkan hubungi admin!');
			$ora->logoff();
		} else {
			$qry="delete from m_quiz_answer where forum_id=".$_GET["id"];
			$ora->sql_no_fetch($qry,$bit_app["db"]);
			
			while (list($k,$v)=each($_POST["tPilihan"])) {
				$qry="insert into m_quiz_answer(forum_id,jawaban)
						values('".$_GET["id"]."','".$v."')";
				$ora->sql_no_fetch($qry,$bit_app["db"]);
			}
			
			$ora->logoff();
			parent_opener_submit();
			close();
		}
	}
	
	$qry="select judul,content from m_quiz where id=".$_GET["id"];
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	
	if (!$_POST["ok"]) {
		$_POST["tInput"][0]=$rs->value[1][1];
		$_POST["fck"]=$rs->value[1][2];
	}		
?>
<table width="100%" border="0">
	<tr>
		<td colspan="2">
			<? echo setTitle2("Edit Quiz");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Judul (*)</td>
		<td><? $f->textbox("tInput[]","tInput",$_POST["tInput"][0],"inputBox",50,255)?></td>
	</tr>
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
	<tr>
		<td colspan="2" height="100%" align="center" valign="top">
		<?
			$qry="select * from m_quiz_answer where forum_id=".$_GET["id"];
			$rsPilihan=$ora->sql_fetch($qry,$bit_app["db"]);
			for ($i=1;$i<=6;$i++) {
				$_POST["tPilihan"][$i-1]=$rsPilihan->value[$i]["jawaban"];
			}
		?>
		<table>
			<tr>
				<th class="listTableHead">Pilihan</th>
			</tr>
			<tr>
				<td><? $f->textbox("tPilihan[]","tPilihan",$_POST["tPilihan"][0],"inputBox",70,255)?></td>
			</tr>
			<tr>
				<td><? $f->textbox("tPilihan[]","tPilihan",$_POST["tPilihan"][1],"inputBox",70,255)?></td>
			</tr>
			<tr>
				<td><? $f->textbox("tPilihan[]","tPilihan",$_POST["tPilihan"][2],"inputBox",70,255)?></td>
			</tr>
			<tr>
				<td><? $f->textbox("tPilihan[]","tPilihan",$_POST["tPilihan"][3],"inputBox",70,255)?></td>
			</tr>
			<tr>
				<td><? $f->textbox("tPilihan[]","tPilihan",$_POST["tPilihan"][4],"inputBox",70,255)?></td>
			</tr>
			<tr>
				<td><? $f->textbox("tPilihan[]","tPilihan",$_POST["tPilihan"][5],"inputBox",70,255)?></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
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
</table>
<?
	$f->closeForm();
?>
</body>
</html>
 