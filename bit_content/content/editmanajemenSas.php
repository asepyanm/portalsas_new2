<?php
	session_start();
	include_once("../../bit_config.php");
?>
<html>
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
<link rel="stylesheet" href="<? echo $bit_app["path_url"]?>bit_css/lte/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<? echo $bit_app["path_url"]?>bit_css/lte/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="<? echo $bit_app["path_url"]?>bit_css/lte/dist/css/skins/_all-skins.min.css">
<link rel="stylesheet" href="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/iCheck/flat/blue.css">
<link rel="stylesheet" href="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/morris/morris.css">
<link rel="stylesheet" href="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
<link rel="stylesheet" href="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/daterangepicker/daterangepiker.css">
<link rel="stylesheet" href="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<link type="text/css" media="screen" rel="stylesheet" href="<? echo $bit_app["path_url"]?>bit_third/colorbox/colorbox/example1/colorbox.css" />
<script language="javascript" src="<? echo $bit_app["path_url"]?>/bit_mod/mod_js.js" type="text/javascript"></script>

</head>
<body onLoad="document.forms[0].tInput[0].focus()">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
	$ora=new clsMysql;
		$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
		$qry="select nama, jabatan, foto from m_manajemen where id=".$_GET["id"];
		$rs=$ora->sql_fetch($qry,$bit_app["db"]);
		
		if (!$_POST["ok"]) {
			$_POST["tInput"][0]=$rs->value[1][1];
            $_POST["tInput"][1]=$rs->value[1][2];
            $_POST["foto"]=$rs->value[1][3];
            
            $fl=$bit_app["folder_dir"].$rs->value[1]['foto'];	
			$file=$rs->value[1]['foto'];
			if(!file_exists($fl) || filesize($fl)==0){
				$getobj = $s3->getObject(BUCKETNAME, PORTALSASDIR_DATA.$file, $fl);
			}
		}		
?>
<table width="90%" class="table">
	<tr>
		<td colspan="2">
			<? echo setTitle2("Edit Manajemen");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Nama  *</td>
		<td><? $f->textbox("tInput[]","tInput",$_POST["tInput"][0],"inputBox",50,150)?></td>
	</tr>
    <tr>
        <td width="30%">Jabatan  *</td>
        <td><? $f->textbox("tInput[]","tInput",$_POST["tInput"][1],"inputBox",50,150)?></td>
    </tr>
	<tr>
		<td valign="top">Foto</td>
		<td>
			<? 
				if ($_POST["foto"]) 
					echo "<img src='".$bit_app["path_url"]."bit_folder/".$_POST["foto"]."' width=120>";
			?>
			<? $f->file("userfile","userfile","browse","inputBox",50,255,"
				if (checkFileExtensionImage(this)!=1) {
					alert(checkFileExtensionImage(this));
				}")?>
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
<?php
	$f->hidden("img","img",$img);
	$f->closeForm();
	
	if ($_POST["ok"]) {		
		$img=do_upload_img("userfile");
		if ($img!="")
			$tm="foto='".$img."',";
			
		$qry="update m_manajemen
				set 
					nama='".$_POST["tInput"][0]."',
                    jabatan='".$_POST["tInput"][1]."',
                    ".$tm."
					updated_date=NOW(),
					updated_by='".getUserID()."'
				where id=".$_GET["id"];
		
		if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
			alert('Edit Manajemen gagal. Silahkan hubungi Administrator !');
			$ora->logoff();
		} else {			
			$ora->logoff();

			refresh_parent($bit_app["path_url"].'/bit_content/frame.php?form=content.manajemenSas');

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

<script src="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/morris/morris.min.js"></script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/knob/jquery.knob.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/plugins/fastclick/fastclick.js"></script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/dist/js/app.min.js"></script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/dist/js/pages/dashboard.js"></script>
<script src="<? echo $bit_app["path_url"]?>bit_css/lte/dist/js/demo.js"></script>


<script type="text/javascript" src="<? echo $bit_app["path_url"]?>bit_third/colorbox/colorbox/example1/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="<? echo $bit_app["path_url"]?>bit_third/colorbox/colorbox/colorbox/jquery.colorbox.js"></script>

</body>
</html>
 