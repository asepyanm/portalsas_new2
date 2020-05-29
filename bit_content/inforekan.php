<?
	session_start();
	include_once("../bit_config.php");
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	if ($_POST["tSearch"]!="Search") {
		$where =" and (upper(nama_karyawan) like '%".strtoupper($_POST["tSearch"])."%' or upper(nik) like '%".strtoupper($_POST["tSearch"])."%')";
	}
	
	if ($_POST["tSearch"]=="" || $_POST["tSearch"]=="Search") {
		$where =" and (upper(nama_karyawan) like '%999999%')";
	}
	
	$qry="select 
				count(1) from m_master_user 
			where 
				1=1 $where";
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	$totalRow=$rs->value[1][1];

	$sumOfRow=4;
	$sumOfPage=5;
	
	//==============================================
	$page=$_POST["page"];
	if ($page==0)
		$page=1;
	else
		$page=$_POST["page"];
	
	if ($_POST["cPage"])
		$page=(($_POST["slPage"]-1)*$sumOfPage)+1;
	
	if (!$_POST["slPage"])
		$_POST["slPage"]=1;
	
	$rownum = ($page-1)*$sumOfRow+1;
	
	$paging=ceil($totalRow/$sumOfRow);
	//=============================================
	$qry="select 
				nama_karyawan,loker2,nik
			from m_master_user
			where 
				1=1 $where
			order by nama_karyawan asc
			limit ".($rownum-1).",".$sumOfRow."";
	$rsN=$ora->sql_fetch($qry,$bit_app["db"]);
	
	echo "<table cellpadding=0 cellspacing=0 width=100%>";
	for ($i=1;$i<=$rsN->jumrec;$i++) {
		echo "<tr><td class='bit_row_news' align='left'>";
		echo "<a class='bit_info_rekan' onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'420px', inline:false, href:'bit_content/content/viewProfile.php?userid=".$rsN->value[$i]["nik"]."'})\">".$rsN->value[$i]["nama_karyawan"]." / ".$rsN->value[$i]["loker2"]." / ".$rsN->value[$i]["nik"]."</a>";
		echo "</td></tr>";
	}
	
	echo "<tr><td align='right' class='bit_back' bgcolor='#051E52'>";
	
	$tmp=ceil(($totalRow/$sumOfRow));
	if ((($_POST["slPage"]-1)*$sumOfPage)+$sumOfPage<=$tmp)
		$maxCount=(($_POST["slPage"]-1)*$sumOfPage)+$sumOfPage;
	else
		$maxCount=$tmp;
	
	if ($totalRow)	
		echo "page : ";
	
	
	for ($i=(($_POST["slPage"]-1)*$sumOfPage)+1;$i<=$maxCount;$i++) {
		if ($i!=$page)
			echo "&nbsp;<a class='bit_paging' onClick='document.frInfoRekan.page.value=".$i.";dtInfoRekan();'>$i</a>&nbsp;";	
		else
			echo "&nbsp;<b><font color='#FBF200'>$i</font></b>&nbsp;";	
	}
	
	if (ceil($paging/$sumOfPage)>1) {
	?>
	<select name="slPage" class="bit_input_list" onChange="document.frInfoRekan.cPage.value=1;dtInfoRekan();">
		<?
			
			for ($i=1;$i<=ceil($paging/$sumOfPage);$i++) {
				if ($_POST["slPage"]==$i) 
					echo "<option selected value='$i'>Page $i</option>";
				else
					echo "<option value='$i'>Page $i</option>";
			}
		?>
	</select>
	<? } 
	
	echo "</td></tr>";
	echo "</table>";
	
	echo "<input type='hidden' name='page'>";
	echo "<input type='hidden' name='cPage' value='0'>";
?>