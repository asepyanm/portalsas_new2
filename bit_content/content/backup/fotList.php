<html>
<head>
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
</head>
<body leftmargin="0" topmargin="0">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data");
?>
	<table cellpadding="1" cellspacing="1" border="0" width="100%">
	<tr>
		<td colspan="5">
			<? echo setTitleCari("Foto");?>
		</td>
	</tr>
	</table>
	
	<table cellpadding="1" cellspacing="0">
		<tr>
			<td align="left">
				<input type="text" name="tSearch" size="30" class="inputtext" value="<? if ($_POST["tSearch"]) echo $_POST["tSearch"]; else echo "Search"?>" onMouseOver="if (this.value=='Search') this.value=''" onMouseOut="if (this.value=='') this.value='Search'">
				&nbsp;
			</td>
			<td><input type="submit" class="button"  onClick="document.forms[0].page.value=1"  value="Search"></td>
		</tr>
	</table>
	<hr class="article_layout_hr">
	<table cellpadding="0" cellspacing="1" border="0" width="100%">
	<thead>
		<tr>
		<? $arrField=array("NO","Title","Foto","Tanggal") ?>
		<?
			for ($i=0;$i<count($arrField);$i++) {
				echo "<th class='listTableHead'>".$arrField[$i]."&nbsp;&nbsp;";
				if ($i!=0 && $i!=2) {
					switch ($_POST["hdOrder"][$i]) {
					case 1 : 
						echo "<img src='".$bit_app["path_url"]."/bit_images/sort_asc.png' ";
						echo "onClick='document.forms[0].hdOrder[$i].value=2;document.forms[0].submit();'";
						echo ">";
						break;
					case 2 :
						echo "<img src='".$bit_app["path_url"]."/bit_images/sort_desc.png' ";
						echo "onClick='document.forms[0].hdOrder[$i].value=1;document.forms[0].submit();'";
						echo ">";
						break;
					default : 						
						echo "<img src='".$bit_app["path_url"]."/bit_images/sort_none.png' ";
						echo "onClick='document.forms[0].hdOrder[$i].value=2;document.forms[0].submit();'";
						echo ">";
					}
				}
				if ($_POST["hdOrder"][$i]==1)
					$orderBy .=($i+1)." asc,"; 
				elseif ($_POST["hdOrder"][$i]==2)
					$orderBy .=($i+1)." desc,"; 
				echo "</th>";
			}
			echo "<th colspan=4 class='listTableHead'>Perintah</th>";
			if ($orderBy)
				$orderBy ="order by ".substr($orderBy,0,strlen($orderBy)-1);
			else
				$orderBy ="order by 1 desc";
			
		?>
		</tr>
	</thead>
	<tbody>
		<?
			$ora=new clsMysql;
			$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
			
			switch ($_POST["hdAction"]) {
			case 3 :
				$qry="delete from m_foto
						where id=".(int)$_POST["id"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); break;
			}
			
			
			if ($_POST["tSearch"] && $_POST["tSearch"]!="Search") {
				$where .=" and (upper(title) like '%".strtoupper($_POST["tSearch"])."%')";
			}
			
			$qry="select 
					count(1) 
				  from m_foto
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
					id,title,description,created_date,date_format(created_date,'%d-%m-%Y')
				  from m_foto a
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
					<td align="center" width="5%" valign="top"><? echo ((($page-1)*$bit_app["sumOfRow"])+$i)?></td>
					<td align="left" width="30%" valign="top"><? echo $rs->value[$i][2]?></td>
					<td align="left" width="40%">
					<?
						unset($rsDetail);
						$qry="select foto_name from m_foto_detail where foto_id=".$rs->value[$i][1];
						$rsDetail=$ora->sql_fetch($qry,$bit_app["db"]);
						
						for ($j=1;$j<=$rsDetail->jumrec;$j++) {
							echo "<a  target='_blank' href='".$bit_app["folder_url"].$rsDetail->value[$j][1]."' class='bit_row_title'><img class='bit_foto_image' width='40' src='".$bit_app["folder_url"].$rsDetail->value[$j][1]."'></a>&nbsp;";
						}
					?>
					</td>
					<td align="left" width="12%" valign="top"><? echo $rs->value[$i][4]?></td>
					<td width="1%" valign="top" align="center">	
						<img title="Edit Foto" style="cursor:pointer" src="<? echo $bit_app["path_url"]?>/bit_images/edit.gif" onClick="var win; win=window.open('<? echo $bit_app["path_url"]."/bit_content/content/"?>fotEdit.php?id=<? echo $rs->value[$i][1]?>','win','top=10,left=100,height=550,width=600,resizable=1,scrollbars=1');win.focus()">
					</td>
					<td width="1%" valign="top" align="center">
						<img style="cursor:pointer" src="<? echo $bit_app["path_url"]?>/bit_images/hapus.png" onClick="var ans;ans=confirm('Anda yakin untuk menghapus Foto [<? echo $rs->value[$i][2]?>] ?'); if (ans) {document.forms[0].hdAction.value=3;document.forms[0].id.value=<? echo $rs->value[$i][1]?>;document.forms[0].submit();}">
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
		</td>
	</table>
	<hr class="article_layout_hr">
	<p align="right">
	<? $f->button("add","Tambah","button","var win;win=window.open('".$bit_app["path_url"]."/bit_content/content/fotAdd.php','win','top=10,left=100,height=550,width=600,resizable=1,scrollbars=1');win.focus()")?>
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

