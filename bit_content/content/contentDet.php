<?
	include_once("../../bit_config.php");
?>
<html>
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
</head>
<body topmargin="0" leftmargin="0" rightmargin="0">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
	$ora=new clsMysql;
		$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
		$qry="select title,category_id,content,image,tag from m_contents where id=".$_GET["id"];
		$rs=$ora->sql_fetch($qry,$bit_app["db"]);
		
		if (!$_POST["tInput"][0])
			$_POST["tInput"][0]=$rs->value[1][1];
		
		if (!$_POST["tInput"][1])
			$_POST["tInput"][1]=$rs->value[1][2];
		
		if (!$_POST["tag"])
			$_POST["tag"]=$rs->value[1][5];
	
		if (!$_POST["fck"])
			$_POST["fck"]=$rs->value[1][3];
			
		$qry="select category_id,category_name from p_category";
		$rsCat=$ora->sql_fetch($qry,$bit_app["db"]);
		for ($i=1;$i<=$rsCat->jumrec;$i++) {
			$datacategory[$rsCat->value[$i][1]]=$rsCat->value[$i][2];
		}
?>
<table width="100%">
	<tr>
		<td colspan="2">
			<? echo setTitleDetail("Detail News");?>
		</td>
	</tr>
	<tr>
		<td width="10%">Judul</td>
		<td><? echo $_POST["tInput"][0]?></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr class="article_layout_hr">
		</td>
	</tr>
	<tr>
		<td valign="top">Kategori</td>
		<td><? echo $datacategory[$_POST["tInput"][1]]?></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr class="article_layout_hr">
		</td>
	</tr>
	<tr>
		<td valign="top">Tag</td>
		<td><? echo $_POST["tag"]?></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr class="article_layout_hr">
		</td>
	</tr>
	<?
		if ($rs->value[1][4]!="") {
	?>
		<tr>
			<td colspan="2">
				<table 
					style="margin-right: 10px;"
					 align=left border=0 cellpadding=2 cellspacing=2 hspace=8>
				<tr>
					<td>
						<?
						$imgDetail="../../bit_folder/".$rs->value[1][4];
						$size=getimagesize($imgDetail);
						
						if ($size[0]<400)
							$wImg=$size[0];
						else
							$wImg=400;
						?>
						<img src="../../bit_folder/<? echo $rs->value[1][4]?>" width="<? echo $wImg?>">
					</td>
				</tr>
				</table>
					<?
						$fc=new FCKeditor("fck");
						$fc->ToolbarSet="Basic";
						echo $fc->Value=$_POST["fck"];
						//echo $fc->CreateHtml();
					?>
				
			</td>
		</tr>
	<? } else { ?>
		<tr>
			<td colspan="2">
				<?
						$fc=new FCKeditor("fck");
						$fc->ToolbarSet="Basic";
						echo $fc->Value=$_POST["fck"];
					?>
			</td>
		</tr>
	<? } ?>
	<tr>
		<td colspan="2">
			<hr class="article_layout_hr">
		</td>
	</tr>
	<tr>
		<td colspan="2">
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
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left">
			<? $f->button("reset","c l o s e","button","window.close()")?>
		</td>
	</tr>
</table>
</body>
</html>
 