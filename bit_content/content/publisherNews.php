<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data");
?>
<table width="100%" border="0">
	<tr>
		<td colspan="4">
			<? echo setTitleEdit("Publisher News / Video / Foto");?>
		</td>
	</tr>
	<?
		$ora=new clsMysql;
		$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
		
		
		if ($_POST["ok"]) {
			while (list($k,$v)=each($_POST["tNIK"])) {
				if ($v) {
					$qry="delete from p_publisher_news where loker2='".$_POST["tLoker"][$k]."'";
					$ora->sql_no_fetch($qry,$bit_app["db"]);
					
					$qry="insert into p_publisher_news(loker2,nik) values('".$_POST["tLoker"][$k]."','".$v."')";
					$ora->sql_no_fetch($qry,$bit_app["db"]);
				}
			}
		}
		
		$qry="select nik,loker2 from p_publisher_news";
		$rsPublish=$ora->sql_fetch($qry,$bit_app["db"]);
		for ($i=1;$i<=$rsPublish->jumrec;$i++) {
			$dtLoker[$rsPublish->value[$i][2]]=$rsPublish->value[$i][1];
		}
		
		$rsUser=getUserDBPublisher();
				
		$qry="select loker2,count(1) from m_master_user group by loker2 order by loker2";
		$rsLoker=$ora->sql_fetch($qry,$bit_app["db"]);
		for ($i=1;$i<=$rsLoker->jumrec;$i++) {
			$nik=explode(",",$dtLoker[$rsLoker->value[$i][1]]);
			$sData="";
			for ($j=0;$j<count($nik);$j++) {
				$sData .=addslashes($rsUser[$nik[$j]][1]).",";
			}
			$sData=substr($sData,0,strlen($sData)-1);
			?>
			<tr>
				<td><? echo $rsLoker->value[$i][1]?></td>
				<td colspan="3">
				<? $f->hidden("tNIK[]","tNIK",$dtLoker[$rsLoker->value[$i][1]])?>
				<? $f->hidden("tLoker[]","tLoker",$rsLoker->value[$i][1])?>
				<? $f->textbox("tUser[]","tUser",$sData,"inputBox",70,255,"","readonly='yes'")?>
				<? $f->button("","...","button","window.open('content/publisherData.php?i=".($i)."&sData=".$sData."&sID=".$dtLoker[$rsLoker->value[$i][1]]."','win1','top=200,left=300,height=200,width=600,resizable=1,scrollbars=1')"); ?>
				</td>
			</tr>
			<?
		}
	?>
	<tr>
		<td colspan="4">
			<hr class="article_layout_hr">
		</td>
	</tr>
	<tr>
		<td colspan="4" align="left">
			<? $f->submit("ok","simpan","button")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
	$ora->logoff();
?>
