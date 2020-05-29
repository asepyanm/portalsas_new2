<?
	session_start();
	include_once("../../bit_config.php");
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
	$dtUser=getUserDB();

	$id=$_GET["id"];
	
	if ($_POST["hID"]) {
		$qry="delete from m_news_comment where id='".$_POST["hID"]."'";
		$ora->sql_no_fetch($qry,$bit_app["db"]);
	}
	
	if ($_POST["tComment"] && $_POST["hID"]=="") {
		while (list($k,$v)=each($_POST["tComment"])) {
			if ($v) {
				$qry="insert into m_news_comment(comment,updated_date,updated_by,news_id)
						values('$v',sysdate(),'".getUserID()."','$k')";
				$ora->sql_no_fetch($qry,$bit_app["db"]);
			}
		}
	}
	echo "<form method='POST' name='frContent' id='frContent'>";
	echo "<table cellpadding=0 border=0 cellspacing=0 width=100%>";
	echo "<tr><td class='bit_news_title' colspan=3>Hasil Komentar</td></tr>";
	$qry="select 
				count(1)
			from 
				m_news_comment
			where 
				news_id=".$id."
				$where";
	$rsTotal=$ora->sql_fetch($qry,$bit_app["db"]);
	$totalRow=$rsTotal->value[1][1];
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
				comment,updated_date,updated_by,id
			from 
				m_news_comment a
			where
				news_id=".$id."
			order by 
				id asc
			limit ".($rownum-1).",".$sumOfRow."";
	$rsC=$ora->sql_fetch($qry,$bit_app["db"]);
	
	for ($j=1;$j<=$rsC->jumrec;$j++) {
		echo "<tr>
					<td align='left' class='bit_row_news' width='40%'><img class='bit_image' src='".$dtUser[$rsC->value[$j]["updated_by"]][3]."' width='60' height='60'> <b>".$dtUser[$rsC->value[$j]["updated_by"]][1]."</b> <br />".$rsC->value[$j]["updated_date"]."</td>
					<td align='left' class='bit_row_news_odd' valign='top' width='59%'>";
					echo nl2br($rsC->value[$j]["comment"]);
							
		echo "		</td>";
		if (getUserLevel()==4) {
			echo "	<td valign='top' width='1px' class='bit_row_news_odd'><a title='Delete Komentar' onClick='document.frContent.hID.value=".$rsC->value[$j][4].";dtComment($id);'>X</a></td>";
		}
		echo "	</tr>";
	}
	
	echo "<tr><td align='right' colspan=3 class='bit_back'>";
	
	$tmp=ceil(($totalRow/$sumOfRow));
	if ((($_POST["slPage"]-1)*$sumOfPage)+$sumOfPage<=$tmp)
		$maxCount=(($_POST["slPage"]-1)*$sumOfPage)+$sumOfPage;
	else
		$maxCount=$tmp;
	
	if ($totalRow)	
		echo "page : &nbsp;&nbsp;";
	
	for ($i=(($_POST["slPage"]-1)*$sumOfPage)+1;$i<=$maxCount;$i++) {
		if ($i!=$page)
			echo "&nbsp;<a class='bit_paging' onClick='document.frContent.page.value=".$i.";dtComment($id);'>$i</a>&nbsp;";	
		else
			echo "&nbsp;<b><font color='#FBF200'>$i</font></b>&nbsp;";	
	}
	
	if (ceil($paging/$sumOfPage)>1) {
	?>
	<select name="slPage" class="bit_input_list" onChange="document.frContent.cPage.value=1;dtList();">
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
	
	if (getUserID()) {
	echo "<tr>
				<td  colspan=3 valign='top' align='left' class='bit_comment'>Comment : </td>
			</tr>
			<tr>
				<td  colspan=3 valign='top' align='left'>
					<textarea class='bit_input'  name='tComment[".$id."]' id='tComment' cols=40 rows=3></textarea>
					<br />
					<input type='button' value='Reply' name='bComment' onClick='dtComment($id);'>
				</td>
			</tr>";
	}
	echo "</table>";
	
	echo "<input type='hidden' name='hID'>";
	echo "<input type='hidden' name='page'>";
	echo "<input type='hidden' name='cPage' value='0'>";
	echo "</form>";
?>