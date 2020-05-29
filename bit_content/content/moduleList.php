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
	
	$qry="select distinct upper(substring(b.nama_karyawan,1,1)) from p_module a, m_master_user b where a.id=b.nik";
	$rsU=$ora->sql_fetch($qry,$bit_app["db"]);
	
			
?>
	<p align="left">
	<table cellpadding="1" cellspacing="1" border="0" width="100%">
	<tr>
		<td colspan="5">
			<? echo setTitleCari("Module");?>
		</td>
	</tr>
	</table>
	<table cellpadding="0" cellspacing="1" border="0" width="100%">
	<thead>
		<tr>
		<? $arrField=array("NO","Module") ?>
		<?
			for ($i=0;$i<count($arrField);$i++) {
				echo "<th class='listTableHead'>".$arrField[$i]."&nbsp;&nbsp;";
				echo "</th>";
			}
			echo "<th class='listTableHead'>Perintah</th>";
			if ($orderBy)
				$orderBy ="order by ".substring($orderBy,0,strlen($orderBy)-1);
			
		?>
		</tr>
	</thead>
	<tbody>
		<?
			
			switch ($_POST["hdAction"]) {
			case 2 :
				$qry="update p_module
						set active_flag=".((int)!$_POST["status"])." 
						where id=".(int)$_POST["id"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); break;
			}
			
			
			
			$qry="select 
					count(1) 
				  from p_module a
				  where 1=1 $where";
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
					a.id,a.module,a.active_flag
				  from p_module a
				  where 1=1 $where
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
					<td align="left"><? echo $rs->value[$i][2]?></td>
					<? if ($rs->value[$i][3]) { ?>
					<td align="center" width="5%">
						<img title="Disable Module" style="cursor:pointer"  src="<? echo $bit_app["path_url"]?>bit_images/tick.png" onClick="var ans;ans=confirm('Anda yakin untuk Mengunci(Lock) Module [<? echo $rs->value[$i][2]?>]  ?'); if (ans) {document.forms[0].hdAction.value=2;document.forms[0].status.value='<? echo $rs->value[$i][3]?>';document.forms[0].id.value=<? echo $rs->value[$i][1]?>;document.forms[0].submit();}">
					</td>
					<? } else {?>
					<td align="center" width="5%">
						<img title="Enable Module" style="cursor:pointer" src="<? echo $bit_app["path_url"]?>bit_images/checked_out.png" onClick="var ans;ans=confirm('Anda yakin untuk Aktifkan Module [<? echo $rs->value[$i][2]?>]  ?'); if (ans) {document.forms[0].hdAction.value=2;document.forms[0].status.value='<? echo $rs->value[$i][3]?>';document.forms[0].id.value=<? echo $rs->value[$i][1]?>;document.forms[0].submit();}">
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

