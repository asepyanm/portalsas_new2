<?php
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
	$field="nama,nik,jabatan,loker,area,kantor,hp";
		
	if ($_POST["ok"] && $_FILES["tFile"]["error"]==0) {
		#UPLOAD FILES
		$file=do_upload("tFile");
		
		$file="../bit_folder/".$file;
		
		#$qry="delete from m_telp_pejabat";
		#$ora->sql_no_fetch($qry,$bit_app["db"]);
		$objReader = new PHPExcel_Reader_Excel5();
		
		$objPHPExcel = $objReader->load($file); 
		#Set active sheet
		$com_acentos=array(
			"�","�","�","�",
			"�","�","�","�",
			"�","�","�","�",
			"�","�","�","�",
			"�","�","�","�",
			"�","�","�","�",
			"�","�");
		
		#getCalculatedValue
		
		$objPHPExcel->setActiveSheetIndex(0);
		
				
		$iJumField= count(explode(",",$field));
		
		$i=5; //Tanpa Header
		while ($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$i)->getvalue()) {
			$valueRow="";
			for ($j=0;$j<$iJumField;$j++) {
				$valueRow_=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($j,$i)->getvalue();
				$valueRow_=str_replace(",",".",$valueRow_);
				$valueRow_=str_replace("'","''",$valueRow_);
				$valueRow_=str_replace($com_acentos,"",$valueRow_);
				if ($j==1)
					$nik=$valueRow_;
				$valueRow .="'".trim($valueRow_)."',";
			}
			
			$i++;
			
			$valueRow=substr($valueRow,0,strlen($valueRow)-1);
			
			$qry="select * from m_telp_pejabat where nik='".$nik."'";
			$rsC=$ora->sql_fetch($qry,$bit_app["db"]);;
			if ($rsC->jumrec<=0) {
				$qry="insert into m_telp_pejabat ($field) values(".$valueRow.")";
				$ora->sql_no_fetch($qry,$bit_app["db"]);
			} else {
				$errorMsg .="<font color='#FF0000'>data untuk nik <b>".$nik."</b> sudah ada di database !</font><br />";
			}
		}
	}
?>
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
			<? echo setTitleCari("Nomor Telepon Personil SAS");?>
		</td>
	</tr>
	<tr>
		<td>	
			<? $f->file("tFile","tFile",$_POST["tNama"],"inputBox",20,255)?>
			<? $f->submit("ok","u p l o a d","button")?>
			<a title="Contoh Template Excel" href="content/template/telp_personil_sas.xls" target="_blank">download contoh template excel</a>
			<? echo "<br /><br />".$errorMsg; ?>
		</td>
	</tr>
	</table>
	<table cellpadding="1" cellspacing="0">
		<tr>
			<td align="right">
				Cari : <input type="text" name="tSearch" size="30" class="inputBox" value="<? if ($_POST["tSearch"]) echo $_POST["tSearch"]; else echo "Search"?>" onMouseOver="if (this.value=='Search') this.value=''" onMouseOut="if (this.value=='') this.value='Search'">
				<input type="submit" class="button" name="sGo"  onClick="document.forms[0].page.value=1"  value="Go">
				(Pencarian terhadap Nama,NIK,Jabatan,Loker,Area,Telepon)
			</td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="1" border="0" width="100%">
	<thead>
		<tr>
			<th rowspan="2" class="listTableHead">NO</th>	
			<th rowspan="2" class="listTableHead">NAMA</th>	
			<th rowspan="2" class="listTableHead">NIK</th>	
			<th rowspan="2" class="listTableHead">JABATAN</th>	
			<th rowspan="2" class="listTableHead">LOKER</th>	
			<th rowspan="2" class="listTableHead">AREA</th>	
			<th colspan="2" class="listTableHead">TELEPON</th>	
			<th rowspan="2" colspan="2" class="listTableHead">PERINTAH</th>	
		</tr>
		<tr>
			<th class="listTableHead">KANTOR</th>	
			<th class="listTableHead">HP</th>	
			<!-- <th class="listTableHead">FLEXI</th> //-->	
		</tr>
	</thead>
	<tbody>
		<?
			
			switch ($_POST["hdAction"]) {
			case 3 :
				$qry="delete from m_telp_pejabat
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
							area like '%".$_POST["tSearch"]."%'
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
				  from m_telp_pejabat
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
					id,nama,nik,jabatan,loker,area,kantor,hp
				  from m_telp_pejabat a
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
					<td align="center" width="20%" valign="top"><? echo $rs->value[$i][4]?></td>
					<td align="center" width="20%" valign="top"><? echo $rs->value[$i][5]?></td>
					<td align="center" width="5%" valign="top"><? echo $rs->value[$i][6]?></td>
					<td align="center" width="5%" nowrap="nowrap" valign="top"><? echo $rs->value[$i][7]?></td>
					<td align="center" width="5%" nowrap="nowrap" valign="top"><? echo $rs->value[$i][8]?></td>
					<!-- <td align="center" width="5%" nowrap="nowrap" valign="top"><? echo $rs->value[$i][9]?></td> //-->
					<td width="1%" align="center">
						<img style="cursor:pointer" title="Edit Data" src="<? echo $bit_app["path_url"]?>/bit_images/edit.gif" onClick="var win; win=window.open('<? echo $bit_app["path_url"]."/bit_content/content/"?>telp1Edit.php?id=<? echo $rs->value[$i][1]?>','win','top=10,left=100,height=320,width=600,resizable=1,scrollbars=1');win.focus()">
					</td>
					<td width="1%" align="center">
						<img style="cursor:pointer" title="Delete Data" src="<? echo $bit_app["path_url"]?>/bit_images/hapus.png" onClick="var ans;ans=confirm('Anda yakin untuk menghapus ?'); if (ans) {document.forms[0].hdAction.value=3;document.forms[0].id.value=<? echo $rs->value[$i][1]?>;document.forms[0].submit();}">
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
	<? $f->button("add","Tambah","button","var win;win=window.open('".$bit_app["path_url"]."/bit_content/content/telp1Add.php','win','top=10,left=100,height=320,width=600,resizable=1,scrollbars=1');win.focus()")?>
	&nbsp;
	<? $f->button("refresh","Refresh","button","window.location=window.location.href")?>
	</p>
	<?
		for ($i=0;$i<count($arrField);$i++) {
			echo "<input type='hidden' name='hdOrder[$i]' id='hdOrder' value='".$_POST["hdOrder"][$i]."'>";
		}
	?>
<? $f->closeForm(); ?>
</body>
</html>
<? $ora->logoff(); ?> 



