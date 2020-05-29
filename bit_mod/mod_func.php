<?php
	
	function send_email($tipe,$to,$subject,$info,$module) {
		global $ora;
		global $bit_app;
		global $dtUser;
		
		
		switch ($tipe) {
		case "submit" :
			$content="Author : ".$info["author"]."
<br>
Judul ".$module." : ".$info["judul"]."
<br><br>
Mohon segera merespon ".$module." tersebut di menu publisher web infratel <a href=\"http://infratel.telkom.co.id\">http://infratel.telkom.co.id</a>
<br><br>
Terima Kasih
<br><br>
Admin Web Infratel
";
		break;
		case "approve" :
			$content="Author : ".$info["author"]."
<br>
Judul ".$module." : ".$info["judul"]."
<br><br>
Telah di publish di web infratel pada : ".date("d-M-Y, H:i")."
<br>
Komentar Publisher : ".$info["note"]."
<br><br>
Terima Kasih
<br><br>
Admin Web Infratel
";
		break;
		case "reject" :
			$content="Author : ".$info["author"]."
<br>
Judul ".$module." : ".$info["judul"]."
<br><br>
Telah di tolak ".$module." tersebut pada : ".date("d-M-Y, H:i")."
<br>
Komentar Publisher : ".$info["note"]."
<br><br>
Terima Kasih
<br><br>
Admin Web Infratel
";
			break;
		case "milis" :
			$content="Author : ".$info["author"]."
<br>
Judul ".$module." : ".$info["judul"]."
<br>
Deskripsi : ".short_content($info["description"],100)."...
<br><br>
Info ".$module." selengkapnya silahkan akses di <a href=\"http://infratel.telkom.co.id\">http://infratel.telkom.co.id</a>
<br><br>
Terima Kasih
<br><br>
Admin Web Infratel
";
			break;
		case "comment_forum" :
			$content="Author : ".$info["author"]."
<br>
Judul ".$module." : ".$info["judul"]."
<br>
Pesan : ".$info["note"]."
<br><br>
Info ".$module." selengkapnya silahkan akses di <a href=\"http://infratel.telkom.co.id\">http://infratel.telkom.co.id</a>
<br><br>
Terima Kasih
<br><br>
Admin Web Infratel
";
			break;
		}
		
		$from ="AdminPortal@telkom.co.id";
		
		#Debug to Database
		$qry="insert into d_email values('$from','$to','$subject','$content',sysdate())";
		$ora->sql_no_fetch($qry,$bit_app["db"]);
		
		$headers = "From: $from <AdminPortal@telkom.co.id>\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		
		if ($bit_app["email"])	  
			MailSend($to,$subject,$content,$from,"Portal Infratel","","");
	}
	
	function getUser($nik) {
		global $ora;
		global $bit_app;
	
		$qry="select user_id,nama_karyawan,loker2,user_foto
				from 
					m_users a,m_master_user b
				where
					a.user_id=b.nik
					and a.user_id=".$nik;
		$rsN=$ora->sql_fetch($qry,$bit_app["db"]);
		return $rsN->value[1]["nama_karyawan"]." / ".$rsN->value[1]["user_id"]." / ".$rsN->value[1]["loker2"];
	}
	
	function getUser2($nik) {
		global $ora;
		global $bit_app;
	
		$qry="select user_id,nama_karyawan,loker2,user_foto
				from 
					m_users a,m_master_user b
				where
					a.user_id=b.nik
					and a.user_id=".$nik;
		$rsN=$ora->sql_fetch($qry,$bit_app["db"]);
		return $rsN->value[1]["nama_karyawan"]." / ".$rsN->value[1]["user_id"];
	}
	
	function getUser3($nik) {
		global $ora;
		global $bit_app;
	
		$qry="select user_id,nama_karyawan,loker2,user_foto
				from 
					m_users a,m_master_user b
				where
					a.user_id=b.nik
					and a.user_id=".$nik;
		$rsN=$ora->sql_fetch($qry,$bit_app["db"]);
		
		$tbl ="<table>";
		$tbl .="<tr><td align='left' class='bit_inovator'>Diupload Oleh : </td></tr>";
		$tbl .="<tr><td align='left'>";
		$tbl .="<img class='bit_image' src='".$bit_app["folder_url"]."/".$rsN->value[1]["user_foto"]."' width='30' height='30'>";
		$tbl .="<b>".$rsN->value[1]["nama_karyawan"]." <br />".$rsN->value[1]["user_id"]." <br /> ".$rsN->value[1]["loker2"]."</b>";
		$tbl .="</td></tr>";
		$tbl .="</table>";
		return $tbl;
	}
	
	function getUserDB() {
		global $ora;
		global $bit_app;
	
		$qry="select user_id,nama_karyawan,loker2,user_foto
				from 
					m_users a,m_master_user b
				where
					a.user_id=b.nik";
		$rsN=$ora->sql_fetch($qry,$bit_app["db"]);
		for ($i=1;$i<=$rsN->jumrec;$i++) {
			//$dtUser[$rsN->value[$i]["user_id"]][1]=$rsN->value[$i]["nama_karyawan"]." / ".$rsN->value[$i]["nik"];
			$dtUser[$rsN->value[$i]["user_id"]][1]=$rsN->value[$i]["nama_karyawan"];
			$dtUser[$rsN->value[$i]["user_id"]][2]=$rsN->value[$i]["loker2"];
			$dtUser[$rsN->value[$i]["user_id"]][4]=$rsN->value[$i]["nama_karyawan"]." / ".$rsN->value[$i]["loker2"];
			if ($rsN->value[$i]["user_foto"])
				$dtUser[$rsN->value[$i]["user_id"]][3]=$bit_app["folder_url"].$rsN->value[$i]["user_foto"];
			else
				$dtUser[$rsN->value[$i]["user_id"]][3]="http://10.2.15.232/drp/0PhotoNAS/".$rsN->value[$i]["user_id"].".jpg";
			$dtUser[$rsN->value[$i]["user_id"]][5]=$rsN->value[$i]["nama_karyawan"]." / ".$rsN->value[$i]["user_id"];
			$dtUser[$rsN->value[$i]["user_id"]][6]=$rsN->value[$i]["nama_karyawan"]." / ".$rsN->value[$i]["user_id"]." / ".$rsN->value[$i]["loker2"];
			
		}
		return $dtUser;
	}
	
	function getUserDBPublisher() {
		global $ora;
		global $bit_app;
	
		$qry="select user_id,replace(nama_karyawan,',','') nama_karyawan,loker2,user_foto
				from 
					m_users a,m_master_user b
				where
					a.user_id=b.nik
					and a.user_level in (3,4)";
		$rsN=$ora->sql_fetch($qry,$bit_app["db"]);
		for ($i=1;$i<=$rsN->jumrec;$i++) {
			//$dtUser[$rsN->value[$i]["user_id"]][1]=$rsN->value[$i]["nama_karyawan"]." / ".$rsN->value[$i]["nik"];
			$dtUser[$rsN->value[$i]["user_id"]][1]=$rsN->value[$i]["nama_karyawan"];
			$dtUser[$rsN->value[$i]["user_id"]][2]=$rsN->value[$i]["loker2"];
			$dtUser[$rsN->value[$i]["user_id"]][4]=$rsN->value[$i]["nama_karyawan"]." / ".$rsN->value[$i]["loker2"];
			if ($rsN->value[$i]["user_foto"])
				$dtUser[$rsN->value[$i]["user_id"]][3]=$bit_app["folder_url"].$rsN->value[$i]["user_foto"];
			else
				$dtUser[$rsN->value[$i]["user_id"]][3]="http://10.2.15.232/drp/0PhotoNAS/".$rsN->value[$i]["user_id"].".jpg";
			$dtUser[$rsN->value[$i]["user_id"]][5]=$rsN->value[$i]["nama_karyawan"]." / ".$rsN->value[$i]["user_id"];
			$dtUser[$rsN->value[$i]["user_id"]][6]=$rsN->value[$i]["nama_karyawan"]." / ".$rsN->value[$i]["user_id"]." / ".$rsN->value[$i]["loker2"];
			
		}
		return $dtUser;
	}
	
	function short_content($content,$length) {
		$content=cleansing($content);
		if (strlen($content)>$length)
			$content=substr($content,0,$length)." ...";
		
		return $content;
	}
	
	function cleansing($content) {
		$i=0;
		$content=" ".$content;
		while (strpos($content,"<",$i)) {
			$i=strpos($content,"<",$i);
			$pos=strpos($content,">",$i);
			if ($pos) {
				$tmp=substr($content,$i,($pos-$i)+1);
				$content=str_replace($tmp,"",$content)." ";
			}
		}
		return $content;
	}
	
	function check_tag($arrTag,$tag) {
		return in_array($arrTag, $tag);
		
	}
	
	function cleansing_content($content) {
		$i=0;
		$content=" ".$content;
		$tag=array("<img ","<p ","<b ","<i ","< u");
		
		while (strpos($content,"<",$i)) {
			$i=strpos($content,"<",$i);
			$pos=strpos($content,">",$i);
			if (substr($content,$i,4)!="<img") {
				if ($pos) {
					$tmp=substr($content,$i,($pos-$i)+1);
					$content=str_replace($tmp,"",$content)." ";
				}
			}
		}
		return $content;
	}
	
	function getPoint($id) {
		global $ora;
		global $bit_app;
	
		$qry="select * from m_point a
				where
					id=".$id;
		$rsPoint=$ora->sql_fetch($qry,$bit_app["db"]);
		return $rsPoint->value[1]["point"];
	}
	
	function getStatus($st) {
		switch($st) {
		case 4 :
			return "Edit"; break;
		case 3 :
			return "Submit"; break;
		case 2 :
			return "Reject"; break;
		case 1 :
			return "Approve"; break;
		}
	}
	
	function alert($msg) {
		echo "<script>alert('$msg')</script>";
	}
	
	function close() {
		echo "<script>window.close()</script>";
	}
	
	function refresh_parent_opener($url) {
		echo "<script>opener.window.location.href='$url'</script>";
	}
	
	function refresh_parent_opener_self() {
		echo "<script>opener.window.location.href=opener.window.location</script>";
	}
	
	function parent_opener_submit() {
		echo "<script>opener.document.forms[0].submit()</script>";
	}
	
	function refresh_parent($url) {
		echo "<script>parent.window.location.href='$url'</script>";
	}
	
	function format($val) {
		return number_format($val,0,",",".");
	}
	
	function escape($str) {
		return str_replace("'","''",$str);
	}
	
	function debug($arr) {
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
	}
	
	function buttonMenu($img,$link) {
		global $pathFolderUrl;
		echo "<img onMouseOut=\"this.style.background='".$pathFolderUrl.$img[1][1]."/".$img[1][2]."'\" onMouseOver=\"this.style.cursor='hand';this.background.src='".$pathFolderUrl.$img[2][1]."/".$img[2][2]."'\" src=\"".$pathFolderUrl.$img[1][1]."/".$img[1][2]."\" onClick=\"var win2; win2=window.open('".$gHomePageUrl."linkDet.php?id=".$link."','win2','height=550,width=550,resizable=1,scrollbars=1');win2.focus()\">";
	}
	
	function validasi_email($str) {
		$pos=strpos($str,"@");
		if ($pos!="") {
			$arr=split("@",$str);
			if (count($arr)>3)
				return false;
			else {
				if (strpos($str,".")=="")
					return false;
				else
					return true;
			} 
		} else return false;
		
			
	}
	
	function twoD($val) {
		if (strlen($val)==1)
			return "0".$val;
		else
			return $val;
	}
	
	function getMonth($val) {
		$arrMonth=array(""=>"",
					"01"=>"Januari",
					"02"=>"Februari",
					"03"=>"Maret",
					"04"=>"April",
					"05"=>"Mei",
					"06"=>"Juni",
					"07"=>"Juli",
					"08"=>"Agustus",
					"09"=>"September",
					"10"=>"Oktober",
					"11"=>"November",
					"12"=>"Desember");
		while (list($key,$value)=each($arrMonth)) {
			if ((int)$key==(int)$val)
				return $value;
		} 
	}
	
	function getMethod($method="REQUEST",$except="") {
		switch (strtoupper($method)) {
		case "POST":
			while (list($key,$value)=each($_POST)) {
				if (count($value)>1) {
					while (list($keyC,$valC)=each($value)) {
						$str .=$key."[".$keyC."]=".$valC."&";
					}		
				} else
						$str .=$key."=".$value."&";
			}
			break;
		case "REQUEST":
			while (list($key,$value)=each($_REQUEST)) {
				if (count($value)>1) {
					while (list($keyC,$valC)=each($value)) {
						$str .=$key."[".$keyC."]=".$valC."&";
					}		
				} else
						$str .=$key."=".$value."&";
			}
			break;
		case "GET":
			while (list($key,$value)=each($_GET)) {
				if (count($value)>1) {
					while (list($keyC,$valC)=each($value)) {
						$str .=$key."[".$keyC."]=".$valC."&";
					}		
				} else
						$str .=$key."=".$value."&";
			}
			break;
		case "SESSION":
			while (list($key,$value)=each($_SESSION)) {
				if (count($value)>1) {
					while (list($keyC,$valC)=each($value)) {
						$str .=$key."[".$keyC."]=".$valC."&";
					}		
				} else
						$str .=$key."=".$value."&";
			}
			break;
		}
		return $str;
	}
	
	function read_file($path) {
		$handle = fopen ("$path", "r"); 
		$buffer="";
		while (!feof ($handle)) { 
		   $buffer .= fgets($handle, 4096); 
		} 
		fclose ($handle);
		return $buffer; 
	}
	
	function open($url,$name='xWindow',$width=600,$height=400,$left=200,$top=200) {
		echo "<script>
					  var $name;
					  $name=window.open('$url','$name','menubar=0,scrollbars=yes,resizable=yes,width=$width,height=$height,left=$left,top=$top');
					  $name.focus();
			</script>";
	}
	
	function open_w($url,$name='xWindow',$width=600,$height=400,$left=200,$top=200) {
		return "var $name;$name=window.open('$url','$name','menubar=0,scrollbars=yes,resizable=yes,width=$width,height=$height,left=$left,top=$top');$name.focus()";
	}
	
	function convert_tanggal($str,$delimiter="-") {
		$split=split($delimiter,$str);
		if ($str!="")
			return $split[2]."-".$split[1]."-".$split[0];
		return "";
	}
	
	function getCalendarModule() {
		global $bit_app;
		echo "
		<link rel='stylesheet' type='text/css' href='".$bit_app["path_url"]."/bit_content/mod/calendar/calendar-mos.css'>
		<script language='JavaScript' src='".$bit_app["path_url"]."/bit_content/mod/mambojavascript.js' type='text/javascript'></script>
		<script language='JavaScript' src='".$bit_app["path_url"]."/bit_content/mod/calendar/calendar.js'></script>
		<script language='JavaScript' src='".$bit_app["path_url"]."/bit_content/mod/calendar/lang/calendar-en.js'></script>
		";
	}
	
	function setTitle($str) {
		global $bit_app;
		echo "<table width='100%' cellpadding=0 cellspacing=0>
			<tr>
				<td class='title'>$str</td>
			</tr>
			<tr>
				<td class='title'><hr class='article_layout_hr'></td>
			</tr>
			</table>";
	}
	
	function setTitleEdit($str) {
		global $bit_app;
		echo "<table width='100%' cellpadding=0 cellspacing='0'>
			<tr>
				<td class='title' align='left'>
				<img src='".$bit_app["path_url"]."/bit_images/edit_f2.png'> $str</td>
			</tr>
			<tr>
				<td class='title' align='left'><hr class='article_layout_hr'></td>
			</tr>
			
			</table>";
	}
	
	
	function setTitleDetail($str) {
		global $bit_app;
		echo "<table width='100%' cellpadding=0 cellspacing='0'>
			<tr>
				<td class='title' align='left'>
				<img src='".$bit_app["path_url"]."/bit_images/mediamanager.png'> $str</td>
			</tr>
			<tr>
				<td class='title' align='left'><hr class='article_layout_hr'></td>
			</tr>
			
			</table>";
	}
	
	function setTitleDetail1($str) {
		global $bit_app;
		echo "<table width='100%' cellpadding=0 cellspacing='0'>
			<tr>
				<td class='title' align='left'>
				$str</td>
			</tr>
			<tr>
				<td class='title' align='left'><hr class='article_layout_hr'></td>
			</tr>
			
			</table>";
	}
	
	function setTitleAdd($str) {
		global $bit_app;
		echo "<table width='100%' cellpadding=0 cellspacing='0'>
			<tr>
				<td class='title' align='left'>
				<img src='".$bit_app["path_url"]."/bit_images/categories.png'> $str</td>
			</tr>
			<tr>
				<td class='title' align='left'><hr class='article_layout_hr'></td>
			</tr>
			
			</table>";
	}
	
	function setTitle2($str) {
		global $bit_app;
		echo "<table width='100%' cellpadding=0 cellspacing='0'>
			<tr>
				<td class='title_line' align='left'>$str</td>
			</tr>
			</table>";
	}
	
	function setTitleCari($str) {
		global $bit_app;
		echo "<table width='100%' cellpadding=0 cellspacing='0'>
			<tr>
				<td class='title' align='left'>
				<img src='".$bit_app["path_url"]."/bit_images/edit-status_f2.png'> $str</td>
			</tr>
			<tr>
				<td class='title' align='left'><hr class='article_layout_hr'></td>
			</tr>
			
			</table>";
	}
	
	function setTitleFront($str) {
		global $bit_app;
		echo "<table width='100%' cellpadding=0 cellspacing='0'>
			<tr>
				<td class='headerNews' align='left'>
				<img src='".$bit_app["path_url"]."/bit_images/edit-status_f2.png'> $str</td>
			</tr>
			<tr>
				<td class='title' align='left'><hr class='article_layout_hr'></td>
			</tr>
			
			</table>";
	}
	
	function closeDHTML() {
		echo "<script>parent.document.dhtmlwindow.close(this)</script>";
	}
	
	function getForm($form) {
		$arr=split("\.",$form);
		//$arr=explode("\.",$form);
		for ($i=0;$i<count($arr);$i++)
			$tmp .=$arr[$i]."/";
		
		$tmp = substr($tmp,0,strlen($tmp)-1).".php";	
		return $tmp;
	}
	
	function getChart($dt) {
		   global $bit_app;
		    
		   $arrColor=array("#f4c84b","#0099CC","#eb4950","#4bf46f","#abced3","#fc67e2","#bb3eb1","#7fe0cd","#8a855e");
		   
		   //Now, we need to convert this data into XML. We convert using string concatenation.
		   //Initialize <graph> element
		   $strXML = "<graph caption='".$dt["title"]."' numberPrefix='' formatNumberScale='0' decimalPrecision='1' showValues='0'>";
		   //Convert data to XML and append
		   $strXML .= "<categories font='Arial' fontSize='11' fontColor='000000'>";
		  
		    for ($i=0;$i<count($dt["cat"]);$i++) 
			  $strXML .= "<p_ name='".$dt["cat"][$i]."' hoverText='".$dt["cat"][$i]."'/>";
			  
		   $strXML .= "</categories>";
		
		   for ($i=0;$i<count($dt["Y"]);$i++) {
			   $strXML .= "<dataset seriesname='".$dt["Y"][$i]."' color='".$arrColor[$i-1]."'>";
			   for ($j=0;$j<count($dt["cat"]);$j++)
			   	$strXML .= "<set value='".$dt[$i][$j]."'/>";
			   
			   $strXML .= "</dataset>";
		   }
		   $strXML .= "</graph>";
		   
		  //Create the chart - Column 3D Chart with data contained in strXML
		   $idRandom="id".rand(1,1000000);
		   return renderChart($bit_app["third_url"]."/FusionChartsFree/Charts/FCF_MSColumn3D.swf", "", $strXML, $idRandom, $dt["width"], $dt["height"]);
	}
	
	
?>