<?
session_start();
include_once("../../bit_config.php");
?>
<html>

<head>
	<title><? echo $bit_app["title"] ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"] ?>bit_css/standart.css">
	<script type="text/javascript" src="../../bit_mod/mod_js.js"></script>
	<script type="text/javascript" src="../../bit_third/flowplayer web/anchors_files/flowplayer-3.js"></script>

	<script>
		function validate() {

			if (document.forms[0].tFile.value != '') {
				if (checkFileExtension(document.forms[0].tFile) != 1) {
					document.getElementById('sError').style.display = 'block';
					document.getElementById('sError').innerHTML = checkFileExtension(document.forms[0].tFile);
					return false;
				}
			}
			return true;
		}
	</script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput.focus()">
	<?

	$f = new clsForm;
	$f->openForm("frmMain", "frmMain", "", "POST", "multipart/form-data", "onSubmit='return validate()'");

	$ora = new clsMysql;
	$ora->logon($bit_app["user_db"], $bit_app["pass_db"]);

	$qry = "select keterangan,file,updated_date,updated_by,image from m_video where id=" . $_GET["id"];
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if (!$_POST["ok"]) {
		$_POST["tInput"] = $rs->value[1][1];
		$_POST["tFile"] = $rs->value[1][2];
	}

	?>
	<table width="100%">
		<tr>
			<td colspan="2">
				<? echo setTitleAdd("Upload Video (.mp4)"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top">Video (.mp4) </td>
			<td>
				<? $f->file("tFile", "tFile", "browse", "inputBox", 50, 255, "
				if (checkFileExtension(this)!=1) {
					alert('Format video harus (.mp4) !')
				}") ?>
			</td>
		</tr>
		<tr>
			<td valign="top">Gambar </td>
			<td>
				<? $f->file("tFileImage", "tFileImage", "browse", "inputBox", 50, 255, "
				if (checkFileExtensionImage(this)!=1) {
					alert(checkFileExtensionImage(this));
				}") ?>
			</td>
		</tr>
		<tr>
			<td width="30%" valign="top">Keterangan</td>
			<td>
				<?
				$f->textarea("tInput", "tInput", $_POST["tInput"], "inputBox", 4, 50);
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
				<? $f->submit("ok", "Simpan", "button") ?>
				<? $f->button("button", "Close", "button", "window.close()") ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<?
				echo "<a  
			 href='" . $bit_app["folder_url"] . "/" . $rs->value[1]["file"] . "'  
			 style='display:block;width:270px;height:160px;z-index:-999'  
			 id='player'> 
			 
			 <img src='" . $bit_app["folder_url"] . "/" . $rs->value[1]["image"] . "' alt='Video 1'  width='270px' height='160px' border=0/> 
		</a>";

				echo "
			<script>
				flowplayer('player', '" . $bit_app["third_url"] . "/flowplayer/flowplayer-3.1.5.swf');
			</script>";
				?>
			</td>
		</tr>
	</table>
	<?

	$f->closeForm();

	if ($_POST["ok"]) {

		$img = do_upload("tFile");
		if ($img)
			$andFile = ", file = '$img'";

		$img1 = do_upload("tFileImage");
		if ($img1)
			$andFile .= ", image = '$img1'";

		$qry = "update m_video
				set 
					keterangan='" . $_POST["tInput"] . "',
					updated_date=sysdate(),
					updated_by='" . $_SESSION["userid_portal"] . "'
					$andFile
				where
					id = " . $_GET["id"];

		if (!$ora->sql_no_fetch($qry, $bit_app["db"])) {
			alert('Edit Video Gagal. Silahkan hubungi administrator !');
		} else {
			alert('Edit Video berhasil !');
			parent_opener_submit();
			close();
		}
	}

	$ora->logoff();

	?>
</body>

</html>