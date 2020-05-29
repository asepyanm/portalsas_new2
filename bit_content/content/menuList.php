<html>
<head>
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
<title>Administrator Portal SAS</title>
</head>
<body leftmargin="0" topmargin="0">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data");
?>
	<table cellpadding="1" cellspacing="1" border="0" width="100%">
	<tr>
		<td colspan="5">
			<? echo setTitleCari("Daftar Menu");?>
		</td>
	</tr>
	</table>
	<table cellpadding="0" cellspacing="1" border="0" width="100%">
	<thead>
		<tr>
		<? $arrField=array("NO","Nama Menu","Urutan","Sub Menu") ?>
		<?
			for ($i=0;$i<count($arrField);$i++) {
				echo "<th class='listTableHead'>".$arrField[$i]."&nbsp;&nbsp;";
				echo "</th>";
			}
			echo "<th colspan=2 class='listTableHead'>Perintah</th>";
			$orderBy ="order by menu_id";
			
		?>
		</tr>
	</thead>
	<tbody>
		<?
			$ora=new clsMysql;
			$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
			
			if ($_POST["sSave"]) {
				while (list($k,$v)=each($_POST["tPosisi"])) {
					$qry="update  menu
							set posisi='$v' where  menu_id=".$k;
					$ora->sql_no_fetch($qry,$bit_app["db"]); 
				}
			}
			
			switch ($_POST["hdAction"]) {
			case 3 :
				$qry="delete from menu
						where menu_id=".(int)$_POST["id"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); break;
			case 4 :
				$qry="update menu
						set publish_flag=".((int)!$_POST["status"])." 
						where menu_id=".(int)$_POST["id"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); break;
			case 9 :
				$qry="update menu
						set private_flag=".((int)!$_POST["private"])." 
						where menu_id=".(int)$_POST["id"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); break;
			}
			
			$qry="select 
					count(1) 
				  from menu
				  where 1=1 $where";
			$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			$totalRow=$rs->value[1][1]; //total record	
		
			//==============================================
			if ($page==0)
				$page=1;
			else
				$page=$_POST["page"];
			
			if ($_POST["cPage"])
				$page=(($_POST["slPage"]-1)*$bit_app["sumOfPage"])+1;
			
			if (!$_POST["slPage"])
				$_POST["slPage"]=1;
			
			
			
			$rownum2 = ($page*$bit_app["sumOfRow"])+1;
			$rownum1 = ($page-1)*$bit_app["sumOfRow"]+1;
			
			$paging=ceil($totalRow/$bit_app["sumOfRow"]);
			//=============================================
			$qry="select 
					menu_id,menu_name,publish_flag,private_flag,posisi
				  from menu a
				  where 1=1 $where
				  $orderBy
				  limit ".($rownum1-1).",".$bit_app["sumOfRow"]."
				  ";
			$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			
			for ($i=1;$i<=$rs->jumrec;$i++) {
				if ($i%2==0)
					$cl="listTableRow";
				else
					$cl="listTableRowS";
		?>			
				<tr class="<? echo $cl?>">
					<td align="center" width="5%"><? echo ((($page-1)*$bit_app["sumOfRow"])+$i)?></td>
					<td align="left" width="15%"><? echo $rs->value[$i][2]?></td>
					<td width="3%" align="center"><input name="tPosisi[<? echo $rs->value[$i][1]?>]" type="text" value="<? echo $rs->value[$i]["posisi"]?>" size="3" maxlength="3" class="input"></td>
					<td align="center" width="8%"><a href="#" onClick="var xWinMenu;xWinMenu=window.open('<? echo $bit_app["path_url"]."/bit_content/content/"?>sMenuList.php?id=<? echo $rs->value[$i][1]?>&mn=<? echo $rs->value[$i][2]?>','xWinMenu','top=140,left=100,width=870,height=400,scrollbars=1,resizable=1');xWinMenu.focus()">edit Sub Menu</a></td>
					<td width="1%" align="center">
						<a href="#" title="Edit Menu"><img src="<? echo $bit_app["path_url"]?>/bit_images/edit.gif" onClick="var win; win=window.open('<? echo $bit_app["path_url"]."/bit_content/content/"?>menuEdit.php?id=<? echo $rs->value[$i][1]?>','win','top=50,left=100,height=350,width=600,resizable=1,scrollbars=1');win.focus()"></a>
					</td>
					<td width="1%" align="center">
						<a href="#" title="Hapus Menu"><img src="<? echo $bit_app["path_url"]?>/bit_images/hapus.png" onClick="var ans;ans=confirm('Anda yakin untuk menghapus Menu [<? echo $rs->value[$i][2]?>] ?'); if (ans) {document.forms[0].hdAction.value=3;document.forms[0].id.value=<? echo $rs->value[$i][1]?>;document.forms[0].submit();}"></a>
					</td>
					<!--
					<? if (!$rs->value[$i][4]) { ?>
					<td width="1%">
						<a href="#" title="Publish to Private" onClick="var ans;ans=confirm('Anda yakin untuk publish to Private ?'); if (ans) {document.forms[0].hdAction.value=9;document.forms[0].private.value='<? echo $rs->value[$i][4]?>';document.forms[0].id.value=<? echo $rs->value[$i][1]?>;document.forms[0].submit();}">public</a>
					</td>
					<? } else {?>
					<td width="1%">
						<a href="#" title="Publish to Public" onClick="var ans;ans=confirm('Anda yakin untuk publish to public ?'); if (ans) {document.forms[0].hdAction.value=9;document.forms[0].private.value='<? echo $rs->value[$i][4]?>';document.forms[0].id.value=<? echo $rs->value[$i][1]?>;document.forms[0].submit();}">registered</a>
					</td>
					<? } ?>
					-->
				</tr>	
		<? } ?>
	</tbody>
	</table>
	<table width="100%">
		<td align="center">
			<?
				$tmp=ceil(($totalRow/$bit_app["sumOfRow"]));
				if ((($_POST["slPage"]-1)*$bit_app["sumOfPage"])+$bit_app["sumOfPage"]<=$tmp)
					$maxCount=(($_POST["slPage"]-1)*$bit_app["sumOfPage"])+$bit_app["sumOfPage"];
				else
					$maxCount=$tmp;
				
				echo "page : &nbsp;&nbsp;";
				
				for ($i=(($_POST["slPage"]-1)*$bit_app["sumOfPage"])+1;$i<=$maxCount;$i++) {
					echo "<a href='#' onClick='document.forms[0].page.value=".(int) $i.";document.forms[0].submit()'>&nbsp;$i&nbsp;</a>";
				}
			?>
			<?
				if (ceil($paging/$bit_app["sumOfPage"])>1) {
			?>
			<select name="slPage" class="inputPage" onChange="document.forms[0].cPage.value=1;document.form.submit()">
				<?
					
					for ($i=1;$i<=ceil($paging/$bit_app["sumOfPage"]);$i++) {
						if ($_POST["slPage"]==$i) 
							echo "<option selected value='$i'>$i</option>";
						else
							echo "<option value='$i'>$i</option>";
					}
				?>
			</select>
			<?
				}
			?>
			<input type="hidden" name="page" value="<? echo $_POST["page"]?>">
			<input type="hidden" name="cPage" value="0">
			<input type="hidden" name="hdAction" value="0">
			<input type="hidden" name="id" value="0">
			<input type="hidden" name="status">
			<input type="hidden" name="private">
		</td>
	</table>
	<hr class="article_layout_hr">
	<p align="right">
	<? $f->submit("sSave","Simpan","button")?>
	&nbsp;
	<? $f->button("add","Tambah Menu","button","var win;win=window.open('".$bit_app["path_url"]."/bit_content/content/menuAdd.php','win','top=200,left=100,height=350,width=600,resizable=1,scrollbars=1');win.focus()")?>
	&nbsp;
	<? $f->button("refresh","Refresh","button","window.location=window.location.href")?>
	</p>
	<?
		for ($i=0;$i<count($arrField);$i++) {
			echo "<input type='hidden' name='hdOrder[$i]' id='hdOrder' value='".$_POST["hdOrder"][$i]."'>";
		}
	?>
<? $f->closeForm();
   $ora->logoff(); 
?>
</body>
</html>


