<?
	include_once("../../bit_config.php");
	
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"]?>bit_css/standart.css">
<script>
// Add the selected items from the source to destination list
function addSrcToDestList() {
	destList = window.document.forms[0].tApr;
	srcList = window.document.forms[0].tSourceApr; 
	var len = destList.length;
	var arr_list=new Array(0);
	var arr_list_tmp=new Array(0);
	var arr_list_val=new Array(0);
	var arr_list_val_tmp=new Array(0);
	
	
	var count=0;
	var iCurrent=0;
	for(var i = 0; i < srcList.length; i++) {
		if ((srcList.options[i] != null) && (srcList.options[i].selected)) {
			iCurrent ++;
		}
	}
	
	for(var count = 0; count < len; count++) {
		arr_list[count]=destList.options[count].text;
		arr_list_val[count]=destList.options[count].value; 
	}
	
	for(var i = 0; i < srcList.length; i++) {
		if ((srcList.options[i] != null) && (srcList.options[i].selected)) {
			//Check if this value already exist in the destList or not
			//if not then add it otherwise do not add it.
			var found = false;
			for(var count = 0; count < len; count++) {
				if (destList.options[count] != null) {
					if (srcList.options[i].value == destList.options[count].value) {
						found = true;
						break;
					}
				}
			}
			
			if (srcList.options[i].value=='')
				found = true;
			
			if (found != true) {
				arr_list[count]=srcList.options[i].text; 
				arr_list_val[count]=srcList.options[i].value; 
				len++;
				count++;
			}
		}
	}
	/*var s="";
	for (var property in arr_list_val) {
		s +=arr_list_val[property]+'\n';
	}
	alert(s);
	var s="";
	*/
	for (var i=0;i<arr_list_val.length;i++) {
		arr_list_val_tmp[i]=arr_list_val[i];
	} 
	
	//arr_list_val=arr_list_val.sort();
	for (var i=0;i<arr_list_val.length;i++) {
		for (var j=0;j<arr_list_val_tmp.length;j++) {
			if (arr_list_val[i]==arr_list_val_tmp[j]) {
				tmpStr=arr_list[j];
			}
		}
		arr_list_tmp[i]=tmpStr;
	}
	
	/*for (var property in arr_list_tmp) {
		s +=arr_list_tmp[property]+'\n';
	}
	alert(s);
	
	*/
	//alert(arr_list);
	for (var i=0; i < arr_list.length;i++) {
		destList.options[i] = new Option(arr_list[i], arr_list_val[i]); 
	}
	
	
}
// Deletes from the destination list.
function deleteFromDestList() {
	var destList  = window.document.forms[0].tApr;
	var len = destList.options.length;
	for(var i = (len-1); i >= 0; i--) {
		if ((destList.options[i] != null) && (destList.options[i].selected == true)) {
		destList.options[i] = null;
    	}
    }
}

function getData() {
	var destList  = window.document.forms[0].tApr;
	var len = destList.options.length;
	var sText = '';
	var sValue = '';
	for(var i = 0; i < len ; i++) {
		if (i == (len - 1)) { 
			sText +=destList.options[i].text;
			sValue +=destList.options[i].value;
		} else {
			sText +=destList.options[i].text+'|';
			sValue +=destList.options[i].value+'|';
		}
    }
	
	<? if ($_GET["i"]) { ?>
		opener.document.forms[0].tUser[<? echo ($_GET["i"]-1)?>].value=sText;
		opener.document.forms[0].tNIK[<? echo ($_GET["i"]-1)?>].value=sValue;
	<? } else { ?>
		opener.document.forms[0].tUser.value=sText;
		opener.document.forms[0].tNIK.value=sValue;
	<? } ?>
	
	window.close();
}

</script>
</head>
<body leftmargin="0" topmargin="0">
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data");
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
			
?>
	<table cellpadding="1" cellspacing="1" border="0" width="100%">
	<tr>
		<td colspan="5">
			<? echo setTitle2("Publisher");?>
		</td>
	</tr>
	</table>
	<table>
	<tr>
		<td class="af_row" valign="top"></td>
		<td>
		<?
			$qry="select user_id,nama_karyawan,loker2,user_foto
					from 
						m_users a,m_master_user b
					where
						a.user_id=b.nik
						and a.user_level in (3,4) 
					order by
						nama_karyawan asc";
			$rsN=$ora->sql_fetch($qry,$bit_app["db"]);
			for ($i=1;$i<=$rsN->jumrec;$i++) {
				$dtUser[$i-1][0]=$rsN->value[$i]["user_id"];
				$dtUser[$i-1][1]=$rsN->value[$i]["nama_karyawan"];
			}
			
			
			$arrNama=preg_split("/\|/",$_GET["sData"]);
			$arrNIK=preg_split("/\|/",$_GET["sID"]);
			
			for ($i=0;$i<count($arrNama);$i++) {
				if ($arrNama[$i]) {
					$dtUserApr[$i][0]=$arrNIK[$i];
					$dtUserApr[$i][1]=$arrNama[$i];
				}
			}
		?>
		<? echo $f->select('tSourceApr','tSourceApr',$dtUser,$_POST["tSourceApr"],"inputBox","addSrcToDestList()",6,"multiple"); ?>
		<img src="../../bit_images/previous_icon.gif" onClick="deleteFromDestList()">
		<? echo $f->select('tApr[]','tApr',$dtUserApr,$_POST["tApr"],"inputBox","",6,"multiple"); ?>
		</td>
		</td>
	</tr>
	</td>
	</table>
	
	<p align="left">
	<br />
	&nbsp;&nbsp;<? $f->button("button","Pilih Publisher","button","getData()")?>
	&nbsp;&nbsp;<? $f->button("button","Close","button","window.close()")?>
	</p>
<? $f->closeForm();
   $ora->logoff(); 
?>
</body>
</html>

