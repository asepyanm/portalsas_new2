<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">

<?php
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data");
?>
	<table cellpadding="1" cellspacing="1" border="0" width="100%">
	<tr>
		<td colspan="5">
			<? echo setTitle("Nomor Telepon Pejabat Telkom");?>
		</td>
	</tr>
	</table>
	<table cellpadding="1" cellspacing="0">
		<tr>
			<td align="right">
				Cari : <input type="text" name="tSearch" size="30" class="inputBox" value="<? if ($_POST["tSearch"]) echo $_POST["tSearch"]; else echo "Search"?>" onMouseOver="if (this.value=='Search') this.value=''" onMouseOut="if (this.value=='') this.value='Search'">
				<input type="submit" class="genric-btn primary circle" name="sGo"  onClick="document.forms[0].page.value=1"  value="Go">
				(Pencarian terhadap Nama,NIK,Jabatan,Loker,Telepon)
			</td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="1" border="0" width="100%" class="table"
	<thead>
		<tr>
			<th rowspan="2" class="listTableHead">NO</th>	
			<th rowspan="2" class="listTableHead">NAMA</th>	
			<th rowspan="2" class="listTableHead">NIK</th>	
			<th rowspan="2" class="listTableHead">JABATAN</th>	
			<th rowspan="2" class="listTableHead">LOKER</th>	
			<th colspan="3" class="listTableHead">TELEPON</th>	
		</tr>
		<tr>
			<th class="listTableHead">KANTOR</th>	
			<th class="listTableHead">HP</th>	
			<th class="listTableHead">FLEXI</th>	
		</tr>
	</thead>
	<tbody>
		<?
			$ora=new clsMysql;
			$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
			
			switch ($_POST["hdAction"]) {
			case 3 :
				$qry="delete from m_telp_pejabat_telkom
						where id=".(int)$_POST["id"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); break;
			}
			
			if ($_POST["sGo"]) {
				$where = " and (
							nama like '%".$_POST["tSearch"]."%'
						    or
							nik like '%".$_POST["tSearch"]."%'
						    or
							jabatan like '%".$_POST["tSearch"]."%'
						    or
							loker like '%".$_POST["tSearch"]."%'
						    or
							kantor like '%".$_POST["tSearch"]."%'
						    or
						  	hp like '%".$_POST["tSearch"]."%'
						    or
							flexi like '%".$_POST["tSearch"]."%'
						)";
			}
			
			$qry="select 
					count(1) 
				  from m_telp_pejabat_telkom
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
					*
				  from m_telp_pejabat_telkom a
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
					<td align="center" width="2%" valign="top"><? echo ((($page-1)*$bit_app["sumOfRow"])+$i)?></td>
					<td align="left" width="20%" valign="top"><? echo $rs->value[$i][2]?></td>
					<td align="center" width="5%" valign="top"><? echo $rs->value[$i][3]?></td>
					<td align="center" width="30%" valign="top"><? echo $rs->value[$i][4]?></td>
					<td align="center" width="30%" valign="top"><? echo $rs->value[$i][5]?></td>
					<td align="center" width="5%" nowrap="nowrap" valign="top"><? echo $rs->value[$i][6]?></td>
					<td align="center" width="5%" nowrap="nowrap" valign="top"><? echo $rs->value[$i][7]?></td>
					<td align="center" width="5%" nowrap="nowrap" valign="top"><? echo $rs->value[$i][8]?></td>
			</tr>	
		<? } ?>
	</tbody>
	</table>
	<table width="100%" class="table">
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


