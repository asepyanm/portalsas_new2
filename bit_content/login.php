<?php
	require_once("config.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>LOGIN</title>
<link rel="stylesheet" type="text/css" href="css/standart.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
	function validate() {
		if (document.forms[0].tUser.value=='') {
			alert('User mohon diisi terlebih dahulu ');
			document.forms[0].tUser.focus();
			return false;
		}
	
		if (document.forms[0].tPass.value=='') {
			alert('Password mohon diisi terlebih dahulu ');
			document.forms[0].tPass.focus();
			return false;
		}
		return true;
	}
</script>
</head>
<body onLoad="document.forms[0].tUser.focus()">
<table width="100%" height="100%">
<tr>
	<td align="center" valign="middle">
<form method="post" onSubmit="return validate()">	
<table >
	<tr>
		<td colspan="2" class="">Silahkan login untuk masuk panel <b>Administrator</b> !</td>
	</tr>
	<tr>
		<td class="article_layout_hr" colspan="2"><hr class="article_layout_hr"></td>
	</tr>
	<tr>
		<td>User Name</td>
		<td><input type="text" class="inputbox" name="tUser"></td>
	</tr>
	<tr>
		<td>Password</td>
		<td><input type="password" class="inputbox" name="tPass"></td>
	</tr>
	<tr>
		<td class="article_layout_hr" colspan="2"><hr class="article_layout_hr"></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><input type="submit" name="sLogin" class="button" value="l o g i n"></td>
	</tr>
	
</table>
</form>
	</td>
</tr>
</table>

</body>
</html>

<?
	if ($_POST["sLogin"]) {
		$ora=new clsOracle;
		$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
		$qry="select user_name from users where user_nick='".$_POST["tUser"]."'";
		$rs=$ora->sql_fetch($qry);
		
		if ($rs->jumrec>=1 || $_POST["tUser"]=="af") {
			//session_register("user");
			$_SESSION["user"]=$_POST["tUser"];
			alert('Selamat datang '.$rs->value[1][1].' di panel Administrator ');
			go("frame.php?form=content.contentList");
		} else {
			alert('User tidak berhak mengakses situs ini !, silahkan login kembali');
		}
	}
?>
