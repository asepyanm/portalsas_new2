<? include_once('../../bit_config.php'); ?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
<title><? echo $bit_app["title"]?></title>
</head>
<body leftmargin="0" topmargin="0">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data");
?>
	<table cellpadding="1" cellspacing="1" border="0" width="100%">
	<tr>
		<td colspan="5">
			<? echo setTitleCari("Daftar SubMenu");?>
		</td>
	</tr>
	</table>
	 <span class="container"> <img src="../../bit_images/indent1.png"> <? echo $_GET["mn"]?></span>
	<hr class="article_layout_hr">
	<table cellpadding="0" cellspacing="1" border="0" width="100%">
	<thead>
		<tr>
		<? $arrField=array("NO","Nama Sub Menu","Tipe Content","Urutan") ?>
		<?
			for ($i=0;$i<count($arrField);$i++) {
				echo "<th class='listTableHead'>".$arrField[$i]."&nbsp;&nbsp;";
				echo "</th>";
			}
			echo "<th colspan=3 class='listTableHead'>Perintah</th>";
			$orderBy ="order by sub_menu_id";
			
		?>
		</tr>
	</thead>
	<tbody>
		<?
			$ora=new clsMysql;
			$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
			
			switch ($_POST["hdAction"]) {
			case 3 :
				$qry="delete from sub_menu_l1
						where sub_menu_id=".(int)$_POST["id"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); break;
			case 9 :
				$qry="update sub_menu_l1
						set private_flag=".((int)!$_POST["private"])." 
						where sub_menu_id=".(int)$_POST["id"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); break;
			}
			
			
			if ($_POST["sSave"]) {
				while (list($k,$v)=each($_POST["tPosisi"])) {
					$qry="update  sub_menu_l1
							set posisi='$v' where  sub_menu_id=".$k;
					$ora->sql_no_fetch($qry,$bit_app["db"]); 
				}
			}
			
			$qry="select 
					count(1) 
				  from sub_menu_l1
				  where menu_id=".$_GET["id"]." $where";
			$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			$totalRow=$rs->value[1][1]; //total record	
		
			//==============================================
			$page=$_POST["page"];
			if ($page==0)
				$page=1;
			
			
			if ($_POST["cPage"])
				$page=(($_POST["slPage"]-1)*$bit_app["sumOfPage"])+1;
			
			if (!$_POST["slPage"])
				$_POST["slPage"]=1;
			
			
			
			$rownum2 = ($page*$bit_app["sumOfRow"])+1;
			$rownum1 = ($page-1)*$bit_app["sumOfRow"]+1;
			
			$paging=ceil($totalRow/$bit_app["sumOfRow"]);
			//=============================================
			
			$qry="select 
					sub_menu_id,sub_menu_name,tipe_content,content,private_flag,posisi
				  from sub_menu_l1 a
				  where menu_id=".$_GET["id"]." $where
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
					<td align="left" width="15%">
					<? 
						switch ((int)$rs->value[$i][3]) {
						case 1 :
								echo "URL";
								break;
						case 2 :
								echo "Kategori";
								break;
						case 3 :
								echo "File";
								break;
						case 4 :
								echo "Content";
								break;
						case 5 :
								echo "Do Nothing";
								break;
						}
					?>
					</td>
					<td width="3%" align="center"><input name="tPosisi[<? echo $rs->value[$i][1]?>]" type="text" value="<? echo $rs->value[$i]["posisi"]?>" size="3" maxlength="3" class="inputBox"></td>
					<td align="center" width="8%"><a href="#" onClick="var xWinSubMenu;xWinSubMenu=window.open('<? echo $bit_app["path_url"]."/bit_content/content/"?>sMenuList2.php?id=<? echo $rs->value[$i][1]?>&mn=<? echo $rs->value[$i][2]?>','xWinSubMenu','top=140,left=100,width=870,height=400,scrollbars=1,resizable=1');xWinSubMenu.focus()">edit Sub Menu</a></td>
					<td width="2%" align="center">
						<img style="cursor:pointer" title="Edit Menu" src="<? echo $bit_app["path_url"]?>/bit_images/edit.gif" onClick="var win; win=window.open('<? echo $bit_app["path_url"]."/bit_content/content/"?>sMenuEdit.php?id=<? echo $rs->value[$i][1]?>','win','top=130,left=100,height=450,width=600,resizable=1,scrollbars=1');win.focus()">
					</td>
					<td width="2%" align="center">
						<img style="cursor:pointer" title="Delete Menu" src="<? echo $bit_app["path_url"]?>/bit_images/hapus.png" onClick="var ans;ans=confirm('Anda yakin untuk menghapus Sub Menu [<? echo $rs->value[$i][2]?>] ?'); if (ans) {document.forms[0].hdAction.value=3;document.forms[0].id.value=<? echo $rs->value[$i][1]?>;document.forms[0].submit();}">
					</td>
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
	<? $f->button("add","Tambah SubMenu","button","var win;win=window.open('".$bit_app["path_url"]."/bit_content/content/sMenuAdd.php?id=".$_GET["id"]."','win','top=120,left=100,height=350,width=700,resizable=1,scrollbars=1');win.focus()")?>
	&nbsp;
	<? $f->button("refresh","Refresh","button","window.location=window.location.href")?>
	<br>
	<br>
	<? $f->button("button","Close","button","window.close()")?>
		
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


