<?php
include_once("../../bit_config.php");
?>
<html>

<head>
	<title><?php echo $bit_app["title"] ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<?php echo $bit_app["path_url"] ?>bit_css/standart.css">
	<link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/jquery-ui.css">
	<!-- jquery -->
	<script src="<?php echo $bit_app["path_url"]; ?>bit_js/jquery-1.12.1.min.js"></script>
	<!-- jquery ui -->
	<script src="<?php echo $bit_app["path_url"]; ?>bit_js/jquery-ui.min.js"></script>
	<?php
	getCalendarModule();
	?>
	<script>
		function validate() {
			if (document.forms[0].tInput[0].value == '') {
				alert('NIK silahkan diisi terlebih dahulu !');
				document.forms[0].tInput[0].focus();
				return false;
			}

			if (document.forms[0].tInput[1].value == '') {
				alert('Nama silahkan diisi terlebih dahulu !');
				document.forms[0].tInput[1].focus();
				return false;
			}

			if (document.forms[0].tInput[3].value == '') {
				alert('Group silahkan diisi terlebih dahulu !');
				document.forms[0].tInput[3].focus();
				return false;
			}

			if (document.forms[0].tBirth.value == '') {
				alert('Tanggal lahir silahkan diisi terlebih dahulu !');
				document.forms[0].tBirth.focus();
				return false;
			}
			return true;
		}
	</script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput[0].focus()">
	<?php

	$f = new clsForm;
	$f->openForm("frmMain", "frmMain", "", "POST", "multipart/form-data", "onSubmit='return validate()'");

	?>
	<table width="100%" border="0">
		<tr>
			<td colspan="4">
				<?php echo setTitleEdit("Edit User"); ?>
			</td>
		</tr>
		<?php
		$ora = new clsMysql;
		$ora->logon($bit_app["user_db"], $bit_app["pass_db"]);
		$qry = "select 
				a.user_id,
				b.nama_karyawan,
				a.user_level,
				user_adm_profile_id,
				user_profile_id,
				a.user_foto,
				b.nama_divisi,
				b.loker2,
				b.tgl_lahir,
				user_profile_icon
			from m_users a,m_master_user b where a.user_id=b.nik and a.user_id=" . $_GET["id"];
		$rs = $ora->sql_fetch($qry, $bit_app["db"]);

		if (!$_POST["ok"]) {
			$_POST["tInput"][0] = $rs->value[1][1];
			$_POST["tInput"][1] = $rs->value[1][2];
			$_POST["tInput"][2] = $rs->value[1][7];
			$_POST["tInput"][3] = $rs->value[1][8];
			$_POST["tBirth"] = $rs->value[1][9];

			$_POST["tInput"][4] = $rs->value[1][6];
			$_POST["sLevel"] = $rs->value[1][3];
			$_POST["slProfile"] = $rs->value[1][4];
			$_POST["slProfileUser"] = $rs->value[1][5];
			$_POST["slProfileUser"] = $rs->value[1][5];
			$_POST["slProfileIcon"] = $rs->value[1][10];
		}
		?>
		<tr>
		<tr>
			<td>NIK (*)</td>
			<td colspan="3">
				<?php $f->textbox("tInput[]", "tInput", $_POST["tInput"][0], "inputBoxDisabled", 10, 255, "", "readonly=1") ?>
				<?php $f->button("go", "Go", "button", "document.forms[0].submit()") ?>
			</td>
		</tr>
		<tr>
			<td>Nama (*)</td>
			<td colspan="3">
				<?php
				//$oraS=new bit_oracle;
				//$oraS->logon();
				//$qry="select v_nama_karyawan,v_short_divisi from s_hr_telkom_full where n_nik='".$_POST["tInput"][0]."'";
				//$rsNama=$oraS->sql_fetch($qry);
				//$_POST["tInput"][1]=$rsNama->value[1][1];
				//$_POST["tInput"][2]=$rsNama->value[1][2];
				//$oraS->logoff();
				?>
				<?php $f->textbox("tInput[]", "tInput", $_POST["tInput"][1], "inputBoxDisabled", 50, 255, "", "readonly=1") ?></td>
		</tr>
		<tr>
			<td>Divisi </td>
			<td colspan="3"><?php $f->textbox("tInput[]", "tInput", $_POST["tInput"][2], "inputBoxDisabled", 50, 255, "", "readonly=1") ?></td>
		</tr>
		<tr>
			<td>Group (*)</td>
			<td colspan="3"><?php $f->textbox("tInput[]", "tInput", $_POST["tInput"][3], "inputBox", 50, 50) ?></td>
		</tr>
		<tr>
			<td valign="top">Tgl. Lahir </td>
			<td><input type="text" class="tBirth" name="tBirth" id="tBirth" value="<?= $_POST["tBirth"] ?>" readonly></td>
		</tr>
		<tr>
			<td>Foto</td>
			<td colspan="3">
				<?php
				if ($_POST["tInput"][4])
					echo "<a href='../../bit_folder/" . $_POST["tInput"][4] . "' target='_blank'>" . $_POST["tInput"][4] . "</a>";
				?>
				<?php $f->file("userfile1", "userfile1", "browse", "inputBox", 50, 255) ?>
			</td>
		</tr>
		<tr>
			<td>Level</td>
			<td colspan="3">
				<select class="inputbox" name="sLevel">
					<option value="1" <?php if ($_POST["sLevel"] == 1) echo "selected" ?>>Guest</option>
					<option value="2" <?php if ($_POST["sLevel"] == 2) echo "selected" ?>>Author</option>
					<option value="3" <?php if ($_POST["sLevel"] == 3) echo "selected" ?>>Publisher</option>
					<option value="4" <?php if ($_POST["sLevel"] == 4) echo "selected" ?>>Administrator</option>
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top">Profile User</td>
			<td>
				<?php
				$qry = "select profile_id,profile_name from p_profile_user";
				$rs = $ora->sql_fetch($qry, $bit_app["db"]);
				echo "<select class='inputBox' name='slProfileUser'>";
				for ($i = 1; $i <= $rs->jumrec; $i++) {
					if ($_POST["slProfileUser"] == $rs->value[$i][1])
						echo "<option value='" . $rs->value[$i][1] . "' selected>" . $rs->value[$i][2] . "</option>";
					else
						echo "<option value='" . $rs->value[$i][1] . "'>" . $rs->value[$i][2] . "</option>";
				}
				echo "</select>";
				?>
			</td>
		</tr>
		<tr>
			<td valign="top">Profile Admin</td>
			<td>
				<?php
				$qry = "select profile_id,profile_name from p_profile";
				$rs = $ora->sql_fetch($qry, $bit_app["db"]);
				echo "<select class='inputBox' name='slProfile'>";
				for ($i = 1; $i <= $rs->jumrec; $i++) {
					if ($_POST["slProfile"] == $rs->value[$i][1])
						echo "<option value='" . $rs->value[$i][1] . "' selected>" . $rs->value[$i][2] . "</option>";
					else
						echo "<option value='" . $rs->value[$i][1] . "'>" . $rs->value[$i][2] . "</option>";
				}
				echo "</select>";
				?>
			</td>
		</tr>
		<tr>
			<td valign="top">Profile Menu Icon</td>
			<td>
				<?php
				$qry = "select profile_id,profile_name from p_profile_icon";
				$rs = $ora->sql_fetch($qry, $bit_app["db"]);
				echo "<select class='inputBox' name='slProfileIcon'>";
				for ($i = 1; $i <= $rs->jumrec; $i++) {
					if ($_POST["slProfileIcon"] == $rs->value[$i][1])
						echo "<option value='" . $rs->value[$i][1] . "' selected>" . $rs->value[$i][2] . "</option>";
					else
						echo "<option value='" . $rs->value[$i][1] . "'>" . $rs->value[$i][2] . "</option>";
				}
				echo "</select>";
				?>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<hr class="article_layout_hr">
				<b>Ket :</b>
				Field yang bertanda (*) harus diisi.
				<hr class="article_layout_hr">
			</td>
		</tr>
		<tr>
			<td colspan="4" align="right">
				<?php $f->submit("ok", "Simpan", "button") ?>
				<?php $f->button("ok", "Close", "button", "window.close()") ?>
			</td>
		</tr>
	</table>
	<?php
	$f->hidden("img", "img", $img);
	$f->closeForm();


	if ($_POST["ok"]) {

		if ($_POST["sLevel"]) {
			$qry = "select * from p_publisher_news where loker2='" . $_POST["tInput"][3] . "'";
			$rsLoker = $ora->sql_fetch($qry, $bit_app["db"]);
			if ($rsLoker->jumrec <= 0) {
				$qry = "insert into p_publisher_news(loker2,nik) values('" . $_POST["tInput"][3] . "','" . $_POST["tInput"][0] . "')";
				$ora->sql_no_fetch($qry, $bit_app["db"]);
			} else {
				$qry = "update p_publisher_news set nik=concat(nik,'," . $_POST["tInput"][0] . "')  where loker2='" . $_POST["tInput"][3] . "'";
				$ora->sql_no_fetch($qry, $bit_app["db"]);
			}
		}

		$img1 = do_upload("userfile1");
		if ($img1)
			$wFoto = ", user_foto = '" . $img1 . "'";

		$tBirth = $_POST["tBirth"];
		$tBirth = str_replace('/', '-', $tBirth);

		$qry = "update m_master_user
				set 
					nik='" . $_POST["tInput"][0] . "',
					nama_karyawan='" . $_POST["tInput"][1] . "',
					nama_divisi='" . $_POST["tInput"][2] . "',
					loker2='" . $_POST["tInput"][3] . "',
					tgl_lahir='" . $tBirth . "'
				where
					nik=" . $_GET["id"];
		$ora->sql_no_fetch($qry, $bit_app["db"]);

		$qry = "update m_users
				set 
					user_level='" . $_POST["sLevel"] . "',
					user_adm_profile_id='" . $_POST["slProfile"] . "',
					user_profile_icon='" . $_POST["slProfileIcon"] . "',
					user_profile_id='" . $_POST["slProfileUser"] . "'
					$wFoto
				where
					user_id=" . $_GET["id"];
		if (!$ora->sql_no_fetch($qry, $bit_app["db"])) {
			alert('Update User Gagal. hubungi administrator !');
		} else {
			alert('Update User Berhasil !');
			parent_opener_submit();
			close();
		}
	}
	$ora->logoff();
	?>

	<script>
		$(document).ready(function() {
			var year = '<?= date('Y'); ?>';

			var rYear = parseInt(year) - 10;
			
			$('#tBirth').datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd',
				maxDate: new Date('12/31/'+ rYear)
			});
		});
	</script>
</body>

</html>