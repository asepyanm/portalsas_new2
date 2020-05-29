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
<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput[0].focus()">
<?
	
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
		
	$qry="select menu_name,tipe_content,content,target,icon from menu where menu_id=".$_GET["id"];
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	if (!$_POST["ok"]) {
	
		if (!$_POST["tInput"][0])
			$_POST["tInput"][0]=$rs->value[1][1];
		
		if (!$_POST["tInput"][1])
			$_POST["tInput"][1]=$rs->value[1][3];
		
		if (!$_POST["rdInput"])
			$_POST["rdInput"]=$rs->value[1][2];
				
		if (!$_POST["sTarget"])
			$_POST["sTarget"]=$rs->value[1][4];
			
		$_POST["slCat"]=$rs->value[1][3];
	}
?>
<table  width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitleEdit("e d i t . m e n u");?>
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
		<td valign="top">
			<? 
				if (!$_POST["rdInput"])
					$_POST["rdInput"]=1;
					
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
		<td width="30%" valign="top">Content (*)</td>
		<td>
			<? 
				if ($_POST["rdInput"]==1) {
					$f->textbox("tInput[]","tInput",$_POST["tInput"][1],"inputBox",50,255);
			 	}elseif ($_POST["rdInput"]==2) {
					$qry="select * from p_category";
					$rsCat=$ora->sql_fetch($qry,$bit_app["db"]);
					echo "<select class='inputBox' name='slCat'>";
					for ($i=1;$i<=$rsCat->jumrec;$i++) {
						if ($_POST["slCat"]==$rsCat->value[$i]["category_id"])
							echo "<option selected value='".$rsCat->value[$i]["category_id"]."'>".$rsCat->value[$i]["category_name"]."</option>";
						else
							echo "<option value='".$rsCat->value[$i]["category_id"]."'>".$rsCat->value[$i]["category_name"]."</option>";
					}
					echo "</select>";
				} elseif ($_POST["rdInput"]==3) {
					if ($rs->value[1][2])
						echo "<a target='_blank' href='../../bit_folder/".$rs->value[1][3]."'>".$rs->value[1][3]."</a><br>";
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
		
		$img=do_upload("userfile1");
		$file1=do_upload("tfile");
		
		if ($img) {
			$update=", icon='".$img."'";
		}
		
		if ($file1) {
			$content=", content='".$file1."'";
		}
		
		if ($_POST["rdInput"]==1 || $_POST["rdInput"]==4) {
			$content=", content='".$_POST["tInput"][1]."'";
		} elseif ($_POST["rdInput"]==2) {
			$_POST["tInput"][1]=$_POST["slCat"];
			$content=", content='".$_POST["tInput"][1]."'";
		} elseif ($_POST["rdInput"]==3)
			$_POST["tInput"][1]=$file1;
		
		$qry="update
					menu
				set	
					menu_name='".$_POST["tInput"][0]."',
					tipe_content='".$_POST["rdInput"]."',
					target='".$_POST["sTarget"]."'
					$content
					$update
				where
					menu_id=".(int)$_GET["id"];
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Edit Menu Gagal. Silahkan hubungi administrator !');
		} else {
			alert('Edit Menu  berhasil !');
			parent_opener_submit();
			close();
		}
	}
	
	$ora->logoff();
	
?>
</body>
</html>
 