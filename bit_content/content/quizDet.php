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
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput.focus()">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
	
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
			<? echo setTitle2("Hasil Quiz ".$_POST["tInput"][0]);?>
		</td>
	</tr>
	<tr>
		<td colspan="2" height="100%">
		<?
			echo nl2br($_POST["fck"]);
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2" height="100%" align="center" valign="top">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th class="listTableHead">Pilihan</th>
				<th class="listTableHead">Score</th>
			</tr>
			<?
				$qry="select jawaban,(select count(distinct updated_by) from m_quiz_vote b where b.forum_id=a.forum_id and b.pilihan=a.id) cnt from m_quiz_answer a where forum_id=".$_GET["id"];
				$rsPilihan=$ora->sql_fetch($qry,$bit_app["db"]);
				for ($i=1;$i<=$rsPilihan->jumrec;$i++) {
					if ($rsPilihan->value[$i]["jawaban"]) {
						if ($i%2==0)
							$cl="listTableRow";
						else
							$cl="listTableRowS";
					?>
					<tr>
						<td class="<? echo $cl?>"><? echo $rsPilihan->value[$i]["jawaban"]?></td>
						<td class="<? echo $cl?>"><? echo $rsPilihan->value[$i]["cnt"]?></td>
					</tr>
					<?
					}
				}
			?>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<? $f->button("reset","Close","button","window.close()")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
?>
</body>
</html>
 