<?
	include_once("../../bit_config.php");
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
			<? echo setTitle("b r o w s e . d a f t a r . c o n t e n t");?>
		</td>
	</tr>
	</table>
	
	<table cellpadding="1" cellspacing="0">
		<tr>
			<td align="left">
				<? $mString="Search"; ?>
				<input type="text" name="tInput[]" size="32" class="inputtext" value="<? if ($_POST["tInput"][0]) echo $_POST["tInput"][0]; else echo $mString?>" onMouseOver="" onMouseOut="if (this.value=='') this.value='<? echo $mString?>'">
				&nbsp;
			</td>
			<td><input type="submit" class="button"  onClick="document.forms[0].page.value=1"  value="s e a r c h"></td>
		</tr>
	</table>
	<hr class="article_layout_hr">
	<table cellpadding="0" cellspacing="1" border="0" width="100%">
	<thead>
		<tr>
		<? $arrField=array("NO","Nama Content","Kategori") ?>
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
			echo "<th colspan=2 class='listTableHead'>Perintah</th>";
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
			
			if ($_POST["tInput"][0]) {
				$where .=" and (
							upper(title) like '%".strtoupper($_POST["tInput"][0])."%'
							or
							 upper(content) like '%".strtoupper($_POST["tInput"][0])."%'
							)";
			}
			
			
			$qry="select 
					count(1) 
				  from m_contents
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
					id,title,b.category_name,publish_flag
				  from m_contents a,p_category b
				  where a.category_id=b.category_id $where
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
					<td align="center"><? echo ((($page-1)*$bit_app["sumOfRow"])+$i)?></td>
					<td align="left">
						<input type="radio" value="<? echo $rs->value[$i][1]?>" name="rd[]" onClick="opener.document.forms[0].<? echo $_GET["field"]?>.value=this.value;window.close()">
						<? echo $rs->value[$i][2]?>
					</td>
					<td align="left"><? echo $rs->value[$i][3]?></td>
					<td width="1%" align="center">
						<img title="Detail Content" style="cursor:pointer" src="<? echo $bit_app["path_url"]?>/bit_images/detail.gif" onClick="var win2; win2=window.open('<? echo $bit_app["path_url"]."/bit_content/contents/"?>det.php?id=<? echo $rs->value[$i][1]?>','win2','top=10,left=100,height=650,width=600,resizable=1,scrollbars=1');win2.focus()">
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
	<? $f->button("refresh","r e f r e s h","button","window.location=window.location.href")?>
	<? $f->button("button","t u t u p . w i n d o w","button","window.close()")?>
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

