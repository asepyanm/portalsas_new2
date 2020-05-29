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
</head>
<body topmargin="0" leftmargin="0" rightmargin="0">
<?
	
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
		
	$qry="select menu_name from menu where menu_id=".$_GET["id"];
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
?>
<table width="100%">
	<tr >
		<td colspan="2">
			<? echo setTitleDetail("I n f o r m a s i . d e t a i l .  m e n u ");?>
		</td>
	</tr>
	<tr>
		<td width="30%">Nama Menu </td>
		<td><? echo $rs->value[1][1]?></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr class="article_layout_hr">
		</td>
	</tr>
	<tr>
		<td width="30%" valign="top">Sub Menu</td>
		<td>
		<table border="0">
			<?
				$qry="select sub_menu_name from sub_menu where menu_id=".$_GET["id"];
				$rs=$ora->sql_fetch($qry,$bit_app["db"]);
				for ($i=1;$i<=$rs->jumrec;$i++) {
					echo "<tr>";
					echo "<td>".$rs->value[$i][1]."</td>";
					echo "</tr>";
				}
			?>
		</table>
		
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<? $f->button("button","t u t u p . w i n d o w","button","window.close()")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
	$ora->logoff();
	
?>
</body>
</html>
 