<?
	session_start();
	include_once("../../bit_config.php");
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
	$id=$_GET["id"];
	
	if ($_POST["tSearch"]!="Search") {
		$where =" and (upper(title) like '%".strtoupper($_POST["tSearch"])."%' or upper(description) like '%".strtoupper($_POST["tSearch"])."%')";
	}
	
	echo "<form method='POST' name='frList' id='frList'>";
	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td  align='left' class='bit_news_title'>FOTO</td></tr>";

	$qry="select count(1) from m_foto where publish_flag=1 $where";
	$rs=$ora->sql_fetch($qry,$bit_app["db"]);
	$totalRow=$rs->value[1][1];

	$sumOfRow=5;
	$sumOfPage=$bit_app["sumOfPage"];
	
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
				id,description,title,date_format(updated_date,'%W, %d/%m/%Y %h:%i') updated_date,
				funcGetUser(updated_by) updated_by 
			from m_foto where publish_flag=1 $where
			order by id desc
			limit ".($rownum-1).",".$sumOfRow."";
	$rsN=$ora->sql_fetch($qry,$bit_app["db"]);
	
	for ($i=1;$i<=$rsN->jumrec;$i++) {
		echo "<tr>";
		
		$qry="select * from m_foto_detail where foto_id=".$rsN->value[$i]["id"]." order by id asc limit 0,1";
		$rsFoto=$ora->sql_fetch($qry,$bit_app["db"]);
		
		echo "<td class='bit_row_news' valign='top' align='left'><a onClick='dtShow(".$rsN->value[$i]["id"].");dtRate(".$rsN->value[$i]["id"].");dtComment(".$rsN->value[$i]["id"].")' target='_parent' class='bit_row_title'><img width='64' height='64' src='".$bit_app["folder_url"]."/".$rsFoto->value[1]["foto_name"]."'  class='headimage'></a>";
			
		echo "<a onClick='dtShow(".$rsN->value[$i]["id"].");dtRate(".$rsN->value[$i]["id"].");dtComment(".$rsN->value[$i]["id"].")' target='_parent' class='bit_row_title'>".$rsN->value[$i]["title"]."</a><br>";
		echo "<span class='bit_row_date'>Tanggal : ".$rsN->value[$i]["updated_date"]."</span>";
		echo "</td></tr>";
	}
	
	echo "<tr><td align='right' class='bit_back'>";
	
	$tmp=ceil(($totalRow/$sumOfRow));
	if ((($_POST["slPage"]-1)*$sumOfPage)+$sumOfPage<=$tmp)
		$maxCount=(($_POST["slPage"]-1)*$sumOfPage)+$sumOfPage;
	else
		$maxCount=$tmp;
	
	if ($totalRow)	
		echo "page : ";
	
	
	for ($i=(($_POST["slPage"]-1)*$sumOfPage)+1;$i<=$maxCount;$i++) {
		if ($i!=$page)
			echo "&nbsp;<a class='bit_paging' onClick='document.frList.page.value=".$i.";dtList();'>$i</a>&nbsp;";	
		else
			echo "&nbsp;<b><font color='#FBF200'>$i</font></b>&nbsp;";	
	}
	
	if (ceil($paging/$sumOfPage)>1) {
	?>
	<select name="slPage" class="bit_input_list" onChange="document.frList.cPage.value=1;dtList();">
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
	
	echo "<input type='hidden' name='page' value=".$_POST["page"].">";
	echo "<input type='hidden' name='cPage' value='0'>";
	echo "<input type='hidden' name='tSearch' value='".$_POST["tSearch"]."'>";
	echo "</form>";
?>