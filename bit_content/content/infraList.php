<?
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);

	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data");
?>
	<table cellpadding="1" cellspacing="1" border="0" width="100%">
	<tr>
		<td colspan="5">
			<? echo setTitle2("Pengembangan Infrastruktur");?>
		</td>
	</tr>
	</table>
	<table cellpadding="1" cellspacing="0">
		<tr>
			<td>Judul </td>
			<td align="left">
				: <input type="text" name="tSearch" size="30" class="inputBox" value="<? if ($_POST["tSearch"]) echo $_POST["tSearch"]; else echo "Search Judul"?>" onMouseOver="if (this.value=='Search Judul') this.value=''" onMouseOut="if (this.value=='') this.value='Search Judul'">
				&nbsp;
			</td>
		</tr>
		<tr>
			<td>Author </td>
			<td align="left">
				: <?
					$qry="select created_by_info,count(1) from m_infra group by created_by_info";
					$rsFilter=$ora->sql_fetch($qry,$bit_app["db"]);
					echo "<select name='slAuthor' class='inputBox'>";
					echo "<option value=''>All</option>";
					for ($i=1;$i<=$rsFilter->jumrec;$i++) {
						$arrData=explode("/",$rsFilter->value[$i][1]);
						if ($rsFilter->value[$i][1]==$_POST["slAuthor"])
							echo "<option selected value='".$rsFilter->value[$i][1]."'>".$arrData[0]."</option>";
						else
							echo "<option value='".$rsFilter->value[$i][1]."'>".$arrData[0]."</option>";
						
					}
					echo "</select>";
				?>
			</td>
		</tr>
		<tr>
			<td>Publisher </td>
			<td align="left">
				: <?
					$qry="select publish_by_info,count(1) from m_infra where publish_by_info is not null group by publish_by_info";
					$rsFilter=$ora->sql_fetch($qry,$bit_app["db"]);
					echo "<select name='slPublisher' class='inputBox'>";
					echo "<option value=''>All</option>";
					for ($i=1;$i<=$rsFilter->jumrec;$i++) {
						$arrData=explode("/",$rsFilter->value[$i][1]);
						if ($rsFilter->value[$i][1]==$_POST["slPublisher"])
							echo "<option selected value='".$rsFilter->value[$i][1]."'>".$arrData[0]."</option>";
						else
							echo "<option value='".$rsFilter->value[$i][1]."'>".$arrData[0]."</option>";
					}
					echo "</select>";
				?>
			</td>
		</tr>
		<tr>
			<td>Owner </td>
			<td align="left">
				: <?
					unset($arrAuthor);
					$qry="select created_by_info,count(1) from m_infra group by created_by_info";
					$rsFilter=$ora->sql_fetch($qry,$bit_app["db"]);
					for ($i=1;$i<=$rsFilter->jumrec;$i++) {
						$arrData=explode("/",$rsFilter->value[$i][1]);
						$arrAuthor[trim($arrData[2])]=1;
					}
					
					
					echo "<select name='slOwner' class='inputBox'>";
					echo "<option value=''>All</option>";
					while (list($k,$v)=each($arrAuthor)) {
						if ($k==$_POST["slOwner"])
							echo "<option selected value='".$k."'>".$k."</option>";
						else
							echo "<option value='".$k."'>".$k."</option>";
					}
					echo "</select>";
				?>
			</td>
		</tr>
		<tr>
			<td>Status </td>
			<td align="left">
				: <?
					$status=array(0=>"ALL",1=>"Menunggu Approved",2=>"Approved",3=>"Reject");
					echo "<select name='slStatus' class='inputBox'>";
					while (list($k,$v)=each($status)) {
						if ($k==$_POST["slStatus"])
							echo "<option selected value='".$k."'>".$v."</option>";
						else
							echo "<option value='".$k."'>".$v."</option>";
					}
					echo "</select>";
				?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>&nbsp;&nbsp;<input type="submit" class="button"  onClick="document.forms[0].page.value=1"  value="Search"></td>
		</tr>
	</table>
	<hr class="article_layout_hr">
	<table cellpadding="0" cellspacing="1" border="0" width="100%">
	<thead>
		<tr>
		<? $arrField=array("No","Judul Berita","Hit","Tgl.Submit","Author","Tgl.Publish","Publisher","Owner") ?>
		<?
			for ($i=0;$i<count($arrField);$i++) {
				echo "<th class='listTableHead'>".$arrField[$i]."&nbsp;&nbsp;";
				echo "</th>";
			}
			echo "<th class='listTableHead' colspan=3>Perintah</th>";
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
				$qry="delete from m_infra
						where id=".$_POST["id"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); break;
			case 4 :
				$qry="update m_infra
						set publish_flag=".((int)!$_POST["status"])." 
						where id=".$_POST["id"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); break;
			}
			
			if ($_POST["tSearch"] && $_POST["tSearch"]!="Search Judul") {
				$where .=" and (upper(title) like '%".strtoupper($_POST["tSearch"])."%')";
			}
			
			if ($_POST["slAuthor"]) {
				$where .=" and created_by_info='".$_POST["slAuthor"]."'";
			}
			
			if ($_POST["slPublisher"]) {
				$where .=" and publish_by_info='".$_POST["slPublisher"]."'";
			}
			
			if ($_POST["slOwner"]) {
				$where .=" and created_by_info like '%".$_POST["slOwner"]."%'";
			}
			
			
			switch ($_POST["slStatus"]) {
			case 1 : 
				$where .=" and publish_flag = 0 and status=1";
				break;	
			case 2 : 
				$where .=" and publish_flag = 1";
				break;	
			case 3 : 
				$where .=" and publish_flag = 0 and status = 2";
				break;	
			}
			
			
			$qry="select 
					count(1) 
				  from m_infra
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
					id,title,
					hits,created_date,created_by,publish_date,publish_by_info,created_by_info
				  from m_infra a
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
					
				$arrAuthor=explode("/",$rs->value[$i][8]);
				$arrPublisher=explode("/",$rs->value[$i][7]);
				
		?>			
				<tr class="<? echo $cl?>">
					<td align="center" width="5%"><? echo ((($page-1)*$bit_app["sumOfRow"])+$i)?></td>
					<td align="left" width="40%"><? echo $rs->value[$i][2]?></td>
					<td align="center" width="5%"><? echo format($rs->value[$i][3])?></td>
					<td align="left" width="10%"><? echo $rs->value[$i][4]?></td>
					<td align="left" width="10%"><? echo $arrAuthor[0]?></td>
					<td align="left" width="10%"><? echo $rs->value[$i][6]?></td>
					<td align="left" width="10%"><? echo $arrPublisher[0]?></td>
					<td align="left" width="10%"><? echo $arrAuthor[2]?></td>
					<td width="1%" align="center">
						<img title="Edit Info" style="cursor:pointer" src="<? echo $bit_app["path_url"]?>/bit_images/edit.gif" onClick="var win; win=window.open('<? echo $bit_app["path_url"]."/bit_content/infra/"?>edit.php?id=<? echo $rs->value[$i][1]?>','win','top=10,left=100,height=400,width=600,resizable=1,scrollbars=1');win.focus()">
					</td>
					<td width="1%" align="center">
						<img title="Hapus Info" style="cursor:pointer" src="<? echo $bit_app["path_url"]?>/bit_images/hapus.png" onClick="var ans;ans=confirm('Anda yakin untuk menghapus Info [<? echo $rs->value[$i][2]?>] ?'); if (ans) {document.forms[0].hdAction.value=3;document.forms[0].id.value=<? echo $rs->value[$i][1]?>;document.forms[0].submit();}">
					</td>
					<td width="1%" align="center">
						<img title="Detail" style="cursor:pointer" src="<? echo $bit_app["path_url"]?>/bit_images/detail.gif" onClick="var win; win=window.open('<? echo $bit_app["path_url"]."/bit_content/infra/"?>det.php?id=<? echo $rs->value[$i][1]?>','win','top=10,left=100,height=650,width=600,resizable=1,scrollbars=1');win.focus()">
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
	<?
		for ($i=0;$i<count($arrField);$i++) {
			echo "<input type='hidden' name='hdOrder[$i]' id='hdOrder' value='".$_POST["hdOrder"][$i]."'>";
		}
	?>
<? $f->closeForm();
   $ora->logoff(); 
?>
