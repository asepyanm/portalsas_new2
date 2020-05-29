<?php
	//session_start();
	//include_once('../../bit_config.php');
	//if (getUserLevel()!=4) {
		#alert('Anda tidak berhak mengakses menu Administrator !');
	//	echo "<script>window.location.href='../../".$bit_app["app"]."/'</script>";
	//	exit;
	//}

	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);

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
			<? echo setTitleCari("Manajemen SAS");?>
		</td>
	</tr>
	</table>
	<table cellpadding="0" cellspacing="1" border="0" width="100%" class="table">
	<thead>
		<tr>
			<th class="listTableHead">NO</th>	
			<th class="listTableHead">NAMA</th>	
			<th class="listTableHead">JABATAN</th>	
			<th class="listTableHead">FOTO</th>		
			<th colspan="2" class="listTableHead">PERINTAH</th>	
		</tr>
	</thead>
	<tbody>
		<?php			

			$qry="select id, nama, jabatan, foto  from m_manajemen order by id asc";
			$rs=$ora->sql_fetch($qry,$bit_app["db"]);
			
			for ($i=1;$i<=$rs->jumrec;$i++) {
                $fl=$bit_app["folder_dir"].$rs->value[$i]['foto'];	
                $file=$rs->value[$i]['foto'];
                if(!file_exists($fl) || filesize($fl)==0){
                    $getobj = $s3->getObject(BUCKETNAME, PORTALSASDIR_DATA.$file, $fl);
                }
				if ($i%2==0)
					$cl="listTableRow";
				else
					$cl="listTableRowS";
		?>			
                <tr class="<? echo $cl?>">
                   <?php
                    // if(!empty($id) && $id==$rs->value[$i]['id']){
                    ?>
                    <!--
                        <td align="center" width="2%" valign="top"><? echo $i?></td>
                        <td align="left" width="20%" valign="top"><input type="text" name="nama" value="<? echo $rs->value[$i]['nama']?>" /></td>
                        <td align="center" width="20%" valign="top"><input type="text" name="jabatan" value="<? echo $rs->value[$i]['jabatan']?>" /></td>
                        <td align="center" width="20%" valign="top"><img src="<?php echo $bit_app["path_url"]?>bit_folder/<? echo $rs->value[$i]['foto']?>" width="75" /><br /
                                <input type="file" name="ufile" requered /></td>
                        <td width="1%" align="center">
                            <input type="hidden" name="id" value="<?php echo $rs->value[$i]['id']?>" />
                            <input type="submit" value="UPDATE" class="button" />
                        </td>
                        //-->
                    <?php
                    // } else{

                   ?>
					<td align="center" width="2%" valign="top"><? echo $i?></td>
					<td align="left" width="20%" valign="top"><? echo $rs->value[$i]['nama']?></td>
					<td align="center" width="20%" valign="top"><? echo $rs->value[$i]['jabatan']?></td>
					<td align="center" width="20%" valign="top"><img src="<?php echo $bit_app["path_url"]?>bit_folder/<? echo $rs->value[$i]['foto']?>" width="75" /></td>
					<td width="1%" align="center">
						<a href="#" onClick='showColorbox("<?php echo $bit_app['path_url']?>bit_content/content/editmanajemenSas.php?id=<?php echo $rs->value[$i]['id']?>");'>
                            <img title="Edit Data" src="<? echo $bit_app["path_url"]?>/bit_images/edit.gif" />
                        </a>
                    </td>
                    <?php
                   // }
                    ?>
				</tr>	
		<? } ?>
	</tbody>
	</table>

	<hr class="article_layout_hr">

<? $f->closeForm(); ?>
</body>
</html>
<? $ora->logoff(); ?> 



