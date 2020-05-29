<?php
	session_start();
	include_once("../../bit_config.php");
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
</head>
<body leftmargin="0" topmargin="0">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data");
	
	switch ($_POST["slModule"]) {
	case 1 : 
		$tbl="m_contents";
		$title="News";
		$folder="contents";
		break;
	case 2 : 
		$tbl="m_info";
		$title="Info Management";
		$folder="info";
		break;
	case 3 : 
		$tbl="m_foto";
		$title="Foto";
		$folder="foto";
		break;
	case 4 : 
		$tbl="m_forum";
		$title="Forum";
		$folder="forum";
		break;
	case 5 : 
		$tbl="m_banner";
		$title="Banner";
		$folder="banner";
		break;
	case 6 : 
		$tbl="m_video";
		$title="Video";
		$folder="video";
		break;
	case 7 : 
		$tbl="m_calender";
		$title="Event Calendar";
		$folder="calendar";
		break;
	case 8 : 
		$tbl="m_infra";
		$title="Pengembangan Infrastruktur";
		$folder="infra";
		break;
	case 9 : 
		$tbl="m_inovasi";
		$title="Inovasi";
		$folder="inovasi";
		break;
	default : 
		$tbl="m_contents";
		$title="News";
		$folder="contents";
		break;
	}
	
	$qry="select count(1) cnt,1 from m_contents a where publish_by_info='".getUser(getUserID())."' and publish_flag=1
			union
			select count(1) cnt,6 from m_video d where publish_by_info='".getUser(getUserID())."' and publish_flag=1
			union
			select count(1) cnt,3 from m_foto c where publish_by_info='".getUser(getUserID())."' and publish_flag=1
		";
	$rsWf=$ora->sql_fetch($qry,$bit_app["db"]);
	for ($i=1;$i<=$rsWf->jumrec;$i++) {
		$dtTotal[$rsWf->value[$i][2]]=$rsWf->value[$i]["cnt"];
	}
?>
	<table cellpadding="1" cellspacing="1" border="0" width="90%">
	<tr>
		<td colspan="5">
			<? echo setTitle2("Approve $title");?>
		</td>
	</tr>
	</table>
	<table cellpadding="1" cellspacing="0">
		<tr>
			<td>Module</td>
			<td>
				: <?
					$arrModul=array(1=>"News",6=>"Video",3=>"Foto");
					echo "<select class='inputBox' name='slModule' onChange='document.forms[0].page.value=1;document.forms[0].submit()'>";
					while (list($k,$v)=each($arrModul)) {
						if ($k==$_POST["slModule"])
							echo "<option selected value='".$k."'>".$v." (".$dtTotal[$k].")</option>";
						else
							echo "<option value='".$k."'>".$v." (".$dtTotal[$k].")</option>";
					}
					echo "</select>";
				?>
			</td>
		</tr>
		<tr>
			<td>Judul </td>
			<td align="left">
				:  <input type="text" name="tSearch" size="30" class="inputBox" value="<? if ($_POST["tSearch"]) echo $_POST["tSearch"]; else echo "Search Judul"?>" onMouseOver="if (this.value=='Search Judul') this.value=''" onMouseOut="if (this.value=='') this.value='Search Judul'">
				<input type="submit" class="button"  onClick="document.forms[0].page.value=1"  value="Search">
			</td>
		</tr>
	</table>
	<hr class="article_layout_hr">
	<table cellpadding="0" cellspacing="1" border="0" width="90%">
	<thead>
		<tr>
		<? $arrField=array("No","Judul ".$title,"Tgl.Submit") ?>
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
			
			if ($_POST["tSearch"] && $_POST["tSearch"]!="Search Judul") {
				$where .=" and (upper(title) like '%".strtoupper($_POST["tSearch"])."%')";
			}
			
			$qry="select 
					count(1) 
				  from $tbl
				  where publish_by_info='".getUser(getUserID())."' and publish_flag=1 $where";
			$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			$totalRow=$rs->value[1][1]; //total record	
		
			//==============================================
			$page=$_POST["page"];
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
					id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date
				  from $tbl a
				  where publish_by_info='".getUser(getUserID())."' and publish_flag=1 $where
				  $orderBy
				  limit ".($rownum1-1).",".$bit_app["sumOfRow"]."
				  ";
			$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			
			for ($i=1;$i<=$rs->jumrec;$i++) {
				if ($i%2==0)
					$cl="listTableRow";
				else
					$cl="listTableRowS";
					
				$arrAuthor=explode("/",$rs->value[$i][9]);
				$arrPublisher=explode("/",$rs->value[$i][8]);
				
		?>			
				<tr class="<? echo $cl?>">
					<td align="center" width="5%"><? echo ((($page-1)*$bit_app["sumOfRow"])+$i)?></td>
					<td align="left" width="40%"><? echo $rs->value[$i][2]?></td>
					<td align="left" width="10%" nowrap="nowrap"><? echo $rs->value[$i][3]?></td>
					<td width="1%" align="center">
						<img title="Detail" style="cursor:pointer" src="<? echo $bit_app["path_url"]?>/bit_images/detail.gif" onClick="var win; win=window.open('<? echo $bit_app["path_url"]."/bit_content/".$folder."/"?>det.php?id=<? echo $rs->value[$i][1]?>','win','top=10,left=100,height=650,width=600,resizable=1,scrollbars=1');win.focus()">
					</td>
						<td width="1%" align="center">
						<img title="Detail" style="cursor:pointer" src="<? echo $bit_app["path_url"]?>/bit_images/edit.gif" onClick="var win; win=window.open('<? echo $bit_app["path_url"]."/bit_content/".$folder."/"?>edit.php?id=<? echo $rs->value[$i][1]?>','win','top=10,left=100,height=650,width=600,resizable=1,scrollbars=1');win.focus()">
					</td>
				
				</tr>	
		<? } ?>
	</tbody>
	</table>
	<table width="90%">
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
</body>
</html>

