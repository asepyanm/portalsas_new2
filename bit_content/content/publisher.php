<?
	
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data","onSubmit='return validate()'");
	
?>
<table width="100%" border="0">
	<tr>
		<td colspan="2">
			<? echo setTitleEdit("Publisher Info Management / Banner");?>
		</td>
	</tr>
	<?
		$ora=new clsMysql;
		$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
		
		if ($_POST["ok"]) {
			$qry="delete from p_publisher_info";
			$ora->sql_no_fetch($qry,$bit_app["db"]);
			
			$qry="insert into p_publisher_info values('".$_POST["tNIK"]."')";
			if (!$ora->sql_no_fetch($qry,$bit_app["db"])) {
				alert('Update Publisher Info Gagal. hubungi administrator !');
			}
		}
		
		$qry="select nik from p_publisher_info";
		$rs=$ora->sql_fetch($qry,$bit_app["db"]);
		
		$rsUser=getUserDBPublisher();
	?>
	<tr>
		<td>Publisher</td>
		<td>
		<? 
			$nik=explode(",",$rs->value[1][1]);
			$sData="";
			for ($j=0;$j<count($nik);$j++) {
				$sData .=$rsUser[$nik[$j]][1].",";
				$sID .=$nik[$j].",";
			}
			$sData=substr($sData,0,strlen($sData)-1);
			$sID=substr($sID,0,strlen($sID)-1);
			
			$f->hidden("tNIK","tNIK",$dtLoker[$rsLoker->value[$i][1]])?>
			<? $f->hidden("tLoker","tLoker",$rsLoker->value[$i][1])?>
			<? $f->textbox("tUser","tUser",$sData,"inputBox",70,255,"","readonly='yes'")?>
			<? $f->button("","...","button","window.open('content/publisherData.php?sData=".$sData."&sID=".$sID."','win1','top=200,left=300,height=200,width=600,resizable=1,scrollbars=1')"); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr class="article_layout_hr">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left">
			<? $f->submit("ok","simpan","button")?>
		</td>
	</tr>
</table>
<?
	$f->closeForm();
	$ora->logoff();
?>
