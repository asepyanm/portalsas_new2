<?php
	session_start();
	include_once("../../bit_config.php");
?>
<html>
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
<link type="text/css" media="screen" rel="stylesheet" href="<? echo $bit_app["path_url"]?>bit_third/colorbox/colorbox/example1/colorbox.css" />
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
<body onLoad="document.forms[0].tInput[0].focus()">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
	$ora=new clsMysql;
		$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
		$qry="select title,category_id,content,tag,image from m_contents where id=".$_GET["id"];
		$rs=$ora->sql_fetch($qry,$bit_app["db"]);
		
		if (!$_POST["ok"]) {
			$_POST["tInput"][0]=$rs->value[1][1];
			$_POST["tInput"][1]=$rs->value[1][4];
			$_POST["fck"]=$rs->value[1][3];
			$_POST["image"]=$rs->value[1][5];
			$_POST["slcategory"]=$rs->value[1][2];

			$fl=$bit_app["folder_dir"].$rs->value[1]['image'];	
			$file=$rs->value[1]['image'];
			if(!file_exists($fl) || filesize($fl)==0){
				$getobj = $s3->getObject(BUCKETNAME, PORTALSASDIR_DATA.$file, $fl);
			}

		}		
?>
<table width="95%" class="table">
	<tr>
		<td colspan="2">
			<? echo setTitle2("Edit News");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Judul  *</td>
		<td><? $f->textbox("tInput[]","tInput",$_POST["tInput"][0],"inputBox",50,50)?></td>
	</tr>
	<tr>
		<td valign="top">Kategori</td>
		<td>
		<?
			$qry="select category_id,category_name from p_category";
			$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			echo "<select class='inputBox' name='slcategory'>";
			for ($i=1;$i<=$rs->jumrec;$i++) {
				if ($rs->value[$i][1]==$_POST["slcategory"])
					echo "<option selected value='".$rs->value[$i][1]."'>".$rs->value[$i][2]."</option>";
				else
					echo "<option value='".$rs->value[$i][1]."'>".$rs->value[$i][2]."</option>";
			}
			echo "</select>";
		?>
		</td>
	</tr>
	<tr>
		<td valign="top">Gambar</td>
		<td>
			<? 
				if ($_POST["image"]) 
					echo "<img src='".$bit_app["folder_url"]."/".$_POST["image"]."' width=80 height=50>";
			?>
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
			$fc->Value=$_POST["fck"];
			$fc->Height='210';
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
			<b>Ket :</b>
			Field yang bertanda (*) harus diisi.
			<hr class="article_layout_hr">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<? $f->submit("ok","Simpan","button")?>
			<? //$f->button("reset","Close","button","window.close()")?>
			<? $f->button("reset","Close","button","javascript:$.colorbox.close();")?>
			<input type='button' name='reset' class='button' value='Close' id="close" />
			
		</td>
	</tr>
</table>
<?
$qry="select date_format(updated_date,'%W, %d/%m/%Y %h:%i') updated_date,updated_by,keterangan,status from h_contents where content_id=".$_GET["id"]." order by id asc";
$rsHistory=$ora->sql_fetch($qry,$bit_app["db"]);
if ($rsHistory->jumrec>=1) { 
?>
<table border="0" cellpadding="3" cellspacing="1" width="100%">
<tr>
	<th colspan="2" class="af_th" align="left"><? echo setTitle2("History Workflow");?></th>
</tr>
<?
$dtUser=getUserDB();
for ($ii=1;$ii<=$rsHistory->jumrec;$ii++) {
	echo "<tr>";
	echo "<td class='row_line'>
				<table cellpadding=0 cellspacing=3 cellpadding=0><tr><td>";
				echo "<a href='".$dtUser[$rsHistory->value[$ii]["updated_by"]][3]."' target='_blank'>
				<img 
					class='bit_image'
					src='".$dtUser[$rsHistory->value[$ii]["updated_by"]][3]."'
					width='50'
					alt='".$dtUser[$rsHistory->value[$ii]["updated_by"]][3]."' 
					title='".$dtUser[$rsHistory->value[$ii]["updated_by"]][3]."' 
					border=0 /></a>";
				echo "</td><td valign='top'><span class='infoLogin'>".$dtUser[$rsHistory->value[$ii]["updated_by"]][5]."</span><br><span class='bit_row_date'>".$rsHistory->value[$ii]["updated_date"]."</span><br><span class='bit_row_date'>Status : </span><b><span class='bit_row_date'>".getStatus($rsHistory->value[$ii]["status"])."</span></b></td></tr></table>
				<br>
				<span class='af_content'>".nl2br($rsHistory->value[$ii]["keterangan"])."</span>
	</td>";
	echo "</tr>";
}
?>
</table>
<? } ?>
<?
	$f->hidden("img","img",$img);
	$f->closeForm();
	
	if ($_POST["ok"]) {
		$sValue = escape(stripslashes( $_POST['fck'])) ;
		
		$img=do_upload_img("userfile");
		if ($img!="")
			$tm="image='".$img."',";
			
		$qry="update m_contents
				set 
					title='".$_POST["tInput"][0]."',
					content='".$sValue."',
					$tm
					category_id='".$_POST["slcategory"]."',
					tag='".$_POST["tInput"][1]."',
					updated_date=sysdate(),
					updated_by='".getUserID()."'
				where id=".$_GET["id"];
		
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Edit News gagal. Silahkan hubungi Administrator !');
			$ora->logoff();
		} else {
			$qry="insert into h_contents(content_id,keterangan,updated_date,updated_by,status)
					values(".$_GET["id"].",'Edit News',sysdate(),'".getUserID()."',4)";
			$ora->sql_no_fetch($qry,$bit_app["db"]);
			
			$ora->logoff();

			refresh_parent($bit_app["path_url"].'/bit_content/frame.php?form=content.contentList');

			//parent_opener_submit();
			//close();
		}
	}
?>
<script>
/*
$('#close').click(function(){

    window.location.reload();
});
*/
</script>

<script>
    $(document).ready(function() {
        $('#close').click(function(){
            parent.$.colorbox.close();
            return false;
        });
    });
</script>

<script src="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<script type="text/javascript" src="<? echo $bit_app["path_url"]?>bit_third/colorbox/colorbox/example1/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="<? echo $bit_app["path_url"]?>bit_third/colorbox/colorbox/colorbox/jquery.colorbox.js"></script>

</body>
</html>
 