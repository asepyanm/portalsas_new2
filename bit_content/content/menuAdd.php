<?
	include_once("../../bit_config.php");
?>
<html>
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
<script>
	function validate() {
		
		if (document.forms[0].tInput.value=='') {
			alert('Nama Menu silahkan diisi terlebih dahulu !');
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
			<? echo setTitleAdd("t a m b a h . m e n u");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Nama Menu (*)</td>
		<td>
			<? 
				$f->textbox("tInput[]","tInput",$_POST["tInput"][0],"inputBox",50,255);
			?>
			
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<? 
				if (!$_POST["rdInput"])
					$_POST["rdInput"]=5;
					
				if ($_POST["rdInput"]==1)
					$chk1="checked";
				
				if ($_POST["rdInput"]==2)
					$chk2="checked";
				
				if ($_POST["rdInput"]==3)
					$chk3="checked";
					
				if ($_POST["rdInput"]==4)
					$chk4="checked";
				
				if ($_POST["rdInput"]==5)
					$chk5="checked";
					
				$f->radio("rdInput","rdInput",5,"","Do Nothing","$chk5 onClick='document.forms[0].submit()'") ;
				echo "<br>";
				$f->radio("rdInput","rdInput",1,"","by URL","$chk1 onClick='document.forms[0].submit()'") ;
				echo "<br>";
				$f->radio("rdInput","rdInput",3,"","by File","$chk3 onClick='document.forms[0].submit()'");
				echo "<br>";
				$f->radio("rdInput","rdInput",4,"","by Content","$chk4 onClick='document.forms[0].submit()'") ;
			?>
		</td>
	</tr>
	<tr>
		<td width="30%">Content (*)</td>
		<td>
			<? 
				if ($_POST["rdInput"]==1) {
					$f->textbox("tInput[]","tInput",$_POST["tInput"][1],"inputBox",50,255);
			 	} elseif ($_POST["rdInput"]==2) {
					$qry="select * from p_category";
					$rs=$ora->sql_fetch($qry,$bit_app["db"]);
					echo "<select class='inputBox' name='slCat'>";
					for ($i=1;$i<=$rs->jumrec;$i++) {
						echo "<option value='".$rs->value[$i]["category_id"]."'>".$rs->value[$i]["category_name"]."</option>";
					}
					echo "</select>";
				} elseif ($_POST["rdInput"]==3) {
					echo $f->file("tfile","tfile","browse","inputBox",50,255);
				} elseif ($_POST["rdInput"]==4) {
					$f->textbox("tInput[]","tInput",$_POST["tInput"][1],"inputBox",30,255,'','readonly=1');
					echo "&nbsp;";
			 		$f->button("","...","button","window.open('".$bit_app["path_url"]."/bit_content/content/contentBrowse.php?field=tInput[1]','win1','top=100,left=100,height=500,width=800,resizable=1,scrollbars=1')");
				}
			?>
		</td>
	</tr>
	<? if ($_POST["rdInput"]==1 || $_POST["rdInput"]==3) { ?>
	<tr>
		<td>Target</td>
		<td>
			<select class="inputbox" name="sTarget">
				<option value="_self" <? if ($_POST["sTarget"]=="_self") echo "selected"?>>Self</option>
				<option value="_blank" <? if ($_POST["sTarget"]=="_blank") echo "selected"?>>New Window</option>
				<option value="_frame" <? if ($_POST["sTarget"]=="_frame") echo "selected"?>>frame</option>
			</select>
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
			<? $f->submit("ok","s i m p a n","button")?>
			<? $f->button("button","t u t u p . w i n d o w","button","window.close()")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
	
	if ($_POST["ok"]) {
		
		$img1=do_upload("userfile1");
		$file1=do_upload("tfile");
		
		if ($_POST["rdInput"]==2)
			$_POST["tInput"][1]=$_POST["slCat"];
		elseif ($_POST["rdInput"]==3)
			$_POST["tInput"][1]=$file1;
		
		
		$qry="insert into menu
				(menu_name,content,tipe_content,target,icon)
				values(
				'".$_POST["tInput"][0]."',
				'".$_POST["tInput"][1]."',
				'".$_POST["rdInput"]."',
				'".$_POST["sTarget"]."',
				'".$img1."'
				)";
						
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Tambah Menu Gagal. Silahkan hubungi administrator !');
		} else {
			alert('Tambah Menu  berhasil !');
			parent_opener_submit();
			close();
		}
	}
	
	$ora->logoff();
	
?>
</body>
</html>
 