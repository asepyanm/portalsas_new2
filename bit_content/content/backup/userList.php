<html>
<head>
<title><? echo $bit_app["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
<?
	getCalendarModule();
?>
<script>
	function validate() {
		if (document.forms[0].tInput[0].value=='') {
			alert('Judul silahkan diisi terlebih dahulu !');
			document.forms[0].tInput[0].focus();
			return false;
		}
		
		return true;
	}
</script>
</head>
<body leftmargin="0" topmargin="0">
<?
	
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data");
	
	$ora=new clsMysql;
		$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
	$qry="select distinct upper(substring(b.nama_karyawan,1,1)) from m_users a, m_master_user b where a.user_id=b.nik";
	$rsU=$ora->sql_fetch($qry,$bit_app["db"]);
	
			
?>
	<p align="left">
	<table cellpadding="1" cellspacing="1" border="0" width="100%">
	<tr>
		<td colspan="5">
			<? echo setTitleCari("User");?>
		</td>
	</tr>
	</table>
	
	<table>
		<tr>
			<td>Mencari data user berdasarkan huruf abjact : </td>
			<?	
				for ($i=1;$i<=$rsU->jumrec;$i++) {
					echo "<td><b><a href='#' onClick='document.forms[0].abj.value=\"".$rsU->value[$i][1]."\";document.forms[0].submit()'> ".$rsU->value[$i][1]."</a></b></td>";
				}
			?>	
		</tr>
	</table>
	
	<table cellpadding="1" cellspacing="0">
		<tr>
			<td align="left">
				<input type="text" name="tSearch" size="30" class="inputtext" value="<? if ($_POST["tSearch"]) echo $_POST["tSearch"]; else echo "Search"?>" onMouseOver="if (this.value=='Search') this.value=''" onMouseOut="if (this.value=='') this.value='Search'">
			</td>
			<td><input type="submit" class="button"  onClick="document.forms[0].page.value=1"  value="Search "></td>
		</tr>
	</table>
	<hr class="article_layout_hr">
	<table cellpadding="0" cellspacing="1" border="0" width="100%">
	<thead>
		<tr>
		<? $arrField=array("NO","NIK","Nama","E-mail","Tipe User") ?>
		<?
			for ($i=0;$i<count($arrField);$i++) {
				echo "<th class='listTableHead'>".$arrField[$i]."&nbsp;&nbsp;";
				if ($i!=0) {
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
				$orderBy ="order by ".substring($orderBy,0,strlen($orderBy)-1);
			
		?>
		</tr>
	</thead>
	<tbody>
		<?
			
			switch ($_POST["hdAction"]) {
			case 3 :
				$qry="delete from m_users
						where user_id=".(int)$_POST["id"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); break;
			case 2 :
				$qry="update m_users
						set user_active_flag=".((int)!$_POST["status"])." 
						where user_id=".(int)$_POST["id"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); break;
			}
			
			
			if ($_POST["abj"]) {
				$where .=" and upper(substring(nama_karyawan,1,1))='".strtoupper($_POST["abj"])."'";
			}
			
			if ($_POST["tSearch"] && $_POST["tSearch"]!="Search") {
				$where .=" and (upper(nama_karyawan) like '%".strtoupper($_POST["tSearch"])."%' or upper(nik) like '%".strtoupper($_POST["tSearch"])."%')";
			}
			
			
			$qry="select 
					count(1) 
				  from m_users a, m_master_user b
				  where a.user_id=b.nik $where";
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
					a.user_id,funcGetUser(a.user_id),user_active_flag,user_level
				  from m_users a, m_master_user b
				  where a.user_id=b.nik
				  		$where
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
					<td align="center"><? echo ((($page-1)*$bit_app["sumOfRow"])+$i)?></td>
					<td align="left"><? echo $rs->value[$i][1]?></td>
					<td align="left"><? echo $rs->value[$i][2]?></td>
					<td align="left"><? echo $rs->value[$i][1]."@telkom.co.id"?></td>
					<td align="left">
						<? 
							switch ($rs->value[$i][4]) {
							case 1 : 
								echo "User"; break;
							case 2 : 
								echo "Author"; break;
							case 3 : 
								echo "Publisher"; break;
							case 4 : 
								echo "Administrator"; break;
							}
						?>
					</td>
					<td>
						<img title="Edit User" style="cursor:pointer" src="<? echo $bit_app["path_url"]?>bit_images/edit.gif" onClick="var win; win=window.open('<? echo $gHomePageUrl?>content/userEdit.php?id=<? echo $rs->value[$i][1]?>','win','left=100,top=100,height=340,width=700,resizable=1,scrollbars=1')">
					</td>
					<td>
						<img title="Hapus User" style="cursor:pointer" src="<? echo $bit_app["path_url"]?>bit_images/hapus.png" onClick="var ans;ans=confirm('Anda yakin untuk menghapus user [<? echo $rs->value[$i][3]?>]  ?'); if (ans) {document.forms[0].hdAction.value=3;document.forms[0].id.value=<? echo $rs->value[$i][1]?>;document.forms[0].submit();}">
					</td>
					<td>
						<img title="Detail User" style="cursor:pointer" src="<? echo $bit_app["path_url"]?>bit_images/detail.gif" onClick="var win; win=window.open('<? echo $gHomePageUrl?>content/userDet.php?id=<? echo $rs->value[$i][1]?>','win','left=100,top=100,height=340,width=700,resizable=1,scrollbars=1')">
					</td>
					<? if ($rs->value[$i][3]) { ?>
					<td>
						<img title="Disable User" style="cursor:pointer"  src="<? echo $bit_app["path_url"]?>bit_images/tick.png" onClick="var ans;ans=confirm('Anda yakin untuk Mengunci(Lock) user [<? echo $rs->value[$i][2]?>]  ?'); if (ans) {document.forms[0].hdAction.value=2;document.forms[0].status.value='<? echo $rs->value[$i][3]?>';document.forms[0].id.value=<? echo $rs->value[$i][1]?>;document.forms[0].submit();}">
					</td>
					<? } else {?>
					<td>
						<img title="Enable User" style="cursor:pointer" src="<? echo $bit_app["path_url"]?>bit_images/checked_out.png" onClick="var ans;ans=confirm('Anda yakin untuk Aktifkan user [<? echo $rs->value[$i][2]?>]  ?'); if (ans) {document.forms[0].hdAction.value=2;document.forms[0].status.value='<? echo $rs->value[$i][3]?>';document.forms[0].id.value=<? echo $rs->value[$i][1]?>;document.forms[0].submit();}">
					</td>
					<? } ?>
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
			<input type="hidden" name="abj" value="<? echo $_POST["abj"]?>">
			<input type="hidden" name="status">
			<input type="hidden" name="id" value="0">
			<input type="hidden" name="idBB" value="0">
			
		</td>
	</table>
	</p>
	<hr class="article_layout_hr">
	<p align="right">
	<? $f->button("add","Tambah","button","window.open('".$bit_app["path_url"]."/bit_content/content/userAdd.php','win','height=340,width=600,resizable=1,scrollbars=1')")?>
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

