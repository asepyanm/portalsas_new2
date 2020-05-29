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
			<? echo setTitleCari("Quiz");?>
		</td>
	</tr>
	</table>
	<table cellpadding="0" cellspacing="1" border="0" width="100%">
	<thead>
		<tr>
		<? $arrField=array("NO","Judul","Active","Oleh","Tanggal") ?>
		<?
			for ($i=0;$i<count($arrField);$i++) {
				echo "<th class='listTableHead'>".$arrField[$i]."&nbsp;&nbsp;";
				if ($i!=0 && $i!=2 && $i!=4) {
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
			echo "<th class='listTableHead' colspan=3>Perintah</th>";
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
			
			if ($_POST["sSave"]) {
				$qry="update m_quiz
						set active=null";
				$ora->sql_no_fetch($qry,$bit_app["db"]); 
				
				$qry="update m_quiz
						set active=1
						where id=".$_POST["rdQuiz"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); 
			}
			
			switch ($_POST["hdAction"]) {
			case 3 :
				$qry="delete from m_quiz
						where id=".$_POST["id"];
				$ora->sql_no_fetch($qry,$bit_app["db"]); 
				break;
			}
			
			$qry="select 
					count(1) 
				  from m_quiz
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
					id,judul,updated_by,date_format(updated_date,'%W, %d/%m/%Y %h:%i') updated_date,active
				  from m_quiz a
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
					<td align="center" width="1%"><? echo ((($page-1)*$bit_app["sumOfRow"])+$i)?></td>
					<td align="left" width="50%"><? echo $rs->value[$i][2]?></td>
					<td align="center" width="3%">
						<? 
							if ($rs->value[$i]["active"]==1) 
								echo "<input type='radio' name='rdQuiz' value='".$rs->value[$i]["id"]."' checked>";
							else
								echo "<input type='radio' name='rdQuiz' value='".$rs->value[$i]["id"]."'>";
						?>
					</td>
					<td align="left" width="15%"><? echo $rs->value[$i][3]?></td>
					<td align="left" width="15%" nowrap="nowrap"><? echo $rs->value[$i][4]?></td>
					<td width="1%">
						<img title="Edit Data" src="<? echo $bit_app["path_url"]?>/bit_images/edit.gif" onMouseOver="this.style.cursor='pointer'" onClick="var win; win=window.open('<? echo $bit_app["path_url"]."/bit_content/content/"?>quizEdit.php?id=<? echo $rs->value[$i][1]?>','win','top=10,left=100,height=550,width=600,resizable=1,scrollbars=1');win.focus()">
					</td>
					<td width="1%">
						<img title="Hapus Data" src="<? echo $bit_app["path_url"]?>/bit_images/hapus.png" onMouseOver="this.style.cursor='pointer'" onClick="var ans;ans=confirm('Anda yakin untuk menghapus ?'); if (ans) {document.forms[0].hdAction.value=3;document.forms[0].id.value=<? echo $rs->value[$i][1]?>;document.forms[0].submit();}">
					</td>
					<td width="1%">
						<img title="Detail Data" src="<? echo $bit_app["path_url"]?>/bit_images/detail.gif" onMouseOver="this.style.cursor='pointer'" onClick="var win; win=window.open('<? echo $bit_app["path_url"]."/bit_content/content/"?>quizDet.php?id=<? echo $rs->value[$i][1]?>','win','top=10,left=100,height=550,width=600,resizable=1,scrollbars=1');win.focus()">
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
	<? 
		if ($rs->jumrec>=1) 
			$f->submit("sSave","Simpan","button");
	?>
	
	<? $f->button("add","Tambah","button","var win;win=window.open('".$bit_app["path_url"]."/bit_content/content/quizAdd.php','win','top=10,left=100,height=530,width=600,resizable=1,scrollbars=1');win.focus()")?>
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


