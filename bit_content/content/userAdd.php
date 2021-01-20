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

			/*
			if (document.forms[0].tInput[3].value == '') {
				alert('Group silahkan diisi terlebih dahulu !');
				document.forms[0].tInput[3].focus();
				return false;
			}
			*/

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

	$ora = new clsMysql;
	$ora->logon($bit_app["user_db"], $bit_app["pass_db"]);
	?>
	<table width="100%">
		<tr>
			<td colspan="4">
				<?php echo setTitleAdd("Add User"); ?>
			</td>
		</tr>
		<tr>
			<td>NIK (*)</td>
			<td colspan="3">
				<?php $f->textbox("tInput[]", "tInput", $_POST["tInput"][0], "inputBox", 10, 255) ?>
				<?php //$f->button("go", "Go", "button", "document.forms[0].submit()") ?>
			</td>
		</tr>
		<tr>
			<td>Nama (*)</td>
			<td colspan="3">
				<?
				/* 	
			$oraS=new bit_oracle;
			$oraS->logon();
			$qry="select v_nama_karyawan,v_short_divisi from s_hr_telkom_full where n_nik='".$_POST["tInput"][0]."'";
			$rsNama=$oraS->sql_fetch($qry);
			
			$oraS->logoff();
		*/
				$f->textbox("tInput[]", "tInput", $rsNama->value[1][1], "", 50, 255, "", "");
				?>
			</td>
		</tr>
		<tr>
			<td>Divisi </td>
			<td colspan="3"><?php $f->textbox("tInput[]", "tInput", $rsNama->value[1][2], "", 50, 255, "", "") ?></td>
		</tr>
		<tr>
			<td>Group</td>
			<td colspan="3"><?php $f->textbox("tInput[]", "tInput", $_POST["tInput"][3], "inputBox", 50, 255) ?></td>
		</tr>
		<tr>
			<td valign="top">Tgl. Lahir </td>
			<td colspan="3">
				<input type="text" class="tBirth" name="tBirth" id="tBirth" readonly>
			</td>
		</tr>
		<tr>
			<td>Foto</td>
			<td colspan="3">
				<?php $f->file("userfile1", "userfile1", "browse", "inputBox", 50, 255) ?>
			</td>
		</tr>
		<tr>
			<td>Level (*)</td>
			<td colspan="3">
				<select class="inputbox" name="sLevel">
					<option value="1">Guest</option>
					<option value="2">Author</option>
					<option value="3">Publiser</option>
					<option value="4">Administrator</option>
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
			<td colspan="3">
				<?php
				$qry = "select profile_id,profile_name from p_profile";
				$rs = $ora->sql_fetch($qry, $bit_app["db"]);
				echo "<select class='inputBox' name='slProfile'>";
				for ($i = 1; $i <= $rs->jumrec; $i++) {
					echo "<option value='" . $rs->value[$i][1] . "'>" . $rs->value[$i][2] . "</option>";
				}
				echo "</select>";
				?>
			</td>
		</tr>
		<tr>
			<td valign="top">Profile Menu Icon</td>
			<td colspan="3">
				<?php
				$qry = "select profile_id,profile_name from p_profile_icon";
				$rs = $ora->sql_fetch($qry, $bit_app["db"]);
				echo "<select class='inputBox' name='slProfileIcon'>";
				for ($i = 1; $i <= $rs->jumrec; $i++) {
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
				<?php $f->button("button", "Close", "button", "window.close()") ?>
			</td>
		</tr>
	</table>
	<?php
	$f->closeForm();
	if ($_POST["ok"]) {
		$qry = "select * from m_users where user_id='" . $_POST["tInput"][0] . "'";
		$rsUser = $ora->sql_fetch($qry, $bit_app["db"]);
		if ($rsUser->jumrec >= 1) {
			alert("NIK sudah terdaftar di database !");
			unset($_POST["ok"]);
		}
	}

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

		$tBirth = $_POST["tBirth"];
		$tBirth = str_replace('/', '-', $tBirth);

		$qry = "insert into m_master_user
				(
					nik,
					nama_karyawan,
					nama_divisi,
					loker2,
					tgl_lahir
					)
				values(
				'" . $_POST["tInput"][0] . "',
				'" . $_POST["tInput"][1] . "',
				'" . $_POST["tInput"][2] . "',
				'" . $_POST["tInput"][3] . "',
				'" . $tBirth . "'
				)";
		$ora->sql_no_fetch($qry, $bit_app["db"]);

		$img1 = do_upload("userfile1");

		$qry = "insert into m_users
				(
					user_id,
					user_foto,
					user_level,
					user_active_flag,
					user_adm_profile_id,
					user_profile_id,
					user_profile_icon)
				values(
				'" . $_POST["tInput"][0] . "',
				'" . $img1 . "',
				'" . $_POST["sLevel"] . "',
				1,
				'" . $_POST["slProfile"] . "',
				'" . $_POST["slProfileUser"] . "',
				'" . $_POST["slProfileIcon"] . "'
				)";

		if (!$ora->sql_no_fetch($qry, $bit_app["db"])) {
			alert('Tambah User Gagal. Silahkan hubungi administrator !');
		} else {
			parent_opener_submit();
			close();
		}
	}

	$ora->logoff();

	?>

	<script>
		$(document).ready(function() {
			var year = '<?= date('Y'); ?>';

			var lYear = parseInt(year) - 57;
			var rYear = parseInt(year) - 20;
			
			$('#tBirth').datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd',
				yearRange: lYear + ':' +  rYear,
				//maxDate: new Date('12/31/'+ rYear)
			});
		});
	</script>
</body>

</html>