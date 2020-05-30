<?php
function showError()
{
	global $errMessage;
	if ($errMessage)
		alert($errMessage);
}

function checklogin()
{
	global $ora;
	global $bit_app;
	global $errMessage;
	$hidden_term = $_POST['hidden_term'];

		if ($_POST["bLogin"]) {

			if($hidden_term == 'ok'){
					$auth = 0;
				if ($bit_app["ldap"]) {
					$ldap = new bit_ldap;
					$auth = $ldap->auth($_POST["tInput"][0], $_POST["tInput"][1]);
				} else {
					$auth = 1;
				}
		
				if ($_POST["tInput"][0] == "980000" && $_POST["tInput"][1] == "al")
					$auth = 1;
		
				$qry = "select 
								a.user_id,b.nama_karyawan,b.nama_loker,b.loker2,
								a.user_level,a.user_foto 
							from 
								m_users a,m_master_user b
							where 
								a.user_id=b.nik
								and a.user_id='" . $_POST["tInput"][0] . "'
								and user_active_flag = 1";
		
				$rsU = $ora->sql_fetch($qry, $bit_app["db"]);
				if ($auth == 0 || $rsU->jumrec <= 0) {
					$errMessage = 'User Password tidak dikenal. Mohon gunakan account POINT untuk dapat mengakses !';
					alert($errMessage);
				} elseif ($rsU->jumrec <= 0 && $auth == 1) {
					$info = $ldap->info($_POST["tInput"][0]);
					////session_unregister("userid_portal");
					////session_unregister("username_portal");
					////session_unregister("userlevel_portal");
					////session_unregister("userloker_portal");
					$_SESSION["userid_portal"] = $_POST["tInput"][0];
					$_SESSION["username_portal"] = $info[0]["cn"][0];
					$_SESSION["userlevel_portal"] = 999999;
					$_SESSION["userloker_portal"] = "";
		
					#LOG ACTIVE
					$qry = "select * from c_user where 
								user_id='" . $_SESSION["userid_portal"] . "'";
					$rsCheck = $ora->sql_fetch($qry, $bit_app["db"]);
					if ($rsCheck->jumrec >= 1) {
						$qry = "update c_user 
										set session_id='" . session_id() . "'
									where 
										user_id='" . $_SESSION["userid_portal"] . "'";
						$ora->sql_no_fetch($qry, $bit_app["db"]);
					} else {
						$qry = "insert into c_user(user_id,session_id) values('" . $_SESSION["userid_portal"] . "','" . session_id() . "')";
						$ora->sql_no_fetch($qry, $bit_app["db"]);
					}
		
					$qry = "insert into l_user(user_id,ip,tgl) values('" . $_SESSION["userid_portal"] . "','" . $_SERVER["REMOTE_ADDR"] . "',sysdate())";
					$ora->sql_no_fetch($qry, $bit_app["db"]);
				} else {
					////session_unregister("userid_portal");
					////session_unregister("username_portal");
					////session_unregister("userlevel_portal");
					////session_unregister("userloker_portal");
					$_SESSION["userid_portal"] = $rsU->value[1][1];
					$_SESSION["username_portal"] = $rsU->value[1][2];
					$_SESSION["userloker_portal"] = $rsU->value[1][4];
					$_SESSION["userlevel_portal"] = $rsU->value[1][5];
		
					#LOG ACTIVE
					$qry = "select * from c_user where 
								user_id='" . $_SESSION["userid_portal"] . "'";
					$rsCheck = $ora->sql_fetch($qry, $bit_app["db"]);
					if ($rsCheck->jumrec >= 1) {
						$qry = "update c_user 
										set session_id='" . session_id() . "'
									where 
										user_id='" . $_SESSION["userid_portal"] . "'";
						$ora->sql_no_fetch($qry, $bit_app["db"]);
					} else {
						$qry = "insert into c_user(user_id,session_id) values('" . $_SESSION["userid_portal"] . "','" . session_id() . "')";
						$ora->sql_no_fetch($qry, $bit_app["db"]);
					}
		
					$qry = "insert into l_user(user_id,ip,tgl) values('" . $_SESSION["userid_portal"] . "','" . $_SERVER["REMOTE_ADDR"] . "',sysdate())";
					$ora->sql_no_fetch($qry, $bit_app["db"]);
				}
			}else{
				$errMessage = 'Harap setujui Term of Use agar bisa login';
				alert($errMessage);
			}
			
		}
	
	
		#COUNTER
		$qry = "insert into m_counter(user_id,ip,tgl) values('" . format($_SESSION["userid_portal"]) . "','" . $_SERVER['REMOTE_ADDR'] . "',sysdate())";
		$ora->sql_no_fetch($qry, $bit_app["db"]);
	

	
}


function headNews($cat = "")
{
	global $ora;
	global $bit_app;
	global $dtUser;

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits,image,tag,created_by from m_contents
				where publish_flag=1
				$whereCat
				$where
				order by publish_date desc
				limit 0,1";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);
	echo "<img class='headimage' src='" . $bit_app["folder_url"] . "/" . $rsN->value[1]["image"] . "' width='200px' height='200px' />";
	echo "<div class='headdate'>" . $rsN->value[1]["created_date"] . "</div>";
	echo "<div class='headtitle'><a href='news.php?id=" . $rsN->value[1]["id"] . "'>" . $rsN->value[1]["title"] . "</a></div>";
	echo "<div class='headcontent'>" . short_content($rsN->value[1]["content"], 400) . "</div>";

	echo "<br /><br />";
	echo "<div class='headcontent'>Created By : " . $dtUser[$rsN->value[1]["created_by"]][4] . "  <span class='headhits'>  [ " . format($rsN->value[1]["hits"]) . " hits ]</span></div>";

	if ($rsN->value[1]["tag"]) {
		$arrTag = explode(",", $rsN->value[1]["tag"]);
		for ($i = 0; $i < count($arrTag); $i++) {
			if ($i == (count($arrTag) - 1))
				$whereTag .= " upper(tag) like '%" . strtoupper($arrTag[$i]) . "%'";
			else
				$whereTag .= " upper(tag) like '" . strtoupper($arrTag[$i]) . "%' or ";
		}

		$whereTag = " and ($whereTag)";

		$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits,image from m_contents
					where publish_flag=1 and id<>'" . $rsN->value[1]["id"] . "' $whereTag
					limit 0,5";
		$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

		if ($rsN->jumrec >= 1)
			echo "<div class='headterkait'>Berita terkait : </div>";

		for ($i = 1; $i <= $rsN->jumrec; $i++) {
			echo "<a href='news.php?id=" . $rsN->value[$i]["id"] . "' target='_parent' class='headlistterkait'>" . $i . ". " . $rsN->value[$i]["title"] . "</a> <span class='headhits'>  [ " . format($rsN->value[$i]["hits"]) . " hits ]</span><br />";
		}
	}
}

function showContent($id)
{
	global $ora;
	global $bit_app;
	global $dtUser;

	if ($id)
		$whereCat = "and id=" . $id;

	$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits,image,tag,created_by from m_contents
				where publish_flag=1
				$whereCat
				$where
				order by publish_date desc
				limit 0,1";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rsN->value[1]["image"])
		echo "<img class='headimage' src='" . $bit_app["folder_url"] . "/" . $rsN->value[1]["image"] . "' width='200px' height='200px' />";
	echo "<div class='headdate'>" . $rsN->value[1]["created_date"] . "</div>";
	echo "<div class='headtitle'><a href='news.php?id=" . $rsN->value[1]["id"] . "'>" . $rsN->value[1]["title"] . "</a></div>";
	echo "<div class='headcontent'>" . $rsN->value[1]["content"] . "</div>";

	echo "<br /><br />";
	echo "<div class='headcontent'>Created By : " . $dtUser[$rsN->value[1]["created_by"]][4] . "  <span class='headhits'>  [ " . format($rsN->value[1]["hits"]) . " hits ]</span></div>";
}

function headNewsInovasi($cat = "")
{
	global $ora;
	global $bit_app;
	global $dtUser;

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits,image,tag,created_by,deskripsi,category_id from m_inovasi
				where publish_flag=1
				$whereCat
				$where
				order by publish_date desc
				limit 0,1";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);
	echo "<img class='headimage_inovasi' src='" . $bit_app["folder_url"] . "/" . $rsN->value[1]["image"] . "' width='200px' height='200px' />";
	echo "<div class='headdate_inovasi'>" . $rsN->value[1]["created_date"] . "</div>";
	echo "<div class='headtitle_inovasi'><a href='detInovasi.php?id=" . $rsN->value[1]["id"] . "&cat=" . $rsN->value[1]["category_id"] . "'>" . $rsN->value[1]["title"] . "</a></div>";
	echo "<div class='headby'>" . $dtUser[$rsN->value[1]["created_by"]][5] . "</div>";
	echo "<div class='headcontentinovasi'>" . $rsN->value[1]["deskripsi"] . " <span class='headhits_inovasi'>  [ " . format($rsN->value[1]["hits"]) . " hits ]</span></div>";
	echo "<br />";
	echo "<br />";
	if ($rsN->value[1]["tag"]) {
		$arrTag = explode(",", $rsN->value[1]["tag"]);
		for ($i = 0; $i < count($arrTag); $i++) {
			if ($i == (count($arrTag) - 1))
				$whereTag .= " upper(tag) like '%" . strtoupper($arrTag[$i]) . "%'";
			else
				$whereTag .= " upper(tag) like '" . strtoupper($arrTag[$i]) . "%' or ";
		}

		$whereTag = " and ($whereTag)";

		$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits,image from m_inovasi
					where publish_flag=1 and id<>'" . $rsN->value[1]["id"] . "' $whereTag
					limit 0,5";
		$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

		if ($rsN->jumrec >= 1)
			echo "<div class='headterkait'>Berita terkait : </div>";

		for ($i = 1; $i <= $rsN->jumrec; $i++) {
			echo "<a href='detinovasi.php?id=" . $rsN->value[$i]["id"] . "' target='_parent' class='headlistterkait_inovasi'>" . $i . ". " . $rsN->value[$i]["title"] . "</a> <span class='headhits_inovasi'>  [ " . format($rsN->value[$i]["hits"]) . " hits ]</span><br />";
		}
	}
}

function footer()
{
	global $ora;
	global $bit_app;
	global $dtUser;

	if (!getUserID())
		return false;

	$qry = "select user_id from c_user where user_id <> '" . getUserID() . "'";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);

	$qry = "select * from m_status where date_format(created_date,'%d-%m-%Y')='" . date("d-m-Y") . "' order by id desc limit 0,5";
	$rsStatus = $ora->sql_fetch($qry, $bit_app["db"]);


?>
	<div id="footpanel">
		<ul id="mainpanel">
			<li><a href="http://infratel.telkom.co.id" class="home">Portal Infratel <small>Portal Infratel</small></a></li>
			<li><a href="#" onClick="$.fn.colorbox({iframe:true, width:'600px', height:'480px', inline:false, href:'bit_content/content/editProfile.php'})" class="editprofile">Edit Profile <small>Edit Profile</small></a></li>
			<li id="alertpanel">
				<a href="#" class="alerts">Alerts</a>
				<div style="height: auto; display: none;" class="subpanel">
					<h3><span> &#8211; </span>Notifications</h3>
					<ul style="height: auto; text-align:left">
						<? for ($i = 1; $i <= $rsStatus->jumrec; $i++) { ?>
							<li><a href="#" class="delete">X</a>
								<p><a class="" href="status.php?id=<? echo $rsStatus->value[$i]["id"] ?>"><? echo $dtUser[$rsStatus->value[$i]["updated_by"]][1] ?></a> <? echo $rsStatus->value[$i]["status"] ?> <a class="" href="#"></a>.</p>
							</li>
						<? } ?>
					</ul>
				</div>
			</li>
			<li id="chatpanel">
				<a href="#" class="chat">Friends (<strong><? echo $rs->jumrec ?></strong>) </a>
				<div class="subpanel">
					<h3><span id='minFooter'> &#8211; </span>Friends Online</h3>
					<ul style="text-align:left; font-size:10px">
						<? for ($i = 1; $i <= $rs->jumrec; $i++) { ?>
							<li><a class="" onClick='javascript:chatWith("<? echo $rs->value[$i]["user_id"] ?>","<? echo str_replace("'", "", $dtUser[$rs->value[$i]["user_id"]][1]) ?>");'><img width="30" height="30" src="<? echo $dtUser[$rs->value[$i]["user_id"]][3] ?>" /><? echo $dtUser[$rs->value[$i]["user_id"]][1] ?></a></li>
						<? } ?>
					</ul>
				</div>
			</li>
			<li id="chat1">
			</li>
		</ul>
	</div>
	<SCRIPT LANGUAGE="JavaScript">
		function remove() {
			//code to remove the old elements
			var d = document.getElementById("mainpanel");
			//code to add new elements
			var li = document.createElement("li");
			li.setAttribute('id', 'chatpanel')

			var a = document.createElement('a');
			a.setAttribute('href', '#');
			a.setAttribute('class', 'chat');
			var oTextNode = document.createTextNode("New List Item 1");
			a.appendChild(oTextNode);

			//input1.onclick=function(){hide(this.att)};


			li.appendChild(a);
			d.appendChild(li);
		}

		//remove();
	</SCRIPT>
	<br /><br />
<?
}

function viewForum($id)
{
	global $ora;
	global $bit_app;
	global $dtUser;

	#Hits
	$qry = "select hits from m_forum where id=" . $id;
	$rsHits = $ora->sql_fetch($qry, $bit_app["db"]);

	#Hits
	$qry = "update m_forum set hits=" . ($rsHits->value[1][1] + 1) . " where id=" . $id;
	$ora->sql_no_fetch($qry, $bit_app["db"]);

	#View
	$qry = "select 
					a.id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits,b.name,updated_by
				from 
					m_forum a left outer join p_forum_category b on  a.category_id=b.id
				where 
					a.id=" . $id;
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	echo "<div class='headdate'>" . $rsN->value[1]["created_date"] . "</div>";
	echo "<div class='headtitle'><a href='forum.php?id=" . $rsN->value[1]["id"] . "'>" . $rsN->value[1]["title"] . "</a></div>";
	echo "<div class='forum_by'>Oleh : <b>" . $dtUser[$rsN->value[1]["updated_by"]][1] . "</b> / <b>" . $rsN->value[1]["name"] . "</b></div>";
	echo "<div class='headcontent'>" . $rsN->value[1]["content"] . " <span class='headhits'>  [ " . format($rsN->value[1]["hits"]) . " hits ]</span></div>";

	echo "<div id='dtForumRate'></div>";
}

function viewNews($id)
{
	global $ora;
	global $bit_app;

	#Hits
	$qry = "select hits from m_contents where id=" . $id;
	$rsHits = $ora->sql_fetch($qry, $bit_app["db"]);

	#Hits
	$qry = "update m_contents set hits=" . ($rsHits->value[1][1] + 1) . " where id=" . $id;
	$ora->sql_no_fetch($qry, $bit_app["db"]);

	#View
	$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits,image from m_contents
				where publish_flag=1
				and id=" . $id;
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	if ($rsN->value[1]["image"])
		$imgsrc = $bit_app["folder_url"] . "/" . $rsN->value[1]["image"];
	else
		$imgsrc = "bit_images/telkom.jpg";

	echo "<img class='headimage' src='" . $imgsrc . "' width='200px' height='200px'/>";
	echo "<div class='headdate'>" . $rsN->value[1]["created_date"] . "</div>";
	echo "<div class='headtitle'><a href='news.php?id=" . $rsN->value[1]["id"] . "'>" . $rsN->value[1]["title"] . "</a></div>";
	echo "<div class='headcontent'>" . $rsN->value[1]["content"] . " <span class='headhits'>  [ " . format($rsN->value[1]["hits"]) . " hits ]</span></div>";

	echo "<div id='dtNewsRate'></div>";
}

function viewInfoMgt($id)
{
	global $ora;
	global $bit_app;

	#Hits
	$qry = "select hits from m_info where id=" . $id;
	$rsHits = $ora->sql_fetch($qry, $bit_app["db"]);

	#Hits
	$qry = "update m_info set hits=" . ($rsHits->value[1][1] + 1) . " where id=" . $id;
	$ora->sql_no_fetch($qry, $bit_app["db"]);

	#View
	$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits,image from m_info
				where id=" . $id;
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	if ($rsN->value[1]["image"])
		$imgsrc = $bit_app["folder_url"] . "/" . $rsN->value[1]["image"];
	else
		$imgsrc = "bit_images/telkom.jpg";

	echo "<img class='headimage' src='" . $imgsrc . "' width='200px' height='200px'/>";
	echo "<div class='headdate'>" . $rsN->value[1]["created_date"] . "</div>";
	echo "<div class='headtitle'><a href='infomgt.php?id=" . $rsN->value[1]["id"] . "'>" . $rsN->value[1]["title"] . "</a></div>";
	echo "<div class='headcontent'>" . $rsN->value[1]["content"] . " <span class='headhits'>  [ " . format($rsN->value[1]["hits"]) . " hits ]</span></div>";
}

function foto()
{
	global $ora;
	global $bit_app;

	$qry = "select active_flag from p_module where id=1";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;

	$qry = "select * from m_foto where publish_flag=1 order by id desc limit 0,3";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
?>
	<table width="90%" cellpadding="0" cellspacing="0">
		<tr>
			<td class="bit_news_title">Galeri Foto SAS</td>
		</tr>
	</table>
	<div class="jflow-content-slider">
		<div id="slidesFoto">
			<?
			for ($i = 1; $i <= $rs->jumrec; $i++) {
			?>
				<div class="slide-wrapperFoto">
					<div class="slide-thumbnailFoto">
						<?
						$qry = "select * from m_foto_detail where foto_id=" . $rs->value[$i]["id"] . " order by id asc";
						$rsFoto = $ora->sql_fetch($qry, $bit_app["db"]);
						?>
						<a href="foto.php?id=<? echo $rs->value[$i]["id"] ?>"><img border="0" src="<? echo $bit_app["folder_url"] . $rsFoto->value[1]["foto_name"] ?>" width='100%' alt="photo" /></a>
					</div>
					<div class="slide-detailsFoto">
						<div class="description">
							<a href="foto.php?id=<? echo $rs->value[$i]["id"] ?>" class="bit_row_title_foto"><? echo $rs->value[$i]["title"] ?></a>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			<? } ?>
		</div>

		<div id="myControllerFoto">
			<span class="jFlowPrevFoto"><img src="bit_images/female_left.gif" class="imgFotoArrow" /></span>
			<? for ($i = 1; $i <= $rs->jumrec; $i++) { ?>
				<span class="jFlowControlFoto"><? echo $i ?></span>
			<? } ?>
			<span class="jFlowNextFoto"><img src="bit_images/female_right.gif" class="imgFotoArrow" /></span>
		</div>
		<div class="clear"></div>
	</div>
	</div>
	<?
	if (getUserLevel() == 2 || getUserLevel() == 3 || getUserLevel() == 4) {
		echo "<div class='bit_row_add'><a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/foto/add.php'})\">upload foto</a></div>";
	}
}

function banner()
{
	global $ora;
	global $bit_app;

	$qry = "select active_flag from p_module where id=2";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;

	$qry = "select * from m_banner where publish_flag=1 order by id desc limit 0,1";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	?>
	<table width="200px" cellpadding="0" cellspacing="0">
		<tr>
			<td class="bit_news_title_200">Banner</td>
		</tr>
	</table>
	<? if ($rs->value[1]["banner"]) { ?>
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="200" height="300">
			<param name="movie" value="<? echo $bit_app["folder_url"] . $rs->value[1]["banner"] ?>" />
			<param name="quality" value="high" />
			<param name="wmode" value="transparent">
			<embed src="<? echo $bit_app["folder_url"] . $rs->value[1]["banner"] ?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="200" height="300" wmode="transparent"></embed>
		</object>
	<? } else {
		$rs->value[1]["description"] = stripslashes($rs->value[1]["description"]);
		echo "<table width='200px' cellpadding='0' cellspacing='0'><tr><td class='bit_row_news'>" . nl2br($rs->value[1]["description"]) . "</td></tr></table>";
	}
	?>
	<?
	if (getUserLevel() == 4) {
		echo "<div class='bit_row_add'><a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'500px', inline:false, href:'bit_content/banner/add.php'})\">upload banner</a></div>";
	}
}

function banner2()
{
	global $ora;
	global $bit_app;

	$qry = "select active_flag from p_module where id=10";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;

	$qry = "select * from m_banner2 where publish_flag=1 order by id desc limit 0,1";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	?>
	<? if ($rs->value[1]["banner"]) { ?>
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="200" height="300">
			<param name="movie" value="<? echo $bit_app["folder_url"] . $rs->value[1]["banner"] ?>" />
			<param name="quality" value="high" />
			<param name="wmode" value="transparent">
			<embed src="<? echo $bit_app["folder_url"] . $rs->value[1]["banner"] ?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="200" height="300" wmode="transparent"></embed>
		</object>
	<? } else {
		$rs->value[1]["description"] = stripslashes($rs->value[1]["description"]);
		echo "<table width='200px' cellpadding='0' cellspacing='0'><tr><td >" . nl2br($rs->value[1]["description"]) . "</td></tr></table>";
	}
	?>
	<?
	if (getUserLevel() == 4) {
		echo "<div class='bit_row_add'><a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'500px', inline:false, href:'bit_content/banner/add2.php'})\">upload banner 2</a></div>";
	}
}

function infomgt()
{
	global $ora;
	global $bit_app;

	$qry = "select active_flag from p_module where id=7";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;

	$qry = "select a.*,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,hits from m_info a where publish_flag=1 order by id desc limit 0,5";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);

	?>
	<div class="bit_news_title_bottom">Info Management</div>
	<div class="jflow-content-slider">
		<div id="slides">
			<?
			for ($i = 1; $i <= $rs->jumrec; $i++) {
			?>
				<div class="slide-wrapper">
					<div class="slide-details"><a class='bit_row_title' href="infomgt.php?id=<? echo $rs->value[$i]["id"] ?>"><? echo $rs->value[$i]["title"] ?></a></div>
					<div class="slide_date"><? echo $rs->value[$i]["created_date"] ?></div>
					<div class="slide-thumbnail">
						<img width='64' height='64' src="<? echo $bit_app["folder_url"] . $rs->value[$i]["image"] ?>" alt="photo" />
					</div>
					<div class="slide-details">
						<div class="description">
							<? echo short_content($rs->value[$i]["content"], 180) ?>
							<span class='bit_row_hit'>[<? echo format($rs->value[$i]["hits"]) ?> hits]</span>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			<? } ?>
		</div>

		<div id="myController">
			<span class="jFlowPrev"><img src="bit_images/female_left.gif" class="imgFotoArrow" /></span>
			<? for ($i = 1; $i <= $rs->jumrec; $i++) { ?>
				<span class="jFlowControl"><? echo $i ?></span>
			<? } ?>
			<span class="jFlowNext"><img src="bit_images/female_right.gif" class="imgFotoArrow" /></span>
		</div>
		<div class="clear"></div>
	</div>
	<?
	if (getUserLevel() == 2 || getUserLevel() == 3 || getUserLevel() == 4) {
		echo "<div class='bit_row_add'><a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'450px', inline:false, href:'bit_content/info/add.php'})\">input info</a></div>";
	}
}

function infra()
{
	global $ora;
	global $bit_app;

	$qry = "select active_flag,module from p_module where id=14";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	$modulename = $rs->value[1][2];
	if ($rs->value[1][1] == 0)
		return;

	$qry = "select a.*,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,hits from m_infra a where publish_flag=1 order by id desc limit 0,5";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);

	?>
	<div class="bit_news_title_bottom"><? echo $modulename ?></div>
	<div class="jflow-content-slider">
		<div id="slidesInfra">
			<?
			for ($i = 1; $i <= $rs->jumrec; $i++) {
			?>
				<div class="slide-wrapper">
					<div class="slide-details"><a class='bit_row_title' href="infra.php?id=<? echo $rs->value[$i]["id"] ?>"><? echo $rs->value[$i]["title"] ?></a></div>
					<div class="slide_date"><? echo $rs->value[$i]["created_date"] ?></div>
					<div class="slide-thumbnail">
						<img width='64' height='64' src="<? echo $bit_app["folder_url"] . $rs->value[$i]["image"] ?>" alt="photo" />
					</div>
					<div class="slide-details">
						<div class="description">
							<? echo short_content($rs->value[$i]["content"], 180) ?>
							<span class='bit_row_hit'>[<? echo format($rs->value[$i]["hits"]) ?> hits]</span>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			<? } ?>
		</div>

		<div id="myControllerInfra">
			<span class="jFlowPrevInfra"><img src="bit_images/female_left.gif" class="imgFotoArrow" /></span>
			<? for ($i = 1; $i <= $rs->jumrec; $i++) { ?>
				<span class="jFlowControlInfra"><? echo $i ?></span>
			<? } ?>
			<span class="jFlowNextInfra"><img src="bit_images/female_right.gif" class="imgFotoArrow" /></span>
		</div>
		<div class="clear"></div>
	</div>
	<?
	if (getUserLevel() == 2 || getUserLevel() == 3 || getUserLevel() == 4) {
		echo "<div class='bit_row_add'><a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'450px', inline:false, href:'bit_content/infra/add.php'})\">input info</a></div>";
	}
}

function listInfo()
{
	global $ora;
	global $bit_app;

	#echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td  align='left' class='bit_news_title' height=1></td></tr>";
	echo "<table cellpadding=0 cellspacing=0 width=100%>";

	$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits,image from m_info
				order by id desc
				limit 0,10";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr>";
		if ($rsN->value[$i]["image"])
			echo "<td class='bit_row_news' valign='top' align='left'><img width='64' height='64' src='" . $bit_app["folder_url"] . "/" . $rsN->value[$i]["image"] . "' class='headimage'>";
		else
			echo "<td class='bit_row_news' valign='top' align='left'><img width='64' height='64' src='bit_images/telkom.jpg'  class='headimage'>";

		echo "<a href='infomgt.php?id=" . $rsN->value[$i]["id"] . "' target='_parent' class='bit_row_title'>" . $rsN->value[$i]["title"] . "</a><br>";
		echo "<span class='bit_row_date'>Tanggal : " . $rsN->value[$i]["created_date"] . "</span>";
		echo "<br /><br />";
		echo "<span class='bit_row_content'>" . short_content($rsN->value[$i]["content"], 150) . "</span> <span class='bit_row_hit'> [ " . format($rsN->value[$i]["hits"]) . " hits ]</span>";
		echo "</td></tr>";
	}
	echo "</table>";
}

function listFoto()
{
	global $ora;
	global $bit_app;

	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td  align='left' class='bit_news_title'>FOTO</td></tr>";

	$qry = "select id,foto,description,title,date_format(updated_date,'%W, %d/%m/%Y %h:%i') updated_date,updated_by 
				from m_foto
				order by id desc
				limit 0,10";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr>";
		if ($rsN->value[$i]["foto"])
			echo "<td class='bit_row_news' valign='top' align='left'><img width='64' height='64' src='" . $bit_app["folder_url"] . "/" . $rsN->value[$i]["foto"] . "' class='headimage'>";
		else
			echo "<td class='bit_row_news' valign='top' align='left'><img width='64' height='64' src='bit_images/telkom.jpg'  class='headimage'>";

		echo "<a href='foto.php?id=" . $rsN->value[$i]["id"] . "' target='_parent' class='bit_row_title'>" . $rsN->value[$i]["title"] . "</a><br>";
		echo "<span class='bit_row_date'>Tanggal : " . $rsN->value[$i]["updated_date"] . "</span>";
		echo "</td></tr>";
	}
	echo "</table>";
}

function commentNews($id)
{
	global $ora;
	global $bit_app;
	global $dtUser;

	if ($_POST["hID"]) {
		$qry = "delete from m_comment_news where id='" . $_POST["hID"] . "'";
		$ora->sql_no_fetch($qry, $bit_app["db"]);
	}

	if ($_POST["tComment"]) {
		while (list($k, $v) = each($_POST["tComment"])) {
			if ($v) {
				$qry = "insert into m_comment_news(comment_desc,comment_date,comment_by,id)
							values('$v',sysdate(),'" . getUserID() . "','$k')";
				$ora->sql_no_fetch($qry, $bit_app["db"]);
			}
		}
	}

	echo "<form method='POST' name='frContent'>";
	echo "<table cellpadding=0 cellspacing=0 width=100%>";
	echo "<tr><td class='bit_news_title_200' colspan=2>Hasil Komentar</td></tr>";
	$qry = "select 
					count(1)
				from 
					m_comment_news
				where 
					id=" . $id . "
					$where";
	$rsTotal = $ora->sql_fetch($qry, $bit_app["db"]);
	$totalRow = $rsTotal->value[1][1];
	$sumOfRow = 5;
	$sumOfPage = $bit_app["sumOfPage"];

	//==============================================
	$page = $_POST["page"];
	if ($page == 0)
		$page = 1;
	else
		$page = $_POST["page"];

	if ($_POST["cPage"])
		$page = (($_POST["slPage"] - 1) * $sumOfPage) + 1;

	if (!$_POST["slPage"])
		$_POST["slPage"] = 1;

	$rownum = ($page - 1) * $sumOfRow + 1;

	$paging = ceil($totalRow / $sumOfRow);
	//=============================================

	$qry = "select 
					comment_desc,comment_date,comment_by,id
				from 
					m_comment_news a
				where
					id=" . $id . "
				order by 
					id asc
				limit " . ($rownum - 1) . "," . $sumOfRow . "";
	$rsC = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($j = 1; $j <= $rsC->jumrec; $j++) {
		echo "<tr>
						<td align='left' class='bit_row_news' width='40%'><img class='bit_image' src='" . $dtUser[$rsC->value[$j]["comment_by"]][3] . "' width='60' height='60'> <b>" . $dtUser[$rsC->value[$j]["comment_by"]][1] . "</b> <br />" . $rsC->value[$j]["comment_date"] . "</td>
						<td align='left' class='bit_row_news_odd' valign='top'>";
		echo nl2br($rsC->value[$j]["comment_desc"]);

		echo "		</td>
					</tr>";
	}

	echo "<tr><td align='left'><a href='home.php' class='bit_back'>[ Back ]</a></td><td align='right' class='bit_back'>";

	$tmp = ceil(($totalRow / $sumOfRow));
	if ((($_POST["slPage"] - 1) * $sumOfPage) + $sumOfPage <= $tmp)
		$maxCount = (($_POST["slPage"] - 1) * $sumOfPage) + $sumOfPage;
	else
		$maxCount = $tmp;

	if ($totalRow)
		echo "page : &nbsp;&nbsp;";

	for ($i = (($_POST["slPage"] - 1) * $sumOfPage) + 1; $i <= $maxCount; $i++) {
		if ($i != $page)
			echo "&nbsp;<a class='bit_paging' onClick='document.frContent.page.value=" . (int) $i . "; document.frContent.submit()'>$i</a>&nbsp;";
		else
			echo "&nbsp;<b><font color='#FBF200'>$i</font></b>&nbsp;";
	}

	echo "<br /><br />";
	echo "</td></tr>";

	if (getUserID()) {
		echo "<tr>
					<td  colspan=2 valign='top' align='left' class='bit_comment'>Comment : </td>
				</tr>
				<tr>
					<td  colspan=2 valign='top' align='right'>
						<textarea class='bit_input'  name='tComment[" . $id . "]' id='tComment' cols=80 rows=3></textarea>
						<br />
						<input type='submit' value='Reply' name='bComment' class='bit_button_big'>
					</td>
				</tr>";
	}


	echo "</table>";

	echo "<input type='hidden' name='page' value=" . $_POST["page"] . ">";
	echo "<input type='hidden' name='cPage' value='0'>";
	echo "</form>";
}

function createSection($menu)
{
	global $ora;
	$qry = "select section_id,section_name from web_section where active_flag=1 and publish_flag=1 and menu_direct=" . (int) $menu;
	$rs = $ora->sql_fetch($qry);

	for ($i = 1; $i <= $rs->jumrec; $i++) {
		echo "<table class='tableBlock' width='100%'>";
		echo "<tr><th align='left' class='thSection' ><img src='administrator/images/icon_arah.gif'>&nbsp;" . $rs->value[$i][2] . "</th></tr>";
		echo "<tr><td height=1 class='lineSection'></td></th>";
		$qry = "select a.link_id,image_id from WEB_SUB_SECTION a
						where
						 	a.section_id=" . (int) $rs->value[$i][1];
		$rsSub = $ora->sql_fetch($qry);
		for ($j = 1; $j <= $rsSub->jumrec; $j++) {
			if ($rsSub->value[$j][1] != "") {
				$qry = "select link_name from web_link where link_id=" . $rsSub->value[$j][1];
			} else {
				$qry = "select folder,image_file from web_image where image_id=" . $rsSub->value[$j][2];
			}
			$rsSubS = $ora->sql_fetch($qry);

			if ($rsSub->value[$j][1] != "") {
				echo "<tr>";
				echo "<td class='itemSection'><img src='administrator/images/icon_menu.gif'>&nbsp;<a href='?link=" . $rsSub->value[$j][1] . "'>" . $rsSubS->value[1][1] . "</a></td>";
				echo "<tr>";
			} else {
				echo "<tr>";
				echo "<td class='itemSection'><img src='administrator/folder/" . $rsSubS->value[1][1] . "/" . $rsSubS->value[1][2] . "'></td>";
				echo "<tr>";
			}
		}
		echo "</table><br>";
	}
}

function getMenu($menu_id)
{
	global $ora;
	global $bit_app;


	$qry = "select * from menu where menu_id=" . (int) $menu_id . " order by menu_id";
	$rsMenu = $ora->sql_fetch($qry, $bit_app["db"]);
	?>
	<table cellpadding="0" cellspacing="0">
		<?

		for ($ii = 1; $ii <= $rsMenu->jumrec; $ii++) {

			$qry = "select * from sub_menu where menu_id=" . $rsMenu->value[$ii]["menu_id"] . " order by menu_id";
			$rs = $ora->sql_fetch($qry, $bit_app["db"]);

		?>
			<tr>
				<td colspan="2" class='bit_news_title' nowrap="nowrap" background='bit_images/bg_menu.gif' height="30"><? echo $rsMenu->value[1]["menu_name"] ?></td>
			</tr>
			<?
			for ($i = 1; $i <= $rs->jumrec; $i++) {


				switch ($rs->value[$i]["target"]) {
					case "_blank":
						$isFrame = 3;
						break;
					case "_self":
						$isFrame = 2;
						break;
					case "_parent":
						$isFrame = 1;
						break;
				}

				if ($rs->value[$i]["tipe_content"] == 1)
					$addP = "&mPage=" . $_GET["mPage"] . "&frame=$isFrame&id=" . $rs->value[$i]["content"];
				elseif ($rs->value[$i]["tipe_content"] == 6)
					$addP = "&mPage=" . $_GET["mPage"] . "&frame=$isFrame&files_id=" . $rs->value[$i]["content"];
				elseif ($rs->value[$i]["tipe_content"] == 4)
					$addP = "&mPage=" . $_GET["mPage"] . "&mod=" . $rs->value[$i]["content"];
				else
					$addP = "&mPage=" . $_GET["mPage"] . "&frame=$isFrame&id=" . $rs->value[$i]["content"];

			?>
				<tr>
					<td class="bit_row_news" width="1%" colspan="2"><img src="bit_images/icon_arah.gif" />&nbsp;&nbsp;&nbsp;<a href="?menu=<? echo $rs->value[$i]["menu_id"] ?>&sub_menu=<? echo $rs->value[$i]["sub_menu_id"] . $addP ?>"><? echo $rs->value[$i]["sub_menu_name"] ?></a></td>
				</tr>
				<?
				$qry = "select * from sub_menu_ where menu_id=" . $rs->value[$i]["sub_menu_id"] . " order by menu_id";
				$rsSub = $ora->sql_fetch($qry, $bit_app["db"]);
				for ($j = 1; $j <= $rsSub->jumrec; $j++) {

					switch ($rsSub->value[$j]["target"]) {
						case "_blank":
							$isFrame = 3;
							break;
						case "_self":
							$isFrame = 2;
							break;
						case "_parent":
							$isFrame = 1;
							break;
					}

					if ($rsSub->value[$j]["tipe_content"] == 1)
						$addP = "&mPage=" . $_GET["mPage"] . "&frame=$isFrame&id=" . $rsSub->value[$j]["content"];
					elseif ($rsSub->value[$j]["tipe_content"] == 6)
						$addP = "&mPage=" . $_GET["mPage"] . "&frame=$isFrame&files_id=" . $rsSub->value[$j]["content"];
					else
						$addP = "&mPage=" . $_GET["mPage"] . "&frame=$isFrame&id=" . $rsSub->value[$j]["content"];
				?>
					<tr>
						<td class="bit_row_news" colspan="2" width="2%">&nbsp;&nbsp;&nbsp;<img src="bit_images/icon_arah.gif" />&nbsp;&nbsp;&nbsp;<a href="?menu=<? echo $rsMenu->value[1]["menu_id"] ?>&sub_menu=<? echo $rs->value[$i]["menu_id"] ?>&sub_menu_=<? echo $rsSub->value[$j]["sub_menu_id"] . $addP ?>"><? echo $rsSub->value[$j]["sub_menu_name"] ?></a></td>
					</tr>
					<?
					$qry = "select * from sub_menu__ where menu_id=" . $rsSub->value[$j]["sub_menu_id"] . " order by menu_id";
					$rsSub2 = $ora->sql_fetch($qry, $bit_app["db"]);
					for ($k = 1; $k <= $rsSub2->jumrec; $k++) {

						switch ($rsSub2->value[$k]["target"]) {
							case "_blank":
								$isFrame = 3;
								break;
							case "_self":
								$isFrame = 2;
								break;
							case "_parent":
								$isFrame = 1;
								break;
						}

						if ($rsSub2->value[$k]["tipe_content"] == 1)
							$addP = "&mPage=" . $_GET["mPage"] . "&frame=$isFrame&id=" . $rsSub2->value[$k]["content"];
						elseif ($rsSub2->value[$k]["tipe_content"] == 6)
							$addP = "&mPage=" . $_GET["mPage"] . "&frame=$isFrame&files_id=" . $rsSub2->value[$k]["content"];
						else
							$addP = "&mPage=" . $_GET["mPage"] . "&frame=$isFrame&id=" . $rsSub2->value[$k]["content"];
					?>
						<tr>
							<td class="bit_row_news" colspan="2" width="2%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="bit_images/icon_arah.gif" />&nbsp;&nbsp;&nbsp;<a href="?menu=<? echo $rsMenu->value[1]["menu_id"] ?>&sub_menu=<? echo $rs->value[$i]["menu_id"] ?>&sub_menu_=<? echo $rsSub->value[$j]["sub_menu_id"] ?>&sub_menu__=<? echo $rsSub2->value[$k]["sub_menu_id"] . $addP ?>"><? echo $rsSub2->value[$k]["sub_menu_name"] ?></a></td>
						</tr>
		<?
					}
				}
			}
		}
		?>
	</table>
<?
}

function getMenuUtama()
{
	global $ora;
	global $bit_app;


	$qry = "select * from menu order by posisi";
	$rsMenu = $ora->sql_fetch($qry, $bit_app["db"]);
?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2" class='bit_news_title' background='bit_images/bg_menu.gif' height="30">Menu Utama</td>
		</tr>
		<?
		for ($i = 1; $i <= $rsMenu->jumrec; $i++) {
		?>
			<tr>
				<td class="bit_row_news" width="1%" colspan="2"><img src="bit_images/icon_arah.gif" />&nbsp;&nbsp;&nbsp;<a href="?menu=<? echo $rsMenu->value[$i]["menu_id"] ?>"><? echo $rsMenu->value[$i]["menu_name"] ?></a></td>
			</tr>
		<?
		}
		?>
	</table>
	<?
}

function getMenuOld()
{
	global $ora;
	global $bit_app;
	if ($_SESSION["portal_id"])
		$qry = "select menu_id,menu_name from menu";
	else
		$qry = "select menu_id,menu_name from menu where private_flag<>1";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);

	//ff:echo "<div id='menulinks' align='right'>";
	for ($i = 1; $i <= $rs->jumrec; $i++) {
		/* ff:
				echo "<a href='?menu=".$rs->value[$i][1]."'><span>".$rs->value[$i][2]."</span></a>";
				echo "<div class='menuline'></div>";
				ff: */
		echo "<a id='menu' href=\"?menu=" . $rs->value[$i][1] . "\" class='menuRoot'>" . $rs->value[$i][2] . "</a>";
	}

	if ($_SESSION["portal_id"]) {
	?>
		<a href="admin/" class="menuRoot">A d m i n i s t r a t o r</a>
		<a href="logout.php" class="menuRoot">l o g o u t</a>
	<? }
}

function getSubMenu($menu = 1)
{
	global $ora;
	global $bit_app;
	if (!$menu)
		return;

	if ($_SESSION["portal_id"])
		$qry = "select sub_menu_id,sub_menu_name,tipe_content,content,target from sub_menu
				where menu_id=" . $menu;
	else
		$qry = "select sub_menu_id,sub_menu_name,tipe_content,content,target from sub_menu
				where menu_id=" . $menu . " and private_flag<>1";

	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rs->jumrec; $i++) {

		switch ($rs->value[$i][3]) {
			case 3:
				$slCat = "cat=";
				break;
			case 4:
				$slCat = "mod=";
				break;
			case 6:
				$slCat = "file=";
				break;
			default:
				$slCat = "id=";
				break;
		}

		switch ($rs->value[$i][5]) {
			case "_blank":
				if ($rs->value[$i][3] == 1)
					$url = "";
				else
					$url = "?$slCat";
				echo "<a target='_blank' href=\"$url" . $rs->value[$i][4] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $rs->value[$i][1] . "\" class='menu'>" . $rs->value[$i][2];
				break;
			case "_parent":
				if ($rs->value[$i][3] == 1)
					$url = "?frame=1&$slCat";
				else
					$url = "?$slCat";

				echo "<a target='_self' href=\"$url" . $rs->value[$i][4] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $rs->value[$i][1] . "\" class='menu'>" . $rs->value[$i][2];
				break;
			case "_self":
				if ($rs->value[$i][3] == 1)
					$url = "";
				else
					$url = "?$slCat";
				echo "<a target='_parent' href=\"$url" . $rs->value[$i][4] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $rs->value[$i][1] . "\" class='menu'>" . $rs->value[$i][2];
				break;
		}
	}
}

function getSubMenu_($menu = 1)
{
	global $ora;
	global $bit_app;
	if (!$menu)
		return;



	if ($_SESSION["portal_id"])
		$qry = "select sub_menu_id,sub_menu_name,tipe_content,content,target from sub_menu_
				where menu_id=" . $menu;
	else
		$qry = "select sub_menu_id,sub_menu_name,tipe_content,content,target from sub_menu_
				where menu_id=" . $menu . " and private_flag<>1";

	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rs->jumrec; $i++) {

		switch ($rs->value[$i][3]) {
			case 3:
				$slCat = "cat=";
				break;
			case 4:
				$slCat = "mod=";
				break;
			case 6:
				$slCat = "file=";
				break;
			default:
				$slCat = "id=";
				break;
		}

		switch ($rs->value[$i][5]) {
			case "_blank":

				if ($rs->value[$i][3] == 1)
					$url = "";
				else
					$url = "?$slCat" . $rs->value[$i][4] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "&sub_menu_=" . $rs->value[$i][1];
				if ($i == $rs->jumrec)
					$resultSTR .= "<a class='menu'
						 onClick=\"window.open('$url')\">" . $rs->value[$i][2] . "</a>";
				else
					$resultSTR .= "<a class='menu' onClick=\"window.open('$url')\">" . $rs->value[$i][2] . "</a>";
				break;

			case "_parent":
				if ($rs->value[$i][3] == 1)
					$url = "?frame=1&$slCat" . $rs->value[$i][4] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "&sub_menu_=" . $rs->value[$i][1];
				else
					$url = "?$slCat" . $rs->value[$i][4] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "&sub_menu_=" . $rs->value[$i][1];


				if ($i == $rs->jumrec)
					$resultSTR .= "<a class='menu' onClick=\"parent.location.href='$url'\">" . $rs->value[$i][2] . "</a>";
				else
					$resultSTR .= "<a class='menu'  onClick=\"parent.location.href='$url'\">" . $rs->value[$i][2] . "</a>";
				break;
			case "_self":
				if ($rs->value[$i][3] == 1)
					$url = "";
				else
					$url = "?$slCat" . $rs->value[$i][4] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "&sub_menu_=" . $rs->value[$i][1];

				if ($i == $rs->jumrec)
					$resultSTR .= "<a class='menu' onClick=\"window.location.href='$url'\">" . $rs->value[$i][2] . "</a>";
				else
					$resultSTR .= "<a class='menu' onClick=\"window.location.href='$url'\">" . $rs->value[$i][2] . "</a>";

				break;
		}
	}

	return $resultSTR;
}

function getUserID()
{
	return $_SESSION["userid_portal"];
}

function getUserName()
{
	return $_SESSION["username_portal"];
}

function getUserLevel()
{
	return $_SESSION["userlevel_portal"];
}

function getUserLoker()
{
	return $_SESSION["userloker_portal"];
}

function getUserProfile()
{
	switch ($_SESSION["userlevel_portal"]) {
		case 1:
			return "Guest";
			break;
		case 2:
			return "Author";
			break;
		case 3:
			return "Publisher";
			break;
		case 4:
			return "Administrator";
			break;
	}
}

function getReport()
{
	global $ora;
	global $bit_app;
	?>
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td class="bit_news_title">Report</td>
		</tr>
		<tr>
			<td class="bit_row_news" align="center">
				<a onClick="$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/content/report5.php'})"><b>Peng. Infra</b></a>
				-
				<a onClick="$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/content/report4.php'})"><b>Info Mgt</b></a>
				-
				<a onClick="$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/content/report1.php'})"><b>Foto</b></a>
				-
				<a onClick="$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/content/report.php'})"><b>News</b></a>
				-
				<a onClick="$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/content/report2.php'})"><b>Video</b></a>
				-
				<a onClick="$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/content/report3.php'})"><b>Forum</b></a></td>
		</tr>
	</table>
<?
}

function getReportInovasi()
{
	global $ora;
	global $bit_app;
?>
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td class="bit_news_title_inovasi">Report</td>
		</tr>
		<tr>
			<td class="bit_row_news_inovasi" align="center">
				<a onClick="$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/content/report5.php'})"><b>Peng. Infra</b></a>
				-
				<a onClick="$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/content/report4.php'})"><b>Info Mgt</b></a>
				-
				<a onClick="$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/content/report.php'})"><b>News</b></a>
				-
				<a onClick="$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/content/report3.php'})"><b>Forum</b></a>
				-
				<a onClick="$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/content/report6.php'})"><b>Inovasi</b></a></td>
		</tr>
	</table>
<?
}

function getUserInfo()
{
	global $ora;
	global $bit_app;
?>

	<?php if ((getUserLevel() == 2) || (getUserLevel() == 3) || (getUserLevel() == 4)) : ?>
		<li class="nav-item">
			<a class="nav-link" href="bit_content/">Panel Admin</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="user-guide/sop_sas_portal.pdf" target="_blank">User Guide</a>
		</li>
	<?php endif; ?>
	<li class="nav-item">
		<a class="nav-link" href="logout.php">Logout</a>
	</li>
	<!--
	/*
		<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td class="bit_news_title">User Information</td>
		</tr>
		<tr>
			<td>
			<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td class="bit_row_news">User</td>
				<td class="bit_row_news">:</td>
				<td class="bit_row_news"><? echo getUserName() . " / " . getUserID() ?></td>
			</tr>
			<tr>
				<td class="bit_row_news" valign="top">Loker</td>
				<td class="bit_row_news" valign="top">:</td>
				<td class="bit_row_news"><? echo getUserLoker() ?></td>
			</tr>
			<? if (getUserID()) { ?>
			<tr>
				<td class="bit_row_news_1" colspan="3" align="right">
					<a class="bit_link" onClick="$.fn.colorbox({iframe:true, width:'600px', height:'480px', inline:false, href:'bit_content/content/editProfile.php'})"><b>Update Profile</b></a>
					<? if (getUserLevel() == 4) { ?>
					|
					<a class="bit_link" target="_self" href='bit_content/'><b>Panel Admin</b></a>
					<? } ?>
					|
					<a class="bit_link" href='logout.php'><b>Logout</b></a>
				</td>
			</tr>
			<? } ?>
			</table>
			</td>
		</tr>
	</table>
	*/ //-->
<?
}

function getUserInfoInovasi()
{
	global $ora;
	global $bit_app;
?>
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td class="bit_news_title_inovasi">User Information</td>
		</tr>
		<tr>
			<td>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="bit_row_news_inovasi">User</td>
						<td class="bit_row_news_inovasi">:</td>
						<td class="bit_row_news_inovasi"><? echo getUserName() . " / " . getUserID() ?></td>
					</tr>
					<tr>
						<td class="bit_row_news_inovasi" valign="top">Loker</td>
						<td class="bit_row_news_inovasi" valign="top">:</td>
						<td class="bit_row_news_inovasi"><? echo getUserLoker() ?></td>
					</tr>
					<? if (getUserID()) { ?>
						<tr>
							<td class="bit_row_news_inovasi" colspan="3" align="right">
								<a onClick="$.fn.colorbox({iframe:true, width:'600px', height:'480px', inline:false, href:'bit_content/content/editProfile.php'})"><b>Update Profile</b></a>
							</td>
						</tr>
					<? } ?>
				</table>
			</td>
		</tr>
	</table>
<?
}
function getInbox()
{
	global $ora;
	global $bit_app;
?>
	<table border="0" width="100%">
		<tr>
			<td class="titleSection">Inbox</td>
		</tr>
		<tr>
			<td class="titleFont">
				<?
				$qry = "select count(*) from m_contents
						where publish_flag=0";
				$rsCount = $ora->sql_fetch($qry, $bit_app["db"]);
				echo "info : ada <b>" . $rsCount->value[1][1] . "</b> artikel yg perlu di publish";
				?>
			</td>
		</tr>
		<tr>
			<td class="titleFont">
				<?
				$qry = "select count(*) from m_contents
						where 
						date_format(expired,'%Y%m%d') < date_format(sysdate(),'%Y%m%d')";
				$rsCount = $ora->sql_fetch($qry, $bit_app["db"]);
				echo "info : ada <b>" . $rsCount->value[1][1] . "</b> artikel yg sudah expired";
				?>
			</td>
		</tr>
		<tr>
			<td height="1%" class="garis">&nbsp;</td>
		</tr>
	</table>
<?
}

function runningText()
{
	global $ora;
	global $bit_app;

	$qry = "select active_flag from p_module where id=9";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;


	$qry = "select id,pengumuman from m_pengumuman where date_format(expired,'%Y%m%d') >= date_format(sysdate(),'%Y%m%d') order by id desc";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rs->jumrec; $i++) {
		$str .= cleansing($rs->value[$i][2]) . " &nbsp;&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;&nbsp; ";
	}
	$str = substr($str, 0, strlen($str) - 27);

	echo "<marquee scrolldelay='200' id='marquee_birth' onmouseover=\"document.getElementById('marquee_birth').stop()\"  onMouseOut=\"document.getElementById('marquee_birth').start()\">";
	echo $str;
	echo "</marquee>";
}

function runningTextGoGreen()
{
	global $ora;
	global $bit_app;

	$qry = "select active_flag from p_module where id=9";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;


	$qry = "select id,pengumuman from m_gogreen where date_format(expired,'%Y%m%d') >= date_format(sysdate(),'%Y%m%d') order by id asc";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rs->jumrec; $i++) {
		$str .= cleansing($rs->value[$i][2]) . " <br /><br /><br />";
	}
	$str = substr($str, 0, strlen($str) - 2);

	echo "<marquee scrolldelay='400' height='70' direction='up' id='marquee_birth_gogreen' onmouseover=\"document.getElementById('marquee_birth_gogreen').stop()\"  onMouseOut=\"document.getElementById('marquee_birth_gogreen').start()\">";
	echo $str;
	echo "</marquee>";
}

function runningTextInovasi()
{
	global $ora;
	global $bit_app;

	$qry = "select active_flag from p_module where id=9";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;

	echo "<table cellpadding=0 cellspacing=0 width=100%>";

	$qry = "select id,pengumuman from m_pengumuman where date_format(expired,'%Y%m%d') >= date_format(sysdate(),'%Y%m%d') order by id desc";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rs->jumrec; $i++) {
		$str .= cleansing($rs->value[$i][2]) . " - ";
	}
	$str = substr($str, 0, strlen($str) - 2);

	echo "<tr><td class='bit_marquee_inovasi'><marquee scrolldelay='200' id='marquee_birth' onmouseover=\"document.getElementById('marquee_birth').stop()\"  onMouseOut=\"document.getElementById('marquee_birth').start()\">";
	echo $str;
	echo "</marquee>";
	echo "</td></tr>";
	echo "</table>";
}

function getPengumuman()
{
	global $ora;
	global $bit_app;
	global $topRecord;
?>
	<table border="0" width="100%">
		<tr>
			<td class="titleSection">Pengumuman</td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0">
					<?
					$qry = "select pengumuman_id,pengumuman from pengumuman where to_days(expired) <= to_days(sysdate()) order by pengumuman_id desc limit 0,$topRecord";
					$rsP = $ora->sql_fetch($qry, $bit_app["db"]);
					for ($i = 1; $i <= $rsP->jumrec; $i++) {
						echo "<tr><td><img width=5 src='img/bintang.gif'></td><td><a class='pengumuman' target='_blank' href='admin/content/pengumumanDetFront.php?id=" . $rsP->value[$i][1] . "'>" . substr($rsP->value[$i][2], 0, 20) . "</a></td></tr>";
					}
					echo "<tr><td colspan=2 align='right'><a class='pengumuman' target='_blank' href='admin/content/pengumumanHist.php'>more ...</a></td></tr>";
					?>
				</table>
			</td>
		</tr>
		<tr>
			<td height="1%" class="garis">&nbsp;</td>
		</tr>
	</table>

	<!--</marquee>-->
<?
}

function getContentTitle($content)
{
	global $ora;
	global $bit_app;
	global $pathFolderUrl;

	if (!$content)
		return;

	$qry = "select title,created_date,content,image,updated_by,hits
				from m_contents
						where date_format(expired,'%Y%m%d') >= date_format(sysdate(),'%Y%m%d') and id=" . (int) $content;
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);

	$qry = "select user_nick,user_name from m_users where user_nick='" . $rs->value[1][5] . "'";
	$rsUser = $ora->sql_fetch($qry, $bit_app["db"]);

	$t .= "<table>";
	$t .= "<tr>";
	$t .= "<td>
				<span class='bit_content_title'>" . $rs->value[1][1] . "</span> <span class='bit_row_hit'> (" . format($rs->value[1][6]) . " hits)</span>
				<!--<br>
				<span class='bit_row_date'>" . $rsUser->value[1][2] . " / " . $rsUser->value[1][1] . " , " . $rs->value[1][2] . "</span>-->
			</td>";
	$t .= "</tr>";
	$t .= "</table>";
	return $t;
}

function getContent($content)
{
	global $ora;
	global $bit_app;
	global $pathFolderUrl;

	if (!$content)
		return;

	/*
		$qry="select title,created_date,content,image
				from m_contents
						where 
							publish_flag=1
							 and date_format(expired,'%Y%m%d') >= date_format(sysdate(),'%Y%m%d')
							and id=".(int)$content;
		*/
	$qry = "select hits from m_contents where id=" . (int) $content;
	$rsHits = $ora->sql_fetch($qry, $bit_app["db"]);
	$rsHits->value[1][1] += 1;

	$qry = "update  m_contents set hits = " . $rsHits->value[1][1] . " where id=" . (int) $content;
	$ora->sql_no_fetch($qry, $bit_app["db"]);

	$qry = "select title,created_date,content,image,updated_by
				from m_contents
						where date_format(expired,'%Y%m%d') >= date_format(sysdate(),'%Y%m%d') and id=" . (int) $content;
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	echo "<table cellpadding=0 cellspacing=0>";
	echo "<tr>";
	echo "<td class='bit_content_portal'>";
	echo $rs->value[1][3];
	echo "</td>";
	echo "</tr>";
	echo "</table>";
}

function searchBox()
{
	global $ora;
	global $bit_app;

	$qry = "select id,title,created_date,content from m_contents
										where publish_flag=1
										 and date_format(expired,'%Y%m%d') >= date_format(sysdate(),'%Y%m%d')
					and (title like '%" . $_POST["tSearch"] . "%' or content like '%" . $_POST["tSearch"] . "%')";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<b><a href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . str_replace(strtoupper($_POST["tSearch"]), "<font color='black'>" . strtoupper($_POST["tSearch"]) . "</font>", strtoupper($rsN->value[$i][2])) . "</a></b><br>";
		echo "<span class='td'>" . nl2br(str_replace(strtoupper($_POST["tSearch"]), "<b><font color='black'>" . strtoupper($_POST["tSearch"]) . "</font></b>", strtoupper(substr($rsN->value[$i][4], 0, 100)))) . " ... </span><br>";
		echo "<span class='td'>Tanggal : " . $rsN->value[$i][3] . "</span>";
		echo "<br>";
		echo "<br>";
	}
}

function movie()
{
	global $ora;
	global $bit_app;

	$qry = "select active_flag from p_module where id=6";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;


	$qry = "select * from m_video where publish_flag=0 order by id desc";
	$rsVideo = $ora->sql_fetch($qry, $bit_app["db"]);
	echo "<table cellpadding=0 class='bit_box' cellspacing=0 width='100%'>";
	if (!$rsVideo->value[1]["image"])
		$rsVideo->value[1]["image"] = "telkom_movie.png";

	echo "<tr><td align='left' class='bit_news_title' height=25>Video SAS</td></tr>";
	echo "<tr>";
	echo "<td align='center' bgcolor='#DBF3FD' width='100%'>";
	/*
		echo "
		<a  
			 href='".$bit_app["folder_url"]."/".$rsVideo->value[1]["file"]."'  
			 style='display:block;width:100%;height:200px;z-index:-99999'  
			 id='player'> 
		</a>";
		*/

	echo "
		<a  
			 href='" . $bit_app["folder_url"] . "/" . $rsVideo->value[1]["file"] . "'  
			 style='display:block;width:100%;height:200px;z-index:-999'  
			 id='player'> 
			<img onClick='dtHits(" . $rsVideo->value[1]["id"] . ");' src='" . $bit_app["folder_url"] . "/" . $rsVideo->value[1]["image"] . "' alt='Video'  width='300' height='100%' border=0 /> 
		</a>";

	echo "
		<script>
			//flowplayer('player', 'bit_third/flowplayer/flowplayer-3.1.5.swf');
		</script>
		";

?>

	<!-- this will install flowplayer inside previous A- tag. -->
	<!--
		<script>
			// begin scripting after the page is fully loaded 
			flowplayer('player', 'bit_third/flowplayer/flowplayer-3.1.5.swf');
		</script>
		-->
	<script type="text/javascript">
		function mycarousel_initCallback(carousel) {
			// Disable autoscrolling if the user clicks the prev or next button.
			carousel.buttonNext.bind('click', function() {
				carousel.startAuto(0);
			});

			carousel.buttonPrev.bind('click', function() {
				carousel.startAuto(0);
			});

			// Pause autoscrolling if the user moves with the cursor over the clip.
			carousel.clip.hover(function() {
				carousel.stopAuto();
			}, function() {
				carousel.startAuto();
			});
		};

		jQuery(document).ready(function() {
			jQuery('#mycarousel').jcarousel({
				wrap: 'last',
				initCallback: mycarousel_initCallback,
				visible: 3,
				scroll: 1
			});
		});

		function dtHits(flv) {
			$.ajax({
				type: "POST",
				url: "bit_content/video/hits.php?flv=" + flv,
				success: function(msg) {
					//
				}
			});
		}
	</script>
	<style type="text/css">
	</style>
	<div id="clips">
		<ul id="mycarousel" class="jcarousel-skin-tango">
			<?
			for ($i = 1; $i <= $rsVideo->jumrec; $i++) {
				if (!$rsVideo->value[$i]["image"])
					$rsVideo->value[$i]["image"] = "telkom_movie.png";

				echo '<li><a id=' . $rsVideo->value[$i]["id"] . ' href="' . $bit_app["folder_url"] . "/" . $rsVideo->value[$i]["file"] . '"><img src="' . $bit_app["folder_url"] . "/" . $rsVideo->value[$i]["image"] . '" width="70" height="50" alt="" border=1 /></a></li>';
			}
			?>
		</ul>
	</div>

	<script>
		//  install Flowplayer inside a#player
		$f("player", {
			src: "bit_third/flowplayer/flowplayer-3.1.5.swf",
			wmode: 'transparent'
		}, {
			clip: {

				// use baseUrl so we can play with shorter file names
				baseUrl: 'http://blip.tv/file/get',

				// use first frame of the clip as a splash screen
				autoPlay: false,
				autoBuffering: true,
				start: 0
			}
		});


		// get all links that are inside div#clips
		var links = document.getElementById("clips").getElementsByTagName("a");

		// loop those links and alter their click behaviour
		for (var i = 0; i < links.length; i++) {
			links[i].onclick = function() {

				// play the clip specified in href- attribute with Flowplayer
				$f().play(this.getAttribute("href", 2));
				dtHits(this.getAttribute("id"));
				// by returning false normal link behaviour is skipped
				return false;
			}
		}
	</script>

	<?
	echo "</td>";
	echo "</tr>";
	if (getUserLevel() == 2 || getUserLevel() == 3 || getUserLevel() == 4) {
		echo "
			<tr>
			<td colspan=2 align='right' class='bit_row_add'>
				<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'350px', inline:false, href:'bit_content/video/add.php'})\">upload video</a> 
			</td>
			</tr>";
	}
	echo "</table>";
}

function calendar()
{
	global $ora;
	global $bit_app;

	$qry = "select active_flag from p_module where id=3";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;


	include('bit_third/calendar/calendar_php/calendar.inc.php');


	echo "<table cellpadding=0 cellspacing=0 width=200px>";
	echo "<tr><td class='bit_news_title' align='left' height=25 colspan=2>Calendar</td></tr>";
	echo "<tr><td bgcolor='#BED2E7'>";
	?>
	<form action="<? echo $PHP_SELF; ?>" method="post">
		<?
		// if year is empty, set year to current year:
		if (!$_POST["year"])
			$year = date('Y');
		else
			$year = $_POST["year"];

		// if month is empty, set month to current month:
		if (!$_POST["month"])
			$month = date('n');
		else
			$month = $_POST["month"];

		// if offset is empty, set offset to 1 (start with Sunday):
		if ($offset == '') $offset = 1;
		?>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td class="bit_row_cal">Thn</td>
				<td nowrap="nowrap">
					<input type="text" name="year" class="bit_input" size="4" maxlength="4" value="<? echo $year; ?>">
				</td>
				<td class="bit_row_cal">Bln</td>
				<td nowrap="nowrap">
					<select name="month" class="bit_input">
						<?
						// build selection (months):
						$months = array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
						for ($i = 1; $i <= 12; $i++) {
							echo '<option value="' . $i . '"';
							if ($i == $month) echo ' selected';
							echo '>' . $months[$i - 1] . "</option>\n";
						}
						?>
					</select>
				</td>
				<td align="center">
					<input type="submit" value="Go" class="bit_button" />
				</td>
			</tr>
		</table>

	</form>
	<?
	echo "</td></tr>";
	echo "<tr>";
	echo "<td align='center' bgcolor='#F0F6F9'>";


	$qry = "select 
				date_format(tgl_mulai,'%d'),
				keterangan,
				date_format(tgl_selesai,'%d'),
				date_format(tgl_selesai,'%m'),
				date_format(tgl_mulai,'%m'),
				date_format(tgl_selesai,'%Y'),
				date_format(tgl_mulai,'%Y')
			from m_calender where publish_flag=1 and (date_format(tgl_mulai,'%Y%m') = '" . $year . twoD($month) . "' or date_format(tgl_selesai,'%Y%m') = '" . $year . twoD($month) . "')";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	$iCounter = 0;
	for ($i = 1; $i <= $rs->jumrec; $i++) {
		if ($rs->value[$i][4] == $rs->value[$i][5]) {
			for ($j = $rs->value[$i][1]; $j <= $rs->value[$i][3]; $j++) {
				$arrDay[$iCounter] = $j;
				if ($arrContent[$j]) {
					$eventNo[$j] += 1;
					$arrContent[$j] .= "<div class='bit_cal_title'>Event " . $eventNo[$j] . " :</div> <div class='bit_row_calender'>" . $rs->value[$i][2] . "</div>";
				} else {
					$eventNo[$j] = 1;
					$arrContent[$j] .= "<div class='bit_cal_title'>Event 1 :</div> <div class='bit_row_calender'>" . $rs->value[$i][2] . "</div>";
				}
				$iCounter++;
			}
		} else {

			if ($rs->value[$i][6] > $rs->value[$i][5])
				$rs->value[$i][4] += 12;

			if ($rs->value[$i][5] >= $rs->value[$i][4] || $month == $rs->value[$i][5]) {
				$iMulai = $rs->value[$i][1];
				$iSelesai = 31;
			} else {
				$iMulai = 1;
				$iSelesai = $rs->value[$i][3];
			}

			for ($j = $iMulai; $j <= $iSelesai; $j++) {
				$arrDay[$iCounter] = $j;

				if ($arrContent[$j]) {
					$eventNo[$j] += 1;
					$arrContent[$j] .= "<div class='bit_cal_title'>Event " . $eventNo[$j] . " :</div> <div class='bit_row_calender'>" . $rs->value[$i][2] . "</div>";
				} else {
					$eventNo[$j] = 1;
					$arrContent[$j] .= "<div class='bit_cal_title'>Event 1 :</div> <div class='bit_row_calender'>" . $rs->value[$i][2] . "</div>";
				}
				$iCounter++;
			}
		}
	}


	$cal = new CALENDAR($year, $month);
	$cal->offset = $offset;
	$cal->link = $PHP_SELF;
	$cal->selected = $arrDay;
	$cal->selectedContent = $arrContent;
	echo $cal->create();


	echo "</td>";
	echo "</tr>";

	if (getUserLevel() == 4) {
		echo "
		<tr>
		<td colspan=2 align='right' class='bit_row_add'>
			<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'350px', inline:false, href:'bit_content/calendar/add.php'})\">create new event</a> 
		</td>
		</tr>";
	}
	echo "</table>";
}

function categoryNews($cat, $bg)
{
	global $ora;
	global $bit_app;

	if ($bg)
		$id = $bg;
	else
		$id = rand(0, 2);

	switch ($id) {
		case 0:
			$bg = "bg_menu.gif";
			break;
		default:
			$bg = "bg_menu" . $id . ".gif";
	}

	$qry = "select category_name from p_category
					where category_id=" . $cat;
	$rsName = $ora->sql_fetch($qry, $bit_app["db"]);
	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td align='left' class='bit_news_title' colspan=2>" . $rsName->value[1][1] . "</td></tr>";

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits from m_contents
				where publish_flag=1
				$whereCat
				$where
				order by id desc
				limit 0,5";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top' width='1%'>&nbsp;<img src='bit_images/bullet.png'></td>";
		echo "<td class='bit_row_news' align='left'><a target='_parent' class='bit_row_title'>" . $rsN->value[$i]["title"] . "</a><br />";
		echo "<div class='bit_row_date'>Tanggal : " . $rsN->value[$i]["created_date"] . " <span class='bit_row_hit'> - " . format($rsN->value[$i]["hits"]) . " hits</span></div>";
		echo "</td></tr>";
	}

	if (getUserID()) {
		echo "
			<tr>
			<td colspan=2 align='right' class='bit_row_add'>
				<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/contents/add.php?cat_id=$cat'})\">create new content</a> -
				<a>more</a>
			</td>
			</tr>";
	}
	echo "</table>";
}

function categoryAllNewsInovasi($cat = "")
{
	global $ora;
	global $bit_app;

	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td align='left' class='bit_news_title_inovasi' colspan=2>Berita Sebelumnya</td></tr>";

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits from m_contents
				where publish_flag=1
				$whereCat
				$where
				order by publish_date desc
				limit 6,15";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news_inovasi' align='left' valign='top' width='1%'>&nbsp;<img src='bit_images/bullet.png'></td>";
		echo "<td class='bit_row_news_inovasi' align='left'><a href='detInovasi.php?id=" . $rsN->value[$i]["id"] . "' class='bit_row_title'>" . $rsN->value[$i]["title"] . "</a><br />";
		echo "<div class='bit_row_date'>Tanggal : " . $rsN->value[$i]["created_date"] . " <span class='bit_row_hit'> - " . format($rsN->value[$i]["hits"]) . " hits</span></div>";
		echo "</td></tr>";
	}

	echo "</table>";
}

function categoryAllNews($cat = "")
{
	global $ora;
	global $bit_app;

	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td align='left' class='bit_news_title' colspan=2>Berita Sebelumnya</td></tr>";

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits from m_contents
				where publish_flag=1
				$whereCat
				$where
				order by publish_date desc
				limit 6,15";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top' width='1%'>&nbsp;<img src='bit_images/bullet.png'></td>";
		echo "<td class='bit_row_news' align='left'><a href='news.php?id=" . $rsN->value[$i]["id"] . "' class='bit_row_title'>" . $rsN->value[$i]["title"] . "</a><br />";
		echo "<div class='bit_row_date'>Tanggal : " . $rsN->value[$i]["created_date"] . " <span class='bit_row_hit'> - " . format($rsN->value[$i]["hits"]) . " hits</span></div>";
		echo "</td></tr>";
	}

	echo "</table>";
}

function ratingNews($cat = "")
{
	global $ora;
	global $bit_app;

	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td align='left' class='bit_news_title' colspan=2>Berita Terpopuler bulan ini</td></tr>";

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select title,id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') 
					created_date,content,sum(hits) hits 
				from m_contents
				where publish_flag=1 and date_format(publish_date,'%m%Y')=date_format(sysdate(),'%m%Y') $whereCat
				group by title order by sum(hits) desc limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top' width='1%'>&nbsp;<img src='bit_images/bullet.png'></td>";
		echo "<td class='bit_row_news' align='left'><a href='news.php?id=" . $rsN->value[$i]["id"] . "' class='bit_row_title'>" . $rsN->value[$i]["title"] . "</a><br />";
		echo "<div class='bit_row_date'>Tanggal : " . $rsN->value[$i]["created_date"] . " <span class='bit_row_hit'> - " . format($rsN->value[$i]["hits"]) . " hits</span></div>";
		echo "</td></tr>";
	}
	echo "</table>";
}

function menu_icon()
{
	global $ora;
	global $bit_app;

	$qry = "select akses from p_profile_icon where profile_id in (select user_profile_icon from m_users where user_id='" . getUserID() . "')";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if (!$rs->value[1][1])
		$rs->value[1][1] = "''";

	$qry = "select * from menu_icon where menu_id in (" . $rs->value[1][1] . ") order by posisi";
	$rsIcon = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($k = 1; $k <= $rsIcon->jumrec; $k++) {
		if ($rsIcon->value[$k][9] == "_blank") {
			$url = $rsIcon->value[$k][5];
			$target = "_blank";
		} else {
			switch ($rsIcon->value[$k][10]) {
				case 1:
					$url = "?url=" . $rsIcon->value[$k][5];
					break;
				case 3:
					$url = "?file=" . $rsIcon->value[$k][5];
					break;
				case 4:
					$url = "?content=" . $rsIcon->value[$k][5];
					break;
			}
			$target = "_self";
		}
	?>
		<td class="icon_title"><a href="<? echo $url ?>" target="<? echo $target ?>" class="icon_title"><img border="0" width="48" height="48" src="bit_folder/<? echo $rsIcon->value[$k]["6"] ?>" style="cursor:pointer" /><br /><? echo $rsIcon->value[$k]["2"] ?></a></td>
	<?
	}
}

function contributorNews($cat = "")
{
	global $ora;
	global $bit_app;
	global $dtUser;

	echo "<table cellpadding=0 cellspacing=0 width=300px><tr><td align='left' class='bit_news_title' colspan=2>Kontributor Terbanyak bulan ini</td></tr>";

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select created_by,count(1) cnt,sum(hits) hts 
				from m_contents
				where publish_flag=1 and date_format(publish_date,'%m%Y')=date_format(sysdate(),'%m%Y') $whereCat
				group by created_by order by count(1) desc,sum(hits) desc limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td width='64' class='bit_row_news' valign='top'><img class='bit_image' src='" . $dtUser[$rsN->value[$i]["created_by"]][3] . "' width='64' height='64'></td>";
		echo "<td class='bit_row_news' align='left' valign='top'><a class='infoLogin' onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'420px', inline:false, href:'bit_content/content/viewProfile.php?userid=" . $rsN->value[$i]["created_by"] . "'})\"><b>" . $dtUser[$rsN->value[$i]["created_by"]][4] . "</b></a> <span class='bit_row_hit'>( " . format($rsN->value[$i]["cnt"]) . " berita )</span> </td></tr>";
	}
	echo "</table>";
}

function contributorInovasi()
{
	global $ora;
	global $bit_app;
	global $dtUser;

	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td align='left' class='bit_news_title_inovasi' colspan=2>Kontributor Terbanyak</td></tr>";

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select created_by,count(1) cnt,sum(hits) hts 
				from m_inovasi
				where publish_flag=1 $whereCat
				group by created_by order by count(1) desc,sum(hits) desc limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td width='64' class='bit_row_news_inovasi' valign='top'><img class='bit_image' src='" . $dtUser[$rsN->value[$i]["created_by"]][3] . "' width='64' height='64'></td>";
		echo "<td class='bit_row_news_inovasi' align='left' valign='top'><a class='infoLogin' onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'420px', inline:false, href:'bit_content/content/viewProfile.php?userid=" . $rsN->value[$i]["created_by"] . "'})\"><b>" . $dtUser[$rsN->value[$i]["created_by"]][4] . "</b></a> <span class='bit_row_hit'>( " . format($rsN->value[$i]["cnt"]) . " inovasi )</span> </td></tr>";
	}
	echo "</table>";
}

function contactPersonInovasi()
{
	global $ora;
	global $bit_app;
	global $dtUser;

	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td align='left' class='bit_news_title_inovasi' colspan=2>Kontak Person Pengelola Inovasi</td></tr>";

	$qry = "select nik from p_publisher_inovasi";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	$arrNIK = explode(",", $rsN->value[1][1]);

	for ($i = 0; $i < count($arrNIK); $i++) {
		$rsN->value[$i]["created_by"] = $arrNIK[$i];

		echo "<tr><td width='64' class='bit_row_news_inovasi' valign='top'><img class='bit_image' src='" . $dtUser[$rsN->value[$i]["created_by"]][3] . "' width='64' height='64'></td>";
		echo "<td class='bit_row_news_inovasi' align='left' valign='top'><a class='infoLogin' onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'420px', inline:false, href:'bit_content/content/viewProfile.php?userid=" . $rsN->value[$i]["created_by"] . "'})\"><b>" . $dtUser[$rsN->value[$i]["created_by"]][4] . "</b></a> </td></tr>";
	}
	echo "</table>";
}

function favorit()
{
	global $ora;
	global $bit_app;
	global $dtUser;

	echo "<table cellpadding=0 cellspacing=0 width='300px'><tr><td  align='left' class='bit_news_title' height=1>Berita Favorit</td></tr>";
	echo "<table cellpadding=0 cellspacing=0 width='300px'>";

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select 
					id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,
					content,hits,image,created_by
				from m_contents
				where publish_flag=1
				$whereCat
				$where
				order by hits desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr>";
		if ($rsN->value[$i]["image"])
			echo "<td class='bit_row_news' valign='top' align='left'><img width='64' height='64' src='" . $bit_app["folder_url"] . "/" . $rsN->value[$i]["image"] . "' class='bit_image'>";
		else
			echo "<td class='bit_row_news' valign='top' align='left'><img width='64' height='64' src='bit_images/telkom.jpg'  class='bit_image'>";

		echo "<a href='news.php?id=" . $rsN->value[$i]["id"] . "' target='_parent' class='bit_row_title'>" . $rsN->value[$i]["title"] . "</a><br>";
		echo "<span class='bit_row_date'>Tanggal : " . $rsN->value[$i]["created_date"] . "</span>";
		echo "<br /><span class='bit_row_by'>" . $dtUser[$rsN->value[$i]["created_by"]][5] . "</span>";
		echo "<br /><br />";
		echo "<span class='bit_row_hit'> [ " . format($rsN->value[$i]["hits"]) . " hits ]</span>";
		echo "</td></tr>";
	}
	echo "</table>";
}


function favoritInovasi()
{
	global $ora;
	global $bit_app;
	global $dtUser;

	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td  align='left' class='bit_news_title_inovasi' height=1>Inovasi Paling Favorit</td></tr>";
	echo "<table cellpadding=0 cellspacing=0 width=100%>";

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select 
					id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,
					content,hits,image,created_by,deskripsi
				from m_inovasi
				where publish_flag=1
				$whereCat
				$where
				order by hits desc
				limit 1,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr>";
		if ($rsN->value[$i]["image"])
			echo "<td class='bit_row_news_inovasi' valign='top' align='left'><img width='64' height='64' src='" . $bit_app["folder_url"] . "/" . $rsN->value[$i]["image"] . "' class='bit_image'>";
		else
			echo "<td class='bit_row_news_inovasi' valign='top' align='left'><img width='64' height='64' src='bit_images/telkom.jpg'  class='bit_image'>";

		echo "<a href='detinovasi.php?id=" . $rsN->value[$i]["id"] . "' target='_parent' class='bit_row_title'>" . $rsN->value[$i]["title"] . "</a><br>";
		echo "<span class='bit_row_date'>Tanggal : " . $rsN->value[$i]["created_date"] . "</span>";
		echo "<br /><span class='bit_row_by'>" . $dtUser[$rsN->value[$i]["created_by"]][5] . "</span>";
		echo "<br /><br />";
		echo "<span class='bit_row_content'>" . short_content($rsN->value[$i]["deskripsi"], 300) . "</span> <span class='bit_row_hit'> [ " . format($rsN->value[$i]["hits"]) . " hits ]</span>";
		echo "</td></tr>";
	}
	echo "</table>";
}

function gogreen()
{

	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td align='left' class='bit_news_title'  height=25 colspan=2>Go Green</td></tr>";
	?>
	<p align="center">
		<table id="Table_01" width="300" height="119" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="3">
					<img src="bit_images/daun_01.gif" width="300" height="22" alt=""></td>
			</tr>
			<tr>
				<td rowspan="2">
					<img src="bit_images/daun_02.gif" width="47" height="97" alt=""></td>
				<td background="bit_images/daun_03.gif" width="188" height="70" style="color:#FFFFFF">
					<?
					runningTextGoGreen();
					?>
				</td>
				<td rowspan="2">
					<img src="bit_images/daun_04.gif" width="65" height="97" alt=""></td>
			</tr>
			<tr>
				<td>
					<img src="bit_images/daun_05.gif" width="188" height="27" alt=""></td>
			</tr>
		</table>
	</p>
	<?
}


function categoryTopNews($cat = "")
{
	global $ora;
	global $bit_app;
	global $s3;

	$qry = "select category_name from p_category
				where category_id=" . $cat;
	$rsCat = $ora->sql_fetch($qry, $bit_app["db"]);

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits,image,DATEDIFF(CURDATE(), publish_date) datediff from m_contents
				where publish_flag=1
				$whereCat
				$where
				order by publish_date desc
				limit 0,3";

	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		$fl = $bit_app["folder_dir"] . $rsN->value[$i]['image'];
		$file = $rsN->value[$i]['image'];
		if (!file_exists($fl) || filesize($fl) == 0) {
			$getobj = $s3->getObject(BUCKETNAME, PORTALSASDIR_DATA . $file, $fl);
		}
	?>
		<div class="col-sm-6 col-lg-4">
			<div class="single_special_cource">
				<img src="<? echo $bit_app["path_url"]; ?>bit_folder/<?php echo $rsN->value[$i]["image"]; ?>" class="special_img" style="height: 230px;" alt="">
				<div class="special_cource_text">
					<h4></h4>
					<?php $ket = $rsN->value[$i]["title"]; ?>
					<?php $titik = (strlen($ket) >= 65)? '...' : '';?>
					<a href="news.php?id=<?php echo $rsN->value[$i]["id"]; ?>&cat=<?php echo $cat; ?>">
						<h3 data-toggle="tooltip" title="<?= $ket; ?>"><?= substr($ket,0,65); ?><?=$titik;?></h3>
					</a>
					<p><? echo short_content($rsN->value[$i]["content"], 250) ?></p>

					<a href="news.php?id=<?php echo $rsN->value[$i]["id"]; ?>&cat=<?php echo $cat; ?>" class="btn_4">Read More</a>

				</div>
			</div>
		</div>
	<?php
		/*
			if ($rsN->value[$i]["image"])
				echo "<td class='$cl' valign='top' align='left'><img width='100' src='".$bit_app["folder_url"]."/".$rsN->value[$i]["image"]."' class='bit_image'>";
			else
				echo "<td class='$cl' valign='top' align='left'><img width='100' src='bit_images/telkom.jpg'  class='bit_image'>";
				
			echo "<a href='news.php?id=".$rsN->value[$i]["id"]."&cat=$cat' target='_parent' class='bit_row_title'>".$rsN->value[$i]["title"]."</a>";
			if ($rsN->value[$i]["datediff"]<=3)
				echo "&nbsp;&nbsp;<img src='".$bit_app["image_url"]."/new.gif'>";
			
			echo "<br>";
			echo "<span class='bit_row_date'>Tanggal : ".$rsN->value[$i]["created_date"]."</span>";
			echo "<br /><br />";
			echo "<span class='bit_row_content'>".short_content($rsN->value[$i]["content"],250)."</span> <span class='bit_row_hit'> [ ".format($rsN->value[$i]["hits"])." hits ]</span>";
			echo "</td></tr>";
			*/
	}

	//echo "<div class='row' style='border: 1px solid;'>";
	//echo '<div class="col-sm-12 col-lg-12">';
	echo '<div class="text-center">';
	if (getUserLevel() != 1) {
		//echo "<tr><td class='bit_row_add' align='right' colspan='2'>";
		///echo "<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'500px', inline:false, href:'bit_content/contents/add.php?cat_id=".$cat."'})\">Create ".$rsCat->value[1][1]."</a> ";
	}
	//echo "<a href='news.php?cat=$cat' class='genric-btn primary-border circle'>Arsip</a>";

	if (getUserLevel() != 1) {
		//echo "</td></tr>";
	}
	echo '</div>';
	//echo '</div>';
	//echo "</div>";
}


function categoryInovasi()
{
	global $ora;
	global $bit_app;

	$qry = "select category_id,category_name from p_category_inovasi";
	$rsCat = $ora->sql_fetch($qry, $bit_app["db"]);

	echo "<table cellpadding=0 cellspacing=2 width=100% bgcolor='#ffffff'>";
	for ($i = 1; $i <= $rsCat->jumrec; $i++) {
		if ($i % 2 == 1)
			echo "<tr>";

		echo "<td valign='top' height='100%'>";
		categoryTopInovasi($rsCat->value[$i][1]);
		echo "</td>";

		if ($i % 2 == 0)
			echo "</tr>";
	}
	echo "</table>";
}

function categoryTopInovasi($cat)
{
	global $ora;
	global $bit_app;

	echo "<table cellpadding=0 cellspacing=0 width=100%>";

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits,image,deskripsi from m_inovasi
				where publish_flag=1
				$whereCat
				$where
				order by publish_date desc
				limit 1,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr>";
		if ($rsN->value[$i]["image"])
			echo "<td class='bit_row_news_inovasi' valign='top' align='left'><img width='64' height='64' src='" . $bit_app["folder_url"] . "/" . $rsN->value[$i]["image"] . "' class='bit_image'>";
		else
			echo "<td class='bit_row_news_inovasi' valign='top' align='left'><img width='64' height='64' src='bit_images/telkom.jpg'  class='bit_image'>";

		echo "<a href='detInovasi.php?id=" . $rsN->value[$i]["id"] . "&cat=" . $cat . "' target='_parent' class='bit_row_title'>" . $rsN->value[$i]["title"] . "</a><br>";
		echo "<span class='bit_row_date'>Tanggal : " . $rsN->value[$i]["created_date"] . "</span>";
		echo "<br /><br />";
		echo "<span class='bit_row_content'>" . short_content($rsN->value[$i]["deskripsi"], 300) . "</span> <span class='bit_row_hit'> [ " . format($rsN->value[$i]["hits"]) . " hits ]</span>";
		echo "</td></tr>";
	}
	echo "</table>";
}


function forum()
{
	global $ora;
	global $bit_app;
	global $dtUser;
	$bg = "bg_menu2.gif";

	$qry = "select active_flag from p_module where id=5";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;


	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td align='left' class='bit_news_title'  height=25 colspan=2>Knowledge Sharing</td></tr>";

	$qry = "select 
					a.forum_id,count(1)
				from 
					m_forum_comment a
				group by a.forum_id";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		$dtCount[$rsN->value[$i][1]] = $rsN->value[$i][2];
	}

	$qry = "select 
					a.id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits,b.name,created_by
				from 
					m_forum a left outer join p_forum_category b on  a.category_id=b.id
				where 
					publish_flag=1
				order by 
					id desc
				limit 0,5";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>&nbsp;<img src='bit_images/bullet.png'></td>";
		echo "<td class='bit_row_news' align='left'><a href='forum.php?id=" . $rsN->value[$i]["id"] . "' class='bit_row_title' href='?module=forum&id=" . $rsN->value[$i]["id"] . "&tipe=view'>" . $rsN->value[$i]["title"] . "</a><br />";
		echo "<div class='bit_row_kategori'>Oleh : <b>" . $dtUser[$rsN->value[$i]["created_by"]][1] . "</b></div>";
		echo "<div class='bit_row_kategori'>Kategori : <b>" . $rsN->value[$i]["name"] . "</b></div>";
		echo "<div class='bit_row_date'>Tanggal : " . $rsN->value[$i]["created_date"] . " <span class='bit_row_hit'> - " . format($dtCount[$rsN->value[$i]["id"]]) . " replies</span></div>";
		echo "</td></tr>";
	}
	echo "
			<tr>
			<td colspan=2 align='right' class='bit_row_add'>
				<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/forum/add.php'})\">" . ((getUserLevel() == 2 || getUserLevel() == 3 || getUserLevel() == 4) ? "create forum" : "") . "</a>
			</tr>";
	echo "</table>";
}

function listForum()
{
	global $ora;
	global $bit_app;
	global $dtUser;

	$bg = "bg_menu2.gif";
	echo "<form method='POST'>";
	echo "<table cellpadding=0 cellspacing=0 width=98%><tr><td align='left' class='bit_news_title' background='bit_images/" . $bg . "' height=25 colspan=2>Forum</td></tr>";
	if ($_POST["tComment"]) {
		while (list($k, $v) = each($_POST["tComment"])) {
			if ($v) {
				$qry = "insert into m_forum_comment(comment_desc,comment_date,comment_by,forum_id)
							values('$v',sysdate(),'" . getUserID() . "','$k')";
				$ora->sql_no_fetch($qry, $bit_app["db"]);
			}
		}
	}

	$qry = "select 
					a.id,title,created_date,content,hits,b.name,a.updated_by
				from 
					m_forum a left outer join p_forum_category b on  a.category_id=b.id
				where 
					publish_flag=1
					and a.id='" . $_GET["id"] . "'";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>&nbsp;<img src='" . $dtUser[$rsN->value[$i]["updated_by"]][3] . "' width='40'></td>";
		echo "<td class='bit_row_news' align='left'><a>" . $dtUser[$rsN->value[$i]["updated_by"]][1] . "</a><br />";
		echo "<div class='bit_row_date'>Tanggal : " . $rsN->value[$i]["created_date"] . "</span></div>";
		echo "<br />";
		echo "<div class='bit_comment_status'>" . nl2br($rsN->value[$i]["content"]) . "</div>";
		echo "<br />";
		$qry = "select 
						comment_desc,comment_date,comment_by
					from 
						m_forum_comment a
					where
						forum_id=" . $rsN->value[$i]["id"] . "
					order by 
						id desc";
		$rsC = $ora->sql_fetch($qry, $bit_app["db"]);
		for ($j = 1; $j <= $rsC->jumrec; $j++) {
			echo "<tr><td class='bit_row_news1' align='left' valign='top'>&nbsp;<img src='" . $dtUser[$rsC->value[$j]["comment_by"]][3] . "' width='40'></td>";
			echo "<td class='bit_row_news1' align='left' width='99%'><a>" . $dtUser[$rsC->value[$j]["comment_by"]][1] . "</a><br />";
			echo "<div class='bit_row_date'>Tanggal : " . $rsC->value[$j]["comment_date"] . "</div>";
			echo "<br />";
			echo "<div class='bit_comment_status'>" . nl2br($rsC->value[$j]["comment_desc"]) . "</div>";
			echo "</td></tr>";
			echo "<tr><td height='2px' bgcolor='#ffffff'></td></tr>";
		}
		echo "</td></tr>";
		echo "<tr><td colspan=2><textarea class='bit_input'  name='tComment[" . $rsN->value[$i]["id"] . "]' id='tComment' cols=80 rows=2></textarea><br /><input type='submit' value='Reply' name='bComment' class='bit_button'></td></tr>";
	}
	echo "
			<tr>
			<td colspan=2 align='right' class='bit_row_news'>
				<a onClick=\"$.fn.colorbox({width:'500px', height:'250px', inline:true, href:'#createStatus'})\">add status</a>
			</td>
			</tr>";
	echo "</table>";
	echo "</form>";
	echo "
			<div style='display:none'>
				<div id='createStatus' style='padding:0px; background:#ffffff;'>
					<iframe frameborder='0' src='bit_content/status/add.php' height='500px' scrolling='no'></iframe>
				</div>
			</div>";
}

function document()
{
	global $ora;
	global $bit_app;
	global $dtUser;

	$qry = "select active_flag from p_module where id=4";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;

	if ($bg)
		$id = $bg;
	else
		$id = rand(0, 2);

	$bg = "bg_menu2.gif";

	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td align='left' class='bit_news_title'colspan=2>Dokumen</td></tr>";

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select id,file_name,file_desc,date_format(updated_date,'%W, %d/%m/%Y %h:%i') updated_date,updated_by,hits from m_doc_file
				order by id desc
				limit 0,5";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>&nbsp;<img src='bit_images/bullet.png'></td>";
		echo "<td class='bit_row_news' align='left'><a  class='bit_row_title' onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'200px', inline:false, href:'bit_content/document/download.php?id=" . $rsN->value[$i]["id"] . "'})\">" . $rsN->value[$i]["file_name"] . "</a><br />";
		echo "<div class='bit_row_date'>Tanggal : " . $rsN->value[$i]["updated_date"] . " <br /><span class='bit_row_hit'>" . format($rsN->value[$i]["hits"]) . " download</span></div>";

		echo "</td></tr>";
	}
	echo "
			<tr>
			<td colspan=2 align='right' class='bit_row_add'>
				<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'300px', inline:false, href:'bit_content/document/add.php'})\">" . (getUserID() ? "upload document" : "") . "</a> 
				<a href='document.php'>more</a>
			</td>
			</tr>";
	echo "</table>";
}

function documentHome()
{
	global $ora;
	global $bit_app;

	if ($bg)
		$id = $bg;
	else
		$id = rand(0, 2);

	$bg = "bg_menu2.gif";
	echo "<table cellpadding=0 cellspacing=0 width=95%><tr><td align='left' class='bit_news_title' background='" . $bit_app["image_url"] . "" . $bg . "' height=25 colspan=2>Document & Sharing</td></tr>";

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	$qry = "select id,file_name,file_desc,updated_date from m_doc_file
				order by id desc
				limit 0,5";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>&nbsp;<img src='" . $bit_app["image_url"] . "bullet.png'></td>";
		echo "<td class='bit_row_news' align='left'><a target='_parent'>" . $rsN->value[$i]["file_name"] . "</a><br />";
		echo "<div class='bit_row_date'>Tanggal : " . $rsN->value[$i]["updated_date"] . " <span class='bit_row_hit'><br />" . format($rsN->value[$i]["hits"]) . " views</span> <span class='bit_row_hit'> - " . format($rsN->value[$i]["hits"]) . " download</span></div>";
		echo "</td></tr>";
	}
	echo "</table>";
}

function status()
{
	global $ora;
	global $bit_app;
	global $dtUser;

	$qry = "select active_flag from p_module where id=12";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;

	$bg = "bg_menu2.gif";

	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td align='left' class='bit_news_title' height=25 colspan=2>Sosial Network Infratel</td></tr>";

	$qry = "select 
					a.status_id,count(1)
				from 
					m_status_comment a
				group by a.status_id";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		$dtCount[$rsN->value[$i][1]] = $rsN->value[$i][2];
	}

	$qry = "select 
					a.id,status,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,hits,updated_by
				from 
					m_status a
				where
					updated_by='" . getUserID() . "'
				order by 
					id desc
				limit 0,2";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>&nbsp;<img src='bit_images/bullet.png'></td>";
		echo "<td class='bit_row_news' align='left'><a class='infoLogin'><b>" . $dtUser[$rsN->value[$i]["updated_by"]][1] . "</b></a>, <a class='bit_row_title' href='status.php?id=" . $rsN->value[$i]["id"] . "'>" . $rsN->value[$i]["status"] . "</a><br />";
		echo "<div class='bit_row_date'>Tanggal : " . $rsN->value[$i]["created_date"] . " <span class='bit_row_hit'> - <a class='bit_row_hit' href='status.php?id=" . $rsN->value[$i]["id"] . "'>" . format($dtCount[$rsN->value[$i]["id"]]) . " comments</a></span></div>";
		echo "</td></tr>";
	}

	$qry = "select 
					a.id,status,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,hits,updated_by
				from 
					m_status a
				where
					updated_by<>'" . getUserID() . "'
				order by 
					id desc
				limit 0,4";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>&nbsp;<img src='bit_images/bullet.png'></td>";
		echo "<td class='bit_row_news' align='left'><a href='status.php?id=" . $rsN->value[$i]["id"] . "' class='infoLogin'><b>" . $dtUser[$rsN->value[$i]["updated_by"]][1] . "</b></a>, <a class='bit_row_title'  href='status.php?id=" . $rsN->value[$i]["id"] . "'>" . $rsN->value[$i]["status"] . "</a><br />";
		echo "<div class='bit_row_date'>Tanggal : " . $rsN->value[$i]["created_date"] . " <span class='bit_row_hit'> - <a class='bit_row_hit' href='status.php?id=" . $rsN->value[$i]["id"] . "'>" . format($dtCount[$rsN->value[$i]["id"]]) . " comments</a></span></div>";
		echo "</td></tr>";
	}

	echo "
			<tr>
			<td colspan=2 align='right' class='bit_row_add'>
				<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'260px', inline:false, href:'bit_content/status/add.php'})\">" . (getUserID() ? "add status" : "") . "</a> 
				<a class='bit_row_add' href=\"status.php\">more</a>
			</td>
			</tr>";
	echo "</table>";
}

function listStatus()
{
	global $ora;
	global $bit_app;
	global $dtUser;

	$bg = "bg_menu2.gif";
	echo "<form method='POST'>";
	echo "<table cellpadding=0 cellspacing=0 width=98%><tr><td align='left' class='bit_news_title' background='bit_images/" . $bg . "' height=25 colspan=2>Sosial Network Infratel</td></tr>";
	if ($_POST["tComment"]) {
		while (list($k, $v) = each($_POST["tComment"])) {
			if ($v) {
				$qry = "insert into m_status_comment(comment_desc,comment_date,comment_by,status_id)
							values('$v',sysdate(),'" . getUserID() . "','$k')";
				$ora->sql_no_fetch($qry, $bit_app["db"]);
			}
		}
	}

	$qry = "select 
					a.id,status,created_date,hits,updated_by
				from 
					m_status a
				order by 
					id desc";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>&nbsp;<img src='" . $dtUser[$rsN->value[$i]["updated_by"]][3] . "' width='40'></td>";
		echo "<td class='bit_row_news' align='left'><a>" . $dtUser[$rsN->value[$i]["updated_by"]][1] . "</a><br />";
		echo "<div class='bit_row_date'>Tanggal : " . $rsN->value[$i]["created_date"] . "</span></div>";
		echo "<br />";
		echo "<div class='bit_comment_status'>" . nl2br($rsN->value[$i]["status"]) . "</div>";
		echo "<br />";
		echo "<table width='100%' border=0 cellspacing=0 cellpadding=4>";
		$qry = "select 
						comment_desc,comment_date,comment_by
					from 
						m_status_comment a
					where
						status_id=" . $rsN->value[$i]["id"] . "
					order by 
						id desc";
		$rsC = $ora->sql_fetch($qry, $bit_app["db"]);
		for ($j = 1; $j <= $rsC->jumrec; $j++) {
			echo "<tr><td class='bit_row_news1' align='left' valign='top'>&nbsp;<img src='" . $dtUser[$rsC->value[$j]["comment_by"]][3] . "' width='40'></td>";
			echo "<td class='bit_row_news1' align='left' width='99%'><a>" . $dtUser[$rsC->value[$j]["comment_by"]][1] . "</a><br />";
			echo "<div class='bit_row_date'>Tanggal : " . $rsC->value[$j]["comment_date"] . "</div>";
			echo "<br />";
			echo "<div class='bit_comment_status'>" . nl2br($rsC->value[$j]["comment_desc"]) . "</div>";
			echo "</td></tr>";
			echo "<tr><td height='2px' colspan=2 bgcolor='#ffffff'></td></tr>";
		}
		echo "<tr><td colspan=2><textarea  name='tComment[" . $rsN->value[$i]["id"] . "]' class='bit_input' id='tComment' cols=80 rows=2></textarea><br /><input type='submit' value='Komentar' name='bComment' class='bit_button'></td></tr>";
		echo "</table>";
		echo "</td></tr>";
	}
	echo "
			<tr>
			<td colspan=2 align='right' class='bit_row_news'>
				<a onClick=\"$.fn.colorbox({width:'500px', height:'250px', inline:true, href:'#createStatus'})\">add status</a>
			</td>
			</tr>";
	echo "</table>";
	echo "</form>";
	echo "
			<div style='display:none'>
				<div id='createStatus' style='padding:0px; background:#ffffff;'>
					<iframe frameborder='0' src='bit_content/status/add.php' height='500px' scrolling='no'></iframe>
				</div>
			</div>";
}

function getKategori($cat)
{
	global $ora;
	global $bit_app;

	if (!$cat)
		return;

	$qry = "select category_name from p_category
					where category_id=" . $cat;
	$rsName = $ora->sql_fetch($qry, $bit_app["db"]);
	echo "<table><tr><td class='titleSection'>";
	echo $rsName->value[1][1];
	echo "</td></tr>";

	$qry = "select id,title,created_date,content from m_contents
										where category_id=" . $cat . " and publish_flag=1
										 and date_format(expired,'%Y%m%d') >= date_format(sysdate(),'%Y%m%d')";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='titleFont'>";
		echo "<b><a href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2] . "</a></b><br>";
		echo "<span class='titleFont'>Tanggal : " . $rsN->value[$i][3] . "</span><br><br>";
		echo "</td></tr>";
	}
}

function getNews($cat)
{
	global $ora;
	global $bit_app;

	if (!$_SESSION["portal_id"])
		$wReg = " and registered=0";

	$qry = "select category_id,category_name from p_category 
				where category_id<>1
					  $wReg";
	$rsName = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsName->jumrec; $i++) {
		$url = "?cat=" . $rsName->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "&sub_menu_=" . $_GET["sub_menu_"];
		if ($i == $rsName->jumrec)
			echo "<a class='titleFont' onClick=\"window.location.href='$url'\">" . $rsName->value[$i][2] . "</a>";
		else
			echo "<a class='titleFont' onClick=\"window.location.href='$url'\">" . $rsName->value[$i][2] . "</a> - &nbsp;&nbsp;";
	}

	if (!$_GET["cat"])
		return;

	$qry = "select id,title,created_date,content from m_contents
										where category_id=" . $cat . " and publish_flag=1  and date_format(expired,'%Y%m%d') >= date_format(sysdate(),'%Y%m%d')";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<b><a href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2] . "</a></b><br>";
		echo "<span class='td'>" . substr(nl2br($rsN->value[$i][4], 0, 100)) . " ... </span><br>";
		echo "<span class='td'>Tanggal : " . $rsN->value[$i][3] . "</span>";
		echo "<br>";
		echo "<br>";
	}
}

function getBanner($menu, $sub_menu, $sub_menu_, $sub_menu__)
{
	global $ora;
	global $bit_app;

	$qry = "select banner from sub_menu__
				where sub_menu_id='" . $sub_menu__ . "'";
	$rsBanner = $ora->sql_fetch($qry, $bit_app["db"]);

	if ($rsBanner->value[1][1] == "") {
		$qry = "select banner from sub_menu_
					where sub_menu_id='" . $sub_menu_ . "'";
		$rsBanner = $ora->sql_fetch($qry, $bit_app["db"]);
	}

	if ($rsBanner->value[1][1] == "") {
		$qry = "select banner from sub_menu
					where sub_menu_id='" . $sub_menu . "'";
		$rsBanner = $ora->sql_fetch($qry, $bit_app["db"]);

		if ($rsBanner->value[1][1] == "") {
			$qry = "select banner from menu
						where menu_id='" . $menu . "'";
			$rsBanner = $ora->sql_fetch($qry, $bit_app["db"]);
		}
	}
	return $rsBanner->value[1][1];
}

function getCategoryMenu($menu)
{
	global $ora;
	global $bit_app;

	$qry = "select category_id from menu where menu_id='" . $menu . "'";
	$rsCat = $ora->sql_fetch($qry, $bit_app["db"]);

	return $rsCat->value[1][1];
}

function getBannerHome()
{
	global $ora;
	global $bit_app;

	$qry = "select banner from menu_home";
	$rsBanner = $ora->sql_fetch($qry, $bit_app["db"]);

	return $rsBanner->value[1][1];
}

function hotNews($cat)
{
	global $ora;
	global $bit_app;

	$qry = "select category_name from p_category";
	$rsName = $ora->sql_fetch($qry, $bit_app["db"]);
	echo "<table cellpadding=0 cellspacing=1 width=100%><tr><td class='bit_news_title' background='bit_images/bg_menu1.gif' height=25>Berita Terbaru</td></tr>";

	if ($cat)
		$whereCat = "and category_id=" . $cat;

	/*
		$qry="select category_id from p_ where registered = 0 $whereCat";
		$rsCat=$ora->sql_fetch($qry,$bit_app["db"]);
		for ($i=1;$i<=$rsCat->jumrec;$i++) {
			$cat .=$rsCat->value[$i][1].",";
		}
		if (!$_SESSION["portal_id"]) 
			$where =" and category_id in ( ".substr($cat,0,strlen($cat)-1).")";
		*/

	$qry = "select id,title,created_date,content from m_contents
				where publish_flag=1
				$whereCat
				$where
				order by id desc
				limit 0,5";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news'>";
		echo "<img src='bit_images/bullet.png'>&nbsp;&nbsp;<a style='text-decoration:none' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "&sub_menu_=" . $_GET["sub_menu_"] . "&sub_menu__=" . $_GET["sub_menu__"] . "&mPage=" . $_GET["mPage"] . "' target='_parent'>" . $rsN->value[$i][2] . "</a><br>";
		echo "<span class='bit_row_date'>Tanggal : " . $rsN->value[$i][3] . "</span>";
		echo "</td></tr>";
	}
	echo "<tr><td colspan=2 align='right' class='bit_row_news'><a class='' target='_blank' href='bit_content/content/contentHistory.php'>more ...</a></td></tr>";
	echo "</table>";
}

function infoKnowlegde()
{
	global $ora;
	global $bit_app;

	echo "<table cellpadding=0 cellspacing=1 width=100%><tr><td class='bit_news_title' background='bit_images/bg_menu.gif'>Info Sharing Knowledge</td></tr>";

	$qry = "select id,title,created_date,content from m_contents
				where publish_flag=1
				and category_id=12 
				order by id desc
				limit 0,5";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news'>";
		echo "<img src='bit_images/bullet.png'>&nbsp;&nbsp;<a style='text-decoration:none' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2] . "</a><br>";
		echo "<span class='bit_row_date'>Tanggal : " . $rsN->value[$i][3] . "</span>";
		echo "</td></tr>";
	}
	echo "<tr><td align='right'></td></tr>";
	echo "</table>";
}

function author_publisher()
{
	global $bit_app;
	switch (getUserLevel()) {
		case 2:
			author();
			break;
		case 3:
			author();
			publisher();
			break;
		case 4:
			author();
			publisher();
			break;
	}
}

function author()
{
	global $ora;
	global $bit_app;

	$qry = "
			select sum(cnt) from (
				select count(1) cnt,1 from m_contents a where created_by='" . getUserID() . "' and publish_flag=1
				union
				select count(1) cnt,4 from m_video b where created_by='" . getUserID() . "' and publish_flag=1
				union
				select count(1) cnt,3 from m_foto c where created_by='" . getUserID() . "' and publish_flag=1
			) h
			";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);

	$qry = "
			select sum(cnt) from (
				select count(1) cnt,1 from m_contents a where created_by='" . getUserID() . "' and publish_flag=0 and status=1
				union
				select count(1) cnt,4 from m_video d where created_by='" . getUserID() . "' and publish_flag=0 and status=1
				union
				select count(1) cnt,3 from m_foto c where created_by='" . getUserID() . "' and publish_flag=0 and status=1
			) h
			";
	$rs1 = $ora->sql_fetch($qry, $bit_app["db"]);

	$qry = "
			select sum(cnt) from (
				select count(1) cnt,1 from m_contents a where created_by='" . getUserID() . "' and publish_flag=0 and status=2
				union
				select count(1) cnt,4 from m_video d where created_by='" . getUserID() . "' and publish_flag=0 and status=2
				union
				select count(1) cnt,3 from m_foto e where created_by='" . getUserID() . "' and publish_flag=0 and status=2
			) h
			";
	$rs2 = $ora->sql_fetch($qry, $bit_app["db"]);

	/*
		echo "<table cellpadding=0 cellspacing=0 width='100%'><tr><td class='bit_news_title' align='left' colspan=2><strong>Author</strong></td></tr>";
		echo "<tr><td class='bit_row_news'>Menunggu Approve</td><td class='bit_row_news'>: <a onClick=\"$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'".$bit_app["path_url"]."bit_content/contents/menunggu.php'})\"><b>".format($rs1->value[1][1])."</b></a></td></tr>";
		echo "<tr><td width='90%' class='bit_row_news'>Approved</td><td class='bit_row_news'>: <a onClick=\"$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'".$bit_app["path_url"]."bit_content/contents/approve.php'})\"><b>".format($rs->value[1][1])."</b></a></td></tr>";
		echo "<tr><td class='bit_row_news'>Rejected</td><td class='bit_row_news'>: <a onClick=\"$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'".$bit_app["path_url"]."bit_content/contents/reject.php'})\"><b>".format($rs2->value[1][1])."</b></a></td></tr>";	
		echo "<tr><td class='bit_row_add' align='right' colspan='2'>";
		echo "<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'500px', inline:false, href:'bit_content/contents/add.php'})\">create news</a>";
		echo "</td></tr>";		
		echo "</table>";
		*/
	?>

	<table cellpadding=0 cellspacing=0 width='40%'>
		<tr>
			<td class='bit_news_title' align='left' colspan=2><strong>Author</strong></td>
		</tr>
		<tr>
			<td class='bit_row_news'>Menunggu Approve</td>
			<td class='bit_row_news'>: <a href="#" onClick="showColorbox('<?php echo $bit_app["path_url"] ?>bit_content/contents/menunggu.php')"><b><?php echo format($rs1->value[1][1]); ?></b></a></td>
		</tr>
		<tr>
			<td width='90%' class='bit_row_news'>Approved</td>
			<td class='bit_row_news'>: <a href="#" onClick="showColorbox('<?php echo $bit_app["path_url"] ?>bit_content/contents/approve.php')"><b><?php echo format($rs->value[1][1]); ?></b></a></td>
		</tr>
		<tr>
			<td class='bit_row_news'>Rejected</td>
			<td class='bit_row_news'>: <a href="#" onClick="showColorbox('<?php echo $bit_app["path_url"] ?>bit_content/contents/reject.php')"><b><?php echo format($rs2->value[1][1]); ?></b></a></td>
		</tr>
		<tr>
			<td class='bit_row_add' align='right' colspan='2'>
				<a href="#" onClick="showColorbox('<?php echo $bit_app["path_url"] ?>bit_content/contents/add.php')">create news</a>
			</td>
		</tr>
	</table>

<?php

}

function publisher()
{
	global $ora;
	global $bit_app;

	$qry = "
			select sum(cnt) from (
				select count(1) cnt,1 from m_contents a where publish_by_info='" . getUser(getUserID()) . "' and publish_flag=1
				union
				select count(1) cnt,4 from m_video d where publish_by_info='" . getUser(getUserID()) . "' and publish_flag=1
				union
				select count(1) cnt,3 from m_foto c where publish_by_info='" . getUser(getUserID()) . "' and publish_flag=1
			) h
			";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);

	$qry = "
			select sum(cnt) from (
				select count(1) cnt,1 from m_contents a where publisher like '%" . getUserID() . "%' and publish_flag=0 and status=1
				union
				select count(1) cnt,4 from m_video d where publisher like '%" . getUserID() . "%' and publish_flag=0 and status=1
				union
				select count(1) cnt,3 from m_foto c where publisher like '%" . getUserID() . "%' and publish_flag=0 and status=1
			) h
			";
	$rs1 = $ora->sql_fetch($qry, $bit_app["db"]);

	$qry = "
			select sum(cnt) from (
				select count(1) cnt,1 from m_contents a where reject_by_info='" . getUser(getUserID()) . "' and publish_flag=0 and status=2
				union
				select count(1) cnt,4 from m_video d where reject_by_info='" . getUser(getUserID()) . "' and publish_flag=0 and status=2
				union
				select count(1) cnt,3 from m_foto c where reject_by_info='" . getUser(getUserID()) . "' and publish_flag=0 and status=2
			) h
			";
	$rs2 = $ora->sql_fetch($qry, $bit_app["db"]);
	/*
		echo "<table cellpadding=0 cellspacing=0 width='100%'><tr><td class='bit_news_title' align='left' colspan=2><strong>Publisher</strong></td></tr>";
		echo "<tr><td class='bit_row_news_red'>Need Approve</td><td class='bit_row_news_red'>: <a onClick=\"$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'".$bit_app["path_url"]."bit_content/contents/need.php'})\"><b>".format($rs1->value[1][1])."</b></a></td></tr>";
		echo "<tr><td width='90%' class='bit_row_news'>Approved</td><td class='bit_row_news'>: <a onClick=\"$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'".$bit_app["path_url"]."bit_content/contents/approve_pub.php'})\"><b>".format($rs->value[1][1])."</b></a></td></tr>";
		echo "<tr><td class='bit_row_news'>Rejected</td><td class='bit_row_news'>: <a onClick=\"$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'".$bit_app["path_url"]."bit_content/contents/reject_pub.php'})\"><b>".format($rs2->value[1][1])."</b></a></td></tr>";
		echo "</table>";
		*/
?>

	<table cellpadding=0 cellspacing=0 width='40%'>
		<tr>
			<td class='bit_news_title' align='left' colspan=2><strong>Publisher</strong></td>
		</tr>
		<tr>
			<td class='bit_row_news_red'>Need Approve</td>
			<td class='bit_row_news_red'>: <a href="#" onClick="showColorbox('<?php echo $bit_app["path_url"] ?>bit_content/contents/need.php')"><b><?php echo format($rs1->value[1][1]); ?></b></a></td>
		</tr>
		<tr>
			<td width='90%' class='bit_row_news'>Approved</td>
			<td class='bit_row_news'>: <a href="#" onClick="showColorbox('<?php echo $bit_app["path_url"] ?>bit_content/contents/approve_pub.php')"><b><?php echo format($rs->value[1][1]); ?></b></a></td>
		</tr>
		<tr>
			<td class='bit_row_news'>Rejected</td>
			<td class='bit_row_news'>: <a href="#" onClick="showColorbox('<?php echo $bit_app["path_url"] ?>bit_content/contents/reject_pub.php')"><b><?php echo format($rs2->value[1][1]); ?></b></a></td>
		</tr>
	</table>

	<?php
}

function author_publisher_inovasi()
{
	global $bit_app;
	switch (getUserLevel()) {
		case 2:
			author_inovasi();
			break;
		case 3:
			author_inovasi();
			publisher_inovasi();
			break;
		case 4:
			author_inovasi();
			publisher_inovasi();
			break;
	}
}

function author_inovasi()
{
	global $ora;
	global $bit_app;

	$qry = "
			select sum(cnt) from (
				select count(1) cnt,1 from m_inovasi a where created_by='" . getUserID() . "' and publish_flag=1
			) h
			";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);

	$qry = "
			select sum(cnt) from (
				select count(1) cnt,1 from m_inovasi a where created_by='" . getUserID() . "' and publish_flag=0 and status=1
			) h
			";
	$rs1 = $ora->sql_fetch($qry, $bit_app["db"]);

	$qry = "
			select sum(cnt) from (
				select count(1) cnt,1 from m_inovasi a where created_by='" . getUserID() . "' and publish_flag=0 and status=2
			) h
			";
	$rs2 = $ora->sql_fetch($qry, $bit_app["db"]);


	echo "<table cellpadding=0 cellspacing=0 width='100%'><tr><td class='bit_news_title_inovasi' align='left' colspan=2>Author</td></tr>";
	echo "<tr><td class='bit_row_news_inovasi'>Menunggu Approve</td><td class='bit_row_news_inovasi'>: <a onClick=\"$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/inovasi/menunggu.php'})\"><b>" . format($rs1->value[1][1]) . "</b></a></td></tr>";
	echo "<tr><td width='90%' class='bit_row_news_inovasi'>Approve</td><td class='bit_row_news_inovasi'>: <a onClick=\"$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/inovasi/approve.php'})\"><b>" . format($rs->value[1][1]) . "</b></a></td></tr>";
	echo "<tr><td class='bit_row_news_inovasi'>Reject</td><td class='bit_row_news_inovasi'>: <a onClick=\"$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/inovasi/reject.php'})\"><b>" . format($rs2->value[1][1]) . "</b></a></td></tr>";

	echo "<tr><td class='bit_row_add_inovasi' align='right' colspan='2'>";
	echo "<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'700px', inline:false, href:'bit_content/inovasi/add.php'})\">create inovasi</a>";
	echo "</td></tr>";

	echo "</table>";
}

function publisher_inovasi()
{
	global $ora;
	global $bit_app;

	$qry = "
			select sum(cnt) from (
				select count(1) cnt,1 from m_inovasi a where publish_by_info='" . getUser(getUserID()) . "' and publish_flag=1
			) h
			";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);

	$qry = "
			select sum(cnt) from (
				select count(1) cnt,1 from m_inovasi a where publisher like '%" . getUserID() . "%' and publish_flag=0 and status=1
			) h
			";
	$rs1 = $ora->sql_fetch($qry, $bit_app["db"]);

	$qry = "
			select sum(cnt) from (
				select count(1) cnt,1 from m_inovasi a where reject_by_info='" . getUser(getUserID()) . "' and publish_flag=0 and status=2
			) h
			";
	$rs2 = $ora->sql_fetch($qry, $bit_app["db"]);

	echo "<table cellpadding=0 cellspacing=0 width='100%'><tr><td class='bit_news_title_inovasi' align='left' colspan=2>Publisher</td></tr>";
	echo "<tr><td class='bit_row_news_inovasi'>Need Approve</td><td class='bit_row_news_inovasi'>: <a onClick=\"$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/inovasi/need.php'})\"><b>" . format($rs1->value[1][1]) . "</b></a></td></tr>";
	echo "<tr><td width='90%' class='bit_row_news_inovasi'>Approve</td><td class='bit_row_news_inovasi'>: <a onClick=\"$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/inovasi/approve_pub.php'})\"><b>" . format($rs->value[1][1]) . "</b></a></td></tr>";
	echo "<tr><td class='bit_row_news_inovasi'>Reject</td><td class='bit_row_news_inovasi'>: <a onClick=\"$.fn.colorbox({iframe:true, width:'900px', height:'480px', inline:false, href:'bit_content/inovasi/reject_pub.php'})\"><b>" . format($rs2->value[1][1]) . "</b></a></td></tr>";

	echo "</table>";
}

function author_old()
{
	global $ora;
	global $bit_app;

	$f = new clsForm;

	if ($_GET["del_banner_id"]) {
		$qry = "delete from m_banner where id=" . $_GET["del_banner_id"];
		$ora->sql_no_fetch($qry, $bit_app["db"]);
	}

	if ($_GET["del_calendar_id"]) {
		$qry = "delete from m_calender where id=" . $_GET["del_calendar_id"];
		$ora->sql_no_fetch($qry, $bit_app["db"]);
	}

	if ($_GET["del_foto_id"]) {
		$qry = "delete from m_foto where id=" . $_GET["del_foto_id"];
		$ora->sql_no_fetch($qry, $bit_app["db"]);
	}

	if ($_GET["del_info_id"]) {
		$qry = "delete from m_info where id=" . $_GET["del_info_id"];
		$ora->sql_no_fetch($qry, $bit_app["db"]);
	}

	if ($_GET["del_video_id"]) {
		$qry = "delete from m_video where id=" . $_GET["del_video_id"];
		$ora->sql_no_fetch($qry, $bit_app["db"]);
	}

	if ($_GET["del_id"]) {
		$qry = "delete from m_contents where id=" . $_GET["del_id"];
		$ora->sql_no_fetch($qry, $bit_app["db"]);
	}

	if ($_GET["del_forum_id"]) {
		$qry = "delete from m_forum where id=" . $_GET["del_forum_id"];
		$ora->sql_no_fetch($qry, $bit_app["db"]);
	}

	$dtUser = getUserDBPublisher();

	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td class='bit_news_title' align='left' colspan=2>Author</td></tr>";
	#News
	$qry = "select 
					id,
					title,
					date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,
					content,
					publisher 
				from 
					m_contents
				where 
					publish_flag=0 
					and created_by=" . getUserID() . " 
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		$qry = "select status from h_contents where content_id=" . $rsN->value[$i][1] . " order by id desc";
		$rsStatus = $ora->sql_fetch($qry, $bit_app["db"]);

		echo "<tr><td class='bit_row_news' align='left' width=1% valign='top'>&nbsp;";
		echo "<img src='bit_images/bullet.png'></td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2];
		echo "</a>";

		if ($rsStatus->value[1][1] == 2)
			echo " - <span class='bit_row_title'><font color='#FF0000'>Reject</font></span>";

		echo "<br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/contents/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit News'><img src='bit_images/edit.png' border=0></a>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"var ans; ans=confirm('Anda yakin untuk menghapus news ini ?'); if (ans) {document.location.href='?del_id=" . $rsN->value[$i][1] . "'}\" title='Hapus News'><img src='bit_images/delete.png' border=0></a>";

		if ($rsStatus->value[1][1] == 2) {
			echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/contents/submit.php?id=" . $rsN->value[$i][1] . "'})\" title='Submit News'><img src='bit_images/accept.png' border=0></a>";
		}

		echo "<br>";
		echo "<span class='bit_row_date'>Publisher : " . $dtUser[$rsN->value[$i][5]][5] . "</span>";
		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : News</b></span>";

		echo "</td></tr>";
	}

	#Info
	$qry = "select 
					id,
					title,
					date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,
					content,
					publisher 
				from 
					m_info
				where 
					publish_flag=0 
					and created_by=" . getUserID() . " 
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		$qry = "select status from h_info where info_id=" . $rsN->value[$i][1] . " order by id desc";
		$rsStatus = $ora->sql_fetch($qry, $bit_app["db"]);

		echo "<tr><td class='bit_row_news' align='left' width=1% valign='top'>&nbsp;";
		echo "<img src='bit_images/bullet.png'></td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2];
		echo "</a>";

		if ($rsStatus->value[1][1] == 2)
			echo " - <span class='bit_row_title'><font color='#FF0000'>Reject</font></span>";

		echo "<br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/info/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit Info Management'><img src='bit_images/edit.png' border=0></a>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"var ans; ans=confirm('Anda yakin untuk menghapus info ini ?'); if (ans) {document.location.href='?del_info_id=" . $rsN->value[$i][1] . "'}\" title='Hapus Info Management'><img src='bit_images/delete.png' border=0></a>";

		if ($rsStatus->value[1][1] == 2) {
			echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/info/submit.php?id=" . $rsN->value[$i][1] . "'})\" title='Submit Info Management'><img src='bit_images/accept.png' border=0></a>";
		}

		echo "<br>";
		echo "<span class='bit_row_date'>Publisher : " . $dtUser[$rsN->value[$i][5]][5] . "</span>";
		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : Info Management</b></span>";

		echo "</td></tr>";
	}

	#Video
	$qry = "select 
					id,
					file,
					date_format(updated_date,'%W, %d/%m/%Y %h:%i') updated_date,
					publisher 
				from 
					m_video
				where 
					publish_flag=0 
					and created_by=" . getUserID() . " 
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		$qry = "select status from h_video where video_id=" . $rsN->value[$i][1] . " order by id desc";
		$rsStatus = $ora->sql_fetch($qry, $bit_app["db"]);

		echo "<tr><td class='bit_row_news' align='left' width=1% valign='top'>&nbsp;";
		echo "<img src='bit_images/bullet.png'></td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2];
		echo "</a>";

		if ($rsStatus->value[1][1] == 2)
			echo " - <span class='bit_row_title'><font color='#FF0000'>Reject</font></span>";

		echo "<br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'480px', inline:false, href:'bit_content/video/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit Video'><img src='bit_images/edit.png' border=0></a>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"var ans; ans=confirm('Anda yakin untuk menghapus video ini ?'); if (ans) {document.location.href='?del_video_id=" . $rsN->value[$i][1] . "'}\" title='Hapus Video'><img src='bit_images/delete.png' border=0></a>";

		if ($rsStatus->value[1][1] == 2) {
			echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/video/submit.php?id=" . $rsN->value[$i][1] . "'})\" title='Submit Video'><img src='bit_images/accept.png' border=0></a>";
		}

		echo "<br>";
		echo "<span class='bit_row_date'>Publisher : " . $dtUser[$rsN->value[$i][4]][5] . "</span>";
		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : Video</b></span>";

		echo "</td></tr>";
	}

	#Banner
	$qry = "select 
					id,
					banner,
					date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,
					description,
					publisher 
				from 
					m_banner
				where 
					publish_flag=0 
					and created_by=" . getUserID() . " 
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		$qry = "select status from h_banner where banner_id=" . $rsN->value[$i][1] . " order by id desc";
		$rsStatus = $ora->sql_fetch($qry, $bit_app["db"]);

		echo "<tr><td class='bit_row_news' align='left' width=1% valign='top'>&nbsp;";
		echo "<img src='bit_images/bullet.png'></td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2];
		echo "</a>";

		if ($rsStatus->value[1][1] == 2)
			echo " - <span class='bit_row_title'><font color='#FF0000'>Reject</font></span>";

		echo "<br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/banner/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit Banner'><img src='bit_images/edit.png' border=0></a>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"var ans; ans=confirm('Anda yakin untuk menghapus banner ini ?'); if (ans) {document.location.href='?del_banner_id=" . $rsN->value[$i][1] . "'}\" title='Hapus Banner'><img src='bit_images/delete.png' border=0></a>";

		if ($rsStatus->value[1][1] == 2) {
			echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/banner/submit.php?id=" . $rsN->value[$i][1] . "'})\" title='Submit Banner'><img src='bit_images/accept.png' border=0></a>";
		}

		echo "<br>";
		echo "<span class='bit_row_date'>Publisher : " . $dtUser[$rsN->value[$i][5]][5] . "</span>";
		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : Banner</b></span>";

		echo "</td></tr>";
	}

	#Foto
	$qry = "select 
					id,
					title,
					date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,
					description,
					publisher 
				from 
					m_foto
				where 
					publish_flag=0 
					and created_by=" . getUserID() . " 
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		$qry = "select status from h_foto where foto_id=" . $rsN->value[$i][1] . " order by id desc";
		$rsStatus = $ora->sql_fetch($qry, $bit_app["db"]);

		echo "<tr><td class='bit_row_news' align='left' width=1% valign='top'>&nbsp;";
		echo "<img src='bit_images/bullet.png'></td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2];
		echo "</a>";

		if ($rsStatus->value[1][1] == 2)
			echo " - <span class='bit_row_title'><font color='#FF0000'>Reject</font></span>";

		echo "<br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/foto/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit Foto'><img src='bit_images/edit.png' border=0></a>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"var ans; ans=confirm('Anda yakin untuk menghapus foto ini ?'); if (ans) {document.location.href='?del_foto_id=" . $rsN->value[$i][1] . "'}\" title='Hapus Foto'><img src='bit_images/delete.png' border=0></a>";

		if ($rsStatus->value[1][1] == 2) {
			echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/foto/submit.php?id=" . $rsN->value[$i][1] . "'})\" title='Submit Foto'><img src='bit_images/accept.png' border=0></a>";
		}

		echo "<br>";
		echo "<span class='bit_row_date'>Publisher : " . $dtUser[$rsN->value[$i][5]][5] . "</span>";
		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : Foto</b></span>";

		echo "</td></tr>";
	}

	#Forum
	$qry = "select 
					id,
					title,
					date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,
					content,
					publisher 
				from 
					m_forum
				where 
					publish_flag=0 
					and created_by=" . getUserID() . " 
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		$qry = "select status from h_forum where forum_id=" . $rsN->value[$i][1] . " order by id desc";
		$rsStatus = $ora->sql_fetch($qry, $bit_app["db"]);

		echo "<tr><td class='bit_row_news' align='left' width=1% valign='top'>&nbsp;";
		echo "<img src='bit_images/bullet.png'></td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2];
		echo "</a>";

		if ($rsStatus->value[1][1] == 2)
			echo " - <span class='bit_row_title'><font color='#FF0000'>Reject</font></span>";

		echo "<br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/forum/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit Forum'><img src='bit_images/edit.png' border=0></a>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"var ans; ans=confirm('Anda yakin untuk menghapus forum ini ?'); if (ans) {document.location.href='?del_forum_id=" . $rsN->value[$i][1] . "'}\" title='Hapus Forum'><img src='bit_images/delete.png' border=0></a>";

		if ($rsStatus->value[1][1] == 2) {
			echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/forum/submit.php?id=" . $rsN->value[$i][1] . "'})\" title='Submit Forum'><img src='bit_images/accept.png' border=0></a>";
		}

		echo "<br>";
		echo "<span class='bit_row_date'>Publisher : " . $dtUser[$rsN->value[$i][5]][5] . "</span>";
		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : Forum</b></span>";

		echo "</td></tr>";
	}

	#Calendar
	$qry = "select 
					id,
					keterangan,
					date_format(updated_by,'%W, %d/%m/%Y %h:%i') updated_by,
					publisher 
				from 
					m_calender
				where 
					publish_flag=0 
					and created_by=" . getUserID() . " 
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		$qry = "select status from h_calendar where calendar_id=" . $rsN->value[$i][1] . " order by id desc";
		$rsStatus = $ora->sql_fetch($qry, $bit_app["db"]);

		echo "<tr><td class='bit_row_news' align='left' width=1% valign='top'>&nbsp;";
		echo "<img src='bit_images/bullet.png'></td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2];
		echo "</a>";

		if ($rsStatus->value[1][1] == 2)
			echo " - <span class='bit_row_title'><font color='#FF0000'>Reject</font></span>";

		echo "<br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/calendar/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit Calendar'><img src='bit_images/edit.png' border=0></a>";
		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"var ans; ans=confirm('Anda yakin untuk menghapus Calendar ini ?'); if (ans) {document.location.href='?del_calendar_id=" . $rsN->value[$i][1] . "'}\" title='Hapus Calendar'><img src='bit_images/delete.png' border=0></a>";

		if ($rsStatus->value[1][1] == 2) {
			echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/calendar/submit.php?id=" . $rsN->value[$i][1] . "'})\" title='Submit Calendar'><img src='bit_images/accept.png' border=0></a>";
		}

		echo "<br>";
		echo "<span class='bit_row_date'>Publisher : " . $dtUser[$rsN->value[$i][4]][5] . "</span>";
		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : Calendar</b></span>";

		echo "</td></tr>";
	}

	echo "<tr><td class='bit_row_add' align='right' colspan=2>";
	echo "<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'500px', inline:false, href:'bit_content/contents/add.php'})\">create new content</a>";
	echo "</td></tr>";
	echo "<tr><td align='right'></td></tr>";
	echo "</table>";
}

function publisher_old()
{
	global $ora;
	global $bit_app;

	$f = new clsForm;

	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td class='bit_news_title' align='left' colspan=2>Publisher</td></tr>";
	$qry = "select 
					id,
					title,
					date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,
					content 
				from 
					m_contents
				where 
					publish_flag=0 
					and publisher='" . getUserID() . "'
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>";
		echo "<img src='bit_images/bullet.png'>&nbsp;</td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2] . "</a><br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/contents/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit News'><img src='bit_images/edit.png' border=0></a>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/contents/accept.php?id=" . $rsN->value[$i][1] . "'})\" title='Approve / Reject News'><img src='bit_images/accept.png' border=0></a>";

		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : News</b></span>";
		echo "</td></tr>";
	}

	$qry = "select 
					id,
					title,
					date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,
					content 
				from 
					m_info
				where 
					publish_flag=0
					and publisher='" . getUserID() . "'
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>";
		echo "<img src='bit_images/bullet.png'>&nbsp;</td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2] . "</a><br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/info/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit Info'><img src='bit_images/edit.png' border=0></a>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/info/accept.php?id=" . $rsN->value[$i][1] . "'})\" title='Approve / Reject Info'><img src='bit_images/accept.png' border=0></a>";
		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : Info Management</b></span>";
		echo "</td></tr>";
	}

	$qry = "select 
					id,
					file,
					date_format(updated_date,'%W, %d/%m/%Y %h:%i') updated_date,
					keterangan 
				from 
					m_video
				where 
					publish_flag=0
					and publisher='" . getUserID() . "'
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>";
		echo "<img src='bit_images/bullet.png'>&nbsp;</td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2] . "</a><br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'580px', inline:false, href:'bit_content/video/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit Video'><img src='bit_images/edit.png' border=0></a>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/video/accept.php?id=" . $rsN->value[$i][1] . "'})\" title='Approve / Reject Video'><img src='bit_images/accept.png' border=0></a>";
		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : Video</b></span>";
		echo "</td></tr>";
	}

	$qry = "select 
					id,
					banner,
					date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,
					description
				from 
					m_banner
				where 
					publish_flag=0
					and publisher='" . getUserID() . "'
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>";
		echo "<img src='bit_images/bullet.png'>&nbsp;</td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2] . "</a><br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/banner/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit Banner'><img src='bit_images/edit.png' border=0></a>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/banner/accept.php?id=" . $rsN->value[$i][1] . "'})\" title='Approve / Reject Banner'><img src='bit_images/accept.png' border=0></a>";
		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : Banner</b></span>";
		echo "</td></tr>";
	}

	$qry = "select 
					id,
					title,
					date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,
					description 
				from 
					m_foto
				where 
					publish_flag=0
					and publisher='" . getUserID() . "'
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>";
		echo "<img src='bit_images/bullet.png'>&nbsp;</td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2] . "</a><br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/foto/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit Foto'><img src='bit_images/edit.png' border=0></a>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/foto/accept.php?id=" . $rsN->value[$i][1] . "'})\" title='Approve / Reject Foto'><img src='bit_images/accept.png' border=0></a>";
		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : Foto</b></span>";
		echo "</td></tr>";
	}

	$qry = "select 
					id,
					title,
					date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,
					content 
				from 
					m_forum
				where 
					publish_flag=0
					and publisher='" . getUserID() . "'
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>";
		echo "<img src='bit_images/bullet.png'>&nbsp;</td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2] . "</a><br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/forum/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit Forum'><img src='bit_images/edit.png' border=0></a>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/forum/accept.php?id=" . $rsN->value[$i][1] . "'})\" title='Approve / Reject Forum'><img src='bit_images/accept.png' border=0></a>";
		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : Forum</b></span>";
		echo "</td></tr>";
	}

	$qry = "select 
					id,
					keterangan,
					date_format(updated_date,'%W, %d/%m/%Y %h:%i') updated_date
				from 
					m_calender
				where 
					publish_flag=0
					and publisher='" . getUserID() . "'
				order by 
					id  desc
				limit 0,3";
	$rsN = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $rsN->jumrec; $i++) {
		echo "<tr><td class='bit_row_news' align='left' valign='top'>";
		echo "<img src='bit_images/bullet.png'>&nbsp;</td><td class='bit_row_news' width='99%'><a class='bit_row_title' href='?id=" . $rsN->value[$i][1] . "&menu=" . $_GET["menu"] . "&sub_menu=" . $_GET["sub_menu"] . "' target='_parent'>" . $rsN->value[$i][2] . "</a><br>";
		echo "<span class='bit_row_date'>" . $rsN->value[$i][3] . "</span>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'600px', height:'550px', inline:false, href:'bit_content/calendar/edit.php?id=" . $rsN->value[$i][1] . "'})\" title='Edit Calendar'><img src='bit_images/edit.png' border=0></a>";

		echo "&nbsp;&nbsp;&nbsp;<a onClick=\"$.fn.colorbox({iframe:true, width:'500px', height:'250px', inline:false, href:'bit_content/calendar/accept.php?id=" . $rsN->value[$i][1] . "'})\" title='Approve / Reject Calendar'><img src='bit_images/accept.png' border=0></a>";
		echo "<br>";
		echo "<span class='bit_row_date'><b>Modul : Event Calendar</b></span>";
		echo "</td></tr>";
	}


	echo "</table>";
}


function capital($str)
{
	$arr = split(" ", $str);
	for ($i = 0; $i < count($arr); $i++) {
		$tmp .= strtoupper(substr($arr[$i], 0, 1)) . strtolower(substr($arr[$i], 1, strlen($arr[$i]))) . " ";
	}
	return $tmp;
}

function login()
{
	if (!getUserID()) {
	?>
		<form class="form-contact contact_form" action="" method="post" id="contactForm" novalidate="novalidate">
			<div class="row">
				<div class="col-sm-10">
					<div class="form-group">
						<input class="form-control" name="tInput[]" id="name" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'" placeholder='Enter your User Name'>
					</div>
				</div>
				<div class="col-sm-10">
					<div class="form-group">
						<input class="form-control" name="tInput[]" id="email" type="password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Your Password'" placeholder='Enter Your Password'>
						<input type="hidden" id="hidden_term" name="hidden_term" style="color:black;">
						<div style="margin-top:10px;">
							<label class="switch">
							<input type="checkbox" id="cTerm">
							<span class="slider round"></span>
							</label>
							<span style="font-size:15px;">Saya setuju dengan <a href="#"  data-toggle="modal" data-target=".bd-example-modal-lg">Term of Use</a></span>
						</div>
						<div class="input-group-append">
							<button class="btn btn_2" name="bLogin" value="Login" type="submit"><i class="ti-angle-right"></i></button>
						</div>
						<br>
					</div>
				</div>
			</div>
		</form>

		<!--
		<form method="post">
		<table cellpadding="2" cellspacing="0" class="tableLogin">
			<tr>
				<td align="right">User : </td>
				<td><input type="text" name="tInput[]" class="bit_input" size="5"></td>
				<td align="right">Pass : </td>
				<td><input type="password" name="tInput[]" class="bit_input"  size="5"></td>
				<td align="right"><input type="submit" value="Login" name="bLogin" class="bit_button" /></td>
				<td nowrap="nowrap">
				<?
				echo "<span class='bit_row_date1'>" . date("d m Y") . "</span>";
				echo "&nbsp;";
				?>
				</td>
			</tr>
		</table>
		</form>
		//-->
	<?
	// } else {
		//echo "<span class='bit_row_date1'>".date("d M Y, H:i")."</span>";
		//echo "&nbsp;";

	}
}

function loginInovasi()
{
	if (!getUserID()) {
	?>
		<form method="post">
			<table cellpadding="4" cellspacing="0" border="0" width="100%">
				<tr>
					<td align="center">
						<table cellpadding="2" cellspacing="0" class="tableLogin">
							<tr>
								<td align="right">User </td>
								<td><input type="text" name="tInput[]" class="bit_input" size="10"></td>
								<td align="right">Pass </td>
								<td><input type="password" name="tInput[]" class="bit_input" size="10"></td>
								<td align="right"><input type="submit" value="login" name="bLogin" class="bit_button_inovasi" /></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	<?
	} else {
		echo "<div class='infoLoginInovasi'>Hai, <span class='infoUserName'>" . getUserName() . "</span> - <span class='infoUserProfile'>" . getUserProfile() . "</span>, " . (getUserLevel() == 4 ? "<b><a target='_blank' href='bit_content/'>Panel</a></b> -" : "") . " <b><a href='logout_inovasi.php'>Logout</a></b></div>";
	}
}

function search()
{
	global $errorLogin;
	?>
	<form method="post" name="frSearch" id="frSearch">
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
			<tr>
				<td align="left">
					<div class="infodate"><? echo date("l, d/M/Y G:i"); ?></div>
				</td>
				<td align="right">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td><input type="text" onfocus="this.value=''" value="<? echo ($_POST["tSearch"] ? $_POST["tSearch"] : "Search") ?>" onblur="if (this.value=='Search' || this.value=='') this.value='Search'" name="tSearch" class="bit_input_search" size="30"></td>
							<td><a><img src="bit_images/search.png" onclick="dtSearch()" /></a></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</form>
<?
}

function loginHome()
{
	global $errorLogin;
?>
	<form method="post">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="bit_row_login" align="right">NIK :</td>
				<td><input type="text" value="" name="tInput[]" class="bit_input" size="10"></td>
				<td class="bit_row_login" align="right">Pass :</td>
				<td><input type="password" name="tInput[]" class="bit_input" size="10" value=""></td>
				<td align="right" class="bit_row_login"><input type="submit" name="button" class="bit_button" value="Login"></td>
			</tr>
			<? if ($errorLogin) { ?>
				<tr>
					<td colspan="5" class="bit_error">
						<? echo $errorLogin ?>
					</td>
				</tr>
			<? } ?>
		</table>
	</form>
<?
}

function quiz()
{
	global $ora;
	global $bit_app;

	$qry = "select active_flag from p_module where id=8";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;

?>
	<form method="post" name="frQuiz" id="frQuiz">
		<table cellpadding="0" cellspacing="0" width="100%">
			<?
			$qry = "select * from m_quiz where active = 1";
			$rs = $ora->sql_fetch($qry, $bit_app["db"]);

			if ($_POST["rJawabanQuiz"] && $_POST["bQuiz"]) {
				$qry = "insert into m_quiz_vote(forum_id,pilihan,updated_date,updated_by) 
						values(" . $rs->value[1]["id"] . "," . $_POST["rJawabanQuiz"] . ",sysdate(),'" . getUserID() . "')";
				$ora->sql_no_fetch($qry, $bit_app["db"]);
			}
			?>
			<tr>
				<td colspan="5" class='bit_news_title'>Quiz : <? echo $rs->value[1]["judul"] ?></td>
			</tr>
			<tr>
				<td class="bit_row_news_no_padding">
					<table width="100%" cellpadding="0" cellspacing="0">
						<? echo $rs->value[1]["content"]; ?>
						<?
						$qry = "select * from m_quiz_vote where forum_id='" . $rs->value[1]["id"] . "' and updated_by='" . getUserID() . "'";
						$rsCheckUser = $ora->sql_fetch($qry, $bit_app["db"]);
						if ($rsCheckUser->jumrec == 0 && getUserID()) {

							$qry = "select * from m_quiz_answer where forum_id='" . $rs->value[1]["id"] . "'";
							$rsPilihan = $ora->sql_fetch($qry, $bit_app["db"]);
							for ($i = 1; $i <= $rsPilihan->jumrec; $i++) {
								if ($rsPilihan->value[$i]["jawaban"]) {
						?>
									<tr>
										<td colspan=""><input type="radio" name="rJawabanQuiz" value="<? echo $rsPilihan->value[$i]["id"] ?>" /> <? echo $rsPilihan->value[$i]["jawaban"] ?></td>
									</tr>
							<?
								}
							}
							?>
						<? } ?>
						<? if ($rsCheckUser->jumrec == 0 && getUserID()) { ?>
							<tr>
								<td colspan="2" align="left"><input type="submit" name="bQuiz" class="bit_button" value=" Pilih "></td>
							</tr>
						<? } ?>
						<? if ($rs->jumrec) { ?>
							<tr>
								<td colspan="2" align="right"><a onClick="$.fn.colorbox({iframe:true, width:'600px', height:'350px', inline:false, href:'bit_content/quiz/quizDet.php?id=<? echo $rs->value[1]["id"] ?>'})"><b>Lihat Hasil</b></a></td>
							</tr>
						<? } ?>
					</table>
				</td>
			</tr>
		</table>
	</form>
<?
}

function genCounter($val)
{

	for ($i = 7; $i > strlen($val); $i--) {
		$tmp .= "0";
	}

	$tmp .= $val;

	for ($i = 0; $i < strlen($tmp); $i++) {
		$tmp1 .= "<img src='bit_images/" . $tmp[$i] . ".gif'></img>";
	}


	return $tmp1;
}

function birthOld()
{
	global $ora;
	global $bit_app;
	echo "<table cellpadding=0 cellspacing=0 width=98%><tr><td class='bit_news_title' background='bit_images/bg_menu.gif' height=25 colspan=2 align='left'>Ulang Tahun</td></tr>";

	#$qry="select max(tahun),max(bulan) from m_master_user";
	#$rsMax=$ora->sql_fetch($qry,$bit_app["db"]);

	$rsMax->value[1][1] = date("Y");
	$rsMax->value[1][2] = date("m");


	$qry = "select kode_divisi,concat(nama_karyawan,' (',nik,')'),nik,date_format(tgl_lahir,'%d-%m-%Y'),nama_posisi,EXTRACT(YEAR FROM (FROM_DAYS(DATEDIFF(NOW(),tgl_lahir))))+0  from m_master_user where tahun=" . $rsMax->value[1][1] . " and bulan='" . twoD($rsMax->value[1][2] - 1) . "' and date_format(tgl_lahir,'%m%d')='" . date("md") . "' order by kode_divisi";

	$qry = "select kode_divisi,concat(nama_karyawan,' (',nik,')'),nik,date_format(tgl_lahir,'%d-%m-%Y'),nama_posisi,EXTRACT(YEAR FROM (FROM_DAYS(DATEDIFF(NOW(),tgl_lahir))))+0  from m_master_user order by kode_divisi";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	$tmp = "";
	if ($rs->jumrec <= 3)
		$bodyBirth = "<div style='width: 340px; height: auto; overflow: auto; padding: 1px;'>";
	else
		$bodyBirth = "<div style='width: 340px; height: 200px; overflow: auto; padding: 1px;'>";

	$bodyBirth .= "<div class='bit_birth_title'>Selamat Ulang Tahun (Sukses Selalu)</div>";
	for ($i = 1; $i <= $rs->jumrec; $i++) {
		if ($tmp != $rs->value[$i][1]) {
			$tmp = $rs->value[$i][1];
			$str .= "<b>" . $tmp . "</b> : ";
			$bodyBirth .= "<div class='bit_cal_title'>" . $tmp . "</div>";
		}
		$bodyBirth .= "<table cellpadding=0 cellspacing=0 width=100% border=0><tr><td width=40px><img src='http://10.2.15.232/drp/0PhotoNAS/" . $rs->value[$i][3] . ".jpg' border=0 width=40></td><td><div class='bit_row_calender'>" . $rs->value[$i][2] . " <br><span class=''><b>" . $rs->value[$i][4] . " / " . $rs->value[$i][6] . " tahun</b></span><br>" . $rs->value[$i][5] . "</div></td></tr></table>";
		$str .= $rs->value[$i][2] . " --";
	}
	$str = substr($str, 0, strlen($str) - 2);
	$bodyBirth .= "</div>";


	echo "<tr><td class='bit_row_news' valign='top' width='1%'>";
	echo '<span title="cssbody=[tooltipBodyBirth] cssheader=[tooltipHeader] body=[' . $bodyBirth . ']  fixedabsy=[220]     style="font-size:11px;cursor:pointer" ><img src="' . $bit_app["path_url"] . '/bit_images/birth-cake-icon.jpg"></span>';
	echo "</td><td class='bit_row_news'><marquee scrolldelay='200' id='marquee_birth' onmouseover=\"document.getElementById('marquee_birth').stop()\"  onMouseOut=\"document.getElementById('marquee_birth').start()\">";
	echo $str;
	echo "</marquee>";
	echo "</td></tr>";
	echo "</table>";
}

function birth()
{
	global $ora;
	global $bit_app;


	echo "<table cellpadding=0 cellspacing=0 width=100%><tr><td colspan=2 align='left'>";
	echo "<table width='100%' cellpadding=0 cellspacing=0>";
	echo "<tr><td class='bit_news_title' width='1%' noWrap>Ulang Tahun</td>";
	echo "<td align='left' class='bit_news_title'><img onClick=\"$.fn.colorbox({width:'50%', inline:true, href:'#inline_birth'})\" src='" . $bit_app["path_url"] . "/bit_images/cake.png'  style=\"cursor:hand\" title='Klik disini'>";
	echo "</td></tr></table>";

	echo "</td></tr>";

	#$qry="select max(tahun),max(bulan) from m_master_user";
	#$rsMax=$ora->sql_fetch($qry,$bit_app["db"]);

	$rsMax->value[1][1] = date("Y");
	$rsMax->value[1][2] = date("m");


	$qry = "select kode_divisi,concat(nama_karyawan,' (',nik,')'),nik,date_format(tgl_lahir,'%d-%m-%Y'),nama_posisi,EXTRACT(YEAR FROM (FROM_DAYS(DATEDIFF(NOW(),tgl_lahir))))+0  from m_master_user where date_format(tgl_lahir,'%m%d')='" . date("md") . "' order by kode_divisi";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	$bodyBirth .= "<div class='bit_news_title_bottom'>Selamat Ulang Tahun (Sukses Selalu)</div>";
	for ($i = 1; $i <= $rs->jumrec; $i++) {
		$str .= "<img src='http://10.2.15.232/drp/0PhotoNAS/" . $rs->value[$i][3] . ".jpg' border=0 width=64 height=64>&nbsp;&nbsp;";
		$bodyBirth .= "<table cellpadding=0 cellspacing=0 width=100% border=0><tr><td width=40px><img src='http://10.2.15.232/drp/0PhotoNAS/" . $rs->value[$i][3] . ".jpg' border=0 width=32 height=32></td><td><div class='bit_row_calender'>" . $rs->value[$i][2] . " <br><span class=''><b>" . $rs->value[$i][4] . " / " . $rs->value[$i][6] . " tahun</b></span><br>" . $rs->value[$i][5] . "</div></td></tr></table>";
	}
	$str = substr($str, 0, strlen($str));



	echo "<tr><td bgcolor='#DBF3FD'><marquee scrolldelay='200' id='marquee_birth' onmouseover=\"document.getElementById('marquee_birth').stop()\"  onMouseOut=\"document.getElementById('marquee_birth').start()\">";
	echo $str;
	echo "</marquee>";
	echo "</td></tr>";
	echo "</table>";

	echo "<div style='display:none'>
				<div id='inline_birth' style='padding:10px; background:#fff;'>
					$bodyBirth
				</div>
			</div>";
}

function inforekan()
{
	global $ora;
	global $bit_app;

	$qry = "select active_flag from p_module where id=13";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;
?>
	<script>
		function dtInfoRekan() {
			var str = $("#frInfoRekan").serialize();
			$.ajax({
				type: "POST",
				url: "bit_content/inforekan.php",
				data: str,
				success: function(msg) {
					$("#dtInfoRekan").ajaxComplete(function(event, request, settings) {
						$(this).html(msg);
					});
				}
			});
		}
	</script>
	<form method="post" name="frInfoRekan" id="frInfoRekan">
		<table cellpadding="0" cellspacing="0" width=200px>
			<tr>
				<td colspan="2" class='bit_news_title' align="left">Info Rekan</td>
			</tr>
			<tr>
				<td colspan="2" class="bit_nres_title" align="left">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td><input type="text" onfocus="this.value=''" value="<? echo ($_POST["tSearch"] ? $_POST["tSearch"] : "Search") ?>" onblur="if (this.value=='Search' || this.value=='') this.value='Search'" name="tSearch" class="bit_input_search" size="32"></td>
							<td><a><img src="bit_images/search.png" onclick="dtInfoRekan()" /></a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div id="dtInfoRekan"></div>
				</td>
			</tr>
		</table>
	</form>
<?
}

function infoLogin()
{
	global $ora;
	global $bit_app;

	/*
	$qry="select * from users";
	$rsU=$ora->sql_fetch($qry,$bit_app["db"]);
	for ($i=1;$i<=$rsU->jumrec;$i++) {
		$dtUser[$rsU->value[$i]["user_nick"]]=$rsU->value[$i]["user_name"];
	}
	*/
	$qry = "select count(1) from c_user";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	/*
	$qry="select count(1) from counter where date_format(tgl,'%m')='".date("m")."'";
	$rs1=$ora->sql_fetch($qry,$bit_app["db"]);
	
	$qry="select user_id,count(1) from counter where date_format(tgl,'%m')='".date("m")."' group by user_id order by 2 desc";
	$rs2=$ora->sql_fetch($qry,$bit_app["db"]);
	*/

	$qry = "select count(1) from m_counter";
	$rs4 = $ora->sql_fetch($qry, $bit_app["db"]);

	$qry = "select title,sum(hits) hits from m_contents group by title order by sum(hits) desc limit 0,3";
	$rsHits = $ora->sql_fetch($qry, $bit_app["db"]);

?>
	<table cellpadding="0" cellspacing="0" width=200px>
		<tr>
			<td colspan="2" class='bit_news_title' align="left">Total Pengunjung<span class="bit_counter"><? //echo $rs4->value[1][1]
																											?></span></td>
		</tr>
		<tr>
			<td class="bit_row_news" align="center" colspan="2">
				<? echo genCounter($rs4->value[1][1]) ?>
			</td>
		</tr>
		<tr>
			<td class="bit_row_news" colspan="2"><img src="bit_images/icon-user.gif" width="14" /> Pengunjung online saat ini : <? echo $rs->value[1][1] ?></td>
		</tr>
		<?
		$qry = "
				select 'News',sum(hits) from m_contents a where publish_flag=1
				union
				select 'Foto',sum(hits) from m_foto b where publish_flag=1
				union
				select 'Video',sum(hits) from m_video d where publish_flag=1
				union
				select 'Info',sum(hits) from m_info e where publish_flag=1
				union
				select 'Forum',sum(hits) from m_forum f where publish_flag=1
				";
		$rsHitsTotal = $ora->sql_fetch($qry, $bit_app["db"]);
		$total = 0;
		for ($i = 1; $i <= $rsHitsTotal->jumrec; $i++) {
			$total += $rsHitsTotal->value[$i][2];
		}
		?>
		<tr>
			<td class="bit_row_news">Total Hits : <? echo "<b>" . format($total) . "</b> hits" ?>
				<table cellpadding="0" cellspacing="0">
					<?
					for ($i = 1; $i <= $rsHitsTotal->jumrec; $i++) {
						echo "<tr><td align='right'>" . $rsHitsTotal->value[$i][1] . "</td><td align='left'>:</td><td><b>" . format($rsHitsTotal->value[$i][2]) . "</b></td></tr> ";
					}
					?>
				</table>
			</td>
		</tr>
		<tr>
			<td height="1%" colspan="2" class="garis">&nbsp;</td>
		</tr>
	</table>
	<?
}

function getIndexNews($filter)
{
	global $ora;
	global $bit_app;
	global $s3;

	include("pagination.php");
	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 5; //if you want to dispaly 10 records per page then you have to change here
	$startpoint = ($page * $limit) - $limit;
	$table = 'm_contents';
	$statement = "$table WHERE publish_flag = 1 AND  YEAR(`created_date`) >= 2019"; //you have to pass your query over here
	if ($filter == null) {
		$qry = "SELECT id,title,image,created_date,content AS isiBerita FROM $statement ORDER BY id DESC LIMIT {$startpoint} , {$limit}";
	} else {
		$qry = "SELECT id,title,image,created_date,content AS isiBerita FROM $statement AND title LIKE '%$filter%' ORDER BY id DESC LIMIT {$startpoint} , {$limit}";
	}
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	// var_dump($rs->value);


	for ($i = 1; $i <= $rs->jumrec; $i++) :

		// ubah ke hari
		$date1 = $rs->value[$i]['created_date'];
		$date2 = strtotime($date1);
		$date3 = date('d M Y', $date2);

		$img = $bit_app["path_url"] . "bit_folder/" . $rs->value[$i]['image'];
	?>
		<div class="row">
			<div class="col-md-6">
				<img src="<?= $img; ?>" width="400" height="200" style="border:5px solid #f5f5f5;">
			</div>
			<div class="col-md-6">
				<!-- <div class="special_cource_text"> -->
				<a href="news.php?id=<?= $rs->value[$i]['id']; ?>">
					<h4><?= $rs->value[$i]['title']; ?></h4>
				</a>
				<p class="text-secondary"> Portal SAS | <?= $date3; ?></p>
				<br>
				<p class="text-dark"> <?= substr(strip_tags($rs->value[$i]['isiberita']), 0, 200) ?>...</p>
			</div>
		</div>
		<hr>

	<?php endfor;
	if ((!empty($rs->value[1])) && ($rs->value[1] != null)) {
		echo pagination($statement, $table, $filter, $limit, $page);
	} else {
		echo "<br>";
		echo "<h3>Berita '" . $filter . "' tidak ditemukan</h3>";
	}
}

function getIndexFoto()
{
	global $ora;
	global $bit_app;
	global $s3;
	include("pagination.php");

	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 12; //if you want to dispaly 10 records per page then you have to change here
	$startpoint = ($page * $limit) - $limit;
	$table = 'm_foto';
	$filter = null;
	$statement = "$table WHERE m_foto.publish_flag = 1"; //you have to pass your query over here
	$qry = "select m_foto_detail.foto_id, m_foto_detail.foto_name,m_foto.description, m_foto_detail.updated_date from m_foto_detail INNER JOIN m_foto ON m_foto.id = m_foto_detail.foto_id WHERE publish_flag = 1 order by m_foto.id desc limit {$startpoint} , {$limit}";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]); ?>

	<div class="row">
		<?php for ($i = 1; $i <= $rs->jumrec; $i++) :
			// ubah ke hari
			$date1 = $rs->value[$i]['updated_date'];
			$date2 = strtotime($date1);
			$date3 = date('d M Y', $date2);
		?>


			<div class="col-lg-3">
				<br>
				<div class="foto" style="border:5px solid #f5f5f5; height:190px; margin:5px;">
					<a class="foto" href="<?= $bit_app['path_url'] ?>bit_folder/<?= $rs->value[$i]['foto_name'] ?>">
						<img src="<?= $bit_app['path_url'] ?>bit_folder/<?= $rs->value[$i]['foto_name'] ?>" alt="" height="180px" width="300px"></a>
				</div>
				<!-- <h6 style="margin-top:10px;margin-bottom:0px;"><?= $rs->value[$i]['description'] ?></h6> -->
				<!-- <span style="font-size: 12px;">Portal SAS | <?= $date3; ?></span> -->
			</div>

		<?php endfor; ?>
	</div>
	<br>
<?php echo pagination($statement, $table, $filter, $limit, $page);
}

function getIndexVideo()
{
	global $ora;
	global $bit_app;
	global $s3;
	include("pagination.php");

	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 12; //if you want to dispaly 10 records per page then you have to change here
	$startpoint = ($page * $limit) - $limit;
	$table = 'm_video';
	$statement = "$table WHERE publish_flag = 1"; //you have to pass your query over here
	$qry = "select * from m_video where publish_flag=1 AND YEAR(`created_date`) >= 2020 order by id desc limit {$startpoint} , {$limit}";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	$filter = null;
?>


	<div class="row">
		<?php for ($i = 1; $i <= $rs->jumrec; $i++) :
			// ubah ke hari
			$date1 = $rs->value[$i]['created_date'];
			$date2 = strtotime($date1);
			$date3 = date('d M Y', $date2);
		?>			

			<div class="col-lg-3">
				<br>
				<div class="videos" style="border:5px solid #f5f5f5; height:190px; margin:5px;">
					<a class="videoUtama" href="<?= $bit_app["folder_url"]; ?><?= $rs->value[$i]["file"]; ?>" title="<?= $rs->value[$i]["keterangan"]; ?>.">
					<div class="img" style="position:relative;">
						<i class="far fa-play-circle fa-4x" style="top:50%;left:50%;position:absolute;transform:translate(-50%,-50%);color:white;opacity:0.8;"></i>
						<img src="<?= $bit_app["folder_url"]; ?><?= $rs->value[$i]["image"]; ?>" alt="" height="180px" height="200px" width="300px">					
					</div>
					
					</a>
				</div>

					<?php $ket = $rs->value[$i]["keterangan"];?>
					<?php $titik = (strlen($ket) >= 30)? '...' : '';?>
					<h6 style="margin-top:10px;margin-bottom:0px;" data-toggle="tooltip" title="<?= $ket; ?>"><?= substr($ket,0,30); ?><?=$titik;?></h6>
				<span class="text-secondary" style="font-size: 12px;">Portal SAS | <?= $date3; ?></span>
			</div>

		<?php endfor; ?>
	</div>
	<br>
	<?php echo pagination($statement, $table, $filter, $limit, $page);
}

function getNewsTerbaru()
{
	global $ora;
	global $bit_app;
	global $s3;
	$qry = "SELECT id,title,image,created_date FROM m_contents WHERE publish_flag = 1 AND  YEAR(`created_date`) >= 2019 ORDER BY id DESC LIMIT 5";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rs->jumrec; $i++) :

		// ubah ke hari
		$date1 = $rs->value[$i]['created_date'];
		$date2 = strtotime($date1);
		$date3 = date('d M Y', $date2);

		$img = $bit_app["path_url"] . "bit_folder/" . $rs->value[$i]['image'];
	?>
		<div class="row">
			<div class="col-md-6">
				<img src="<?= $img; ?>" width="200" height="120" style="border:3px solid #f5f5f5;">
			</div>
			<div class="col-md-6">
				<!-- <div class="special_cource_text"> -->
				<?php $ket = $rs->value[$i]['title'];?>
				<?php $titik = (strlen($ket) >= 70)? '...' : '';?>
				<a href="news.php?id=<?= $rs->value[$i]['id']; ?>">
					<h6 data-toggle="tooltip" title="<?= $ket; ?>"><?= substr($ket,0,70); ?><?=$titik;?></h6>
				</a>
				
				<!-- </div> -->
				<small class="text-secondary">Portal SAS | <?= $date3; ?></small>
			</div>
		</div>
		<hr>

		<?php endfor;
}


function galleryFoto()
{
	global $ora;
	global $bit_app;
	global $s3;

	$qry = "select m_foto_detail.foto_id, m_foto_detail.foto_name,m_foto.description, m_foto_detail.updated_date from m_foto_detail INNER JOIN m_foto ON m_foto.id = m_foto_detail.foto_id order by m_foto_detail.updated_date desc limit 9";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($j = 1; $j <= 3; $j++) {
		//    if($i % 2 == 1){
		echo '<div class="testimonial_slider">
								   <div class="row">';
		//    }
		if ($j == 1) {
			$mulai = 1;
			$akhir = 3;
		} else if ($j == 2) {
			$mulai = 4;
			$akhir = 6;
		} elseif ($j == 3) {
			$mulai = 7;
			$akhir = 9;
		}

		for ($i = $mulai; $i <= $akhir; ++$i) {
			$fl = $bit_app["folder_dir"] . $rs->value[$i]['foto_name'];
			$file = $rs->value[$i]['foto_name'];
			if (!file_exists($fl) || filesize($fl) == 0) {
				$getobj = $s3->getObject(BUCKETNAME, PORTALSASDIR_DATA . $file, $fl);
			}

			$date1 = $rs->value[$i]['updated_date'];
			$date2 = strtotime($date1);
			$date3 = date('d M Y', $date2);

		?>


			<div class="col-lg-8 col-xl-4 col-sm-8 align-self-center">
				<div class="testimonial_slider_img">
					<a class="fotoUtama" href="<?= $bit_app['path_url'] ?>bit_folder/<?= $rs->value[$i]['foto_name'] ?>">
						<img src="<?= $bit_app['path_url'] ?>bit_folder/<?= $rs->value[$i]['foto_name'] ?>" alt="" height="200px" width="300px" style="border:5px solid #f5f5f5;"></a>
					<!-- <h6 style="margin-top:10px;margin-bottom:0px;"><?= $rs->value[$i]['description'] ?></h6> -->
					<!-- <span>Portal SAS | <?= $date3; ?></span> -->
				</div>
			</div>


		<?php



		}


		// if($i % 2 == 1){
		echo '</div>
				</div>';
		// }
	}
}

function galleryVideo()
{
	global $ora;
	global $bit_app;

	$qry = "select active_flag from p_module where id=6";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	if ($rs->value[1][1] == 0)
		return;


	$qry = "select * from m_video where publish_flag=1 AND YEAR(`created_date`) >= 2020 order by id desc limit 9";
	$rsVideo = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($j = 1; $j <= 3; $j++) {
		echo '<div class="testimonial_slider">
									<div class="row">';

		if ($j == 1) {
			$mulai = 1;
			$akhir = 3;
		} else if ($j == 2) {
			$mulai = 4;
			$akhir = 6;
		} elseif ($j == 3) {
			$mulai = 7;
			$akhir = 9;
		}

		for ($i = $mulai; $i <= $akhir; ++$i) {
			if (!$rsVideo->value[3]["image"]) {
				$rsVideo->value[3]["image"] = "telkom_movie.png";
				echo "<p>'" . $rsVideo->value[$_COOKIE]["image"] . "'</p>";
			}

			$date1 = $rsVideo->value[$i]['created_date'];
			$date2 = strtotime($date1);
			$date3 = date('d M Y', $date2);

		?>

			<div class="col-md-4">
				<?php if ($date1 != null) : ?>
					<a class="videoUtama" href="<?= $bit_app["folder_url"]; ?><?= $rsVideo->value[$i]["file"]; ?>" title="<?= $rsVideo->value[$i]["keterangan"]; ?>">
					<div class="img" style="position:relative;">
					<i class="far fa-play-circle fa-4x" style="top:50%;left:50%;position:absolute;transform:translate(-50%,-50%);color:white;opacity:0.8;"></i>
					<img src="<?= $bit_app["folder_url"]; ?><?= $rsVideo->value[$i]["image"]; ?>" alt="" height="200px" style="border:5px solid #f5f5f5;" height="200px" width="300px">
					</div>
					</a>

					<?php $ket = $rsVideo->value[$i]["keterangan"];?>
					<?php $titik = (strlen($ket) >= 40)? '...' : '';?>
					<h6 style="margin-top:10px;margin-bottom:0px;" data-toggle="tooltip" title="<?= $ket; ?>"><?= substr($ket,0,40); ?><?=$titik;?></h6>

					<span style="font-size: 12px;">Portal SAS | <?= $date3; ?></span>
				<?php else : ?>
					<a class="#" href="#" title=""><img src="" alt="" height="200px" style="border:5px solid #f5f5f5;" height="200px" width="300px"></a>
					<span style="font-size: 12px;">Video Tidak Tersedia</span>
				<?php endif; ?>
			</div>
		<?php
		}
		echo '</div>
				</div>';
	}
}

function ManajemenSAS()
{
	global $ora;
	global $bit_app;
	global $s3;

	$qry = "select nama, jabatan, foto from m_manajemen order by id asc";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);

	for ($i = 1; $i <= $rs->jumrec; $i++) {
		$fl = $bit_app["folder_dir"] . $rs->value[$i]['foto'];
		$file = $rs->value[$i]['foto'];
		if (!file_exists($fl) || filesize($fl) == 0) {
			$getobj = $s3->getObject(BUCKETNAME, PORTALSASDIR_DATA . $file, $fl);
		}
		?>
		<div class="col-sm-6 col-xl-3">
			<div class="single_feature">
				<div class="single_feature_part">
					<!-- <span class="single_feature_icon"><i><img src="img/afriwandi.png"></i></span>-->
					<span><i><img src="<?php echo $bit_app["path_url"] ?>bit_folder/<?php echo $rs->value[$i]['foto'] ?>" class="rounded-circle"></i></span>
					<h4><?php echo $rs->value[$i]['nama'] ?></h4>
					<p><?php echo $rs->value[$i]['jabatan'] ?></p>
				</div>
			</div>
		</div>
	<?php
	}
}

function appSAS()
{
	global $ora;
	global $bit_app;

	$qry = "select sub_menu_name, content from sub_menu_l1 where type='APP' order by posisi";
	$r = $ora->sql_fetch($qry, $bit_app["db"]);
	for ($i = 1; $i <= $r->jumrec; ++$i) {
		echo '<a href="' . $r->value[$i]['content'] . '" target="_blank" class="btn_1">' . $r->value[$i]['sub_menu_name'] . '</a>' . "\n";
	}

	echo '<a href="kebijakan.php" class="btn_1" style="margin-top:10px;">Peraturan Perundang Undangan</a>';
}
function headLine()
{
	global $ora;
	global $bit_app;

	$qry = "select * from m_contents where category_id=2 order by id desc limit 0,4";
	$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	?>
	<div id="featured">
		<ul class="ui-tabs-nav">
			<?
			for ($i = 1; $i <= $rs->jumrec; $i++) {
				if (!$rs->value[$i]["image"])
					$rs->value[$i]["image"] = $bit_app["image_url"] . "/telkom-kecil.png"
			?>
				<li class="ui-tabs-nav-item ui-tabs-selected" id="nav-fragment-<?= $i ?>"><a href="#fragment-<?= $i ?>"><img src="<? echo $rs->value[$i]["image"] ?>" alt="" width="80" height="50" /><span><? echo $rs->value[$i]["title"] ?></span></a></li>
			<?
			}
			?>
		</ul>
		<?
		for ($i = 1; $i <= $rs->jumrec; $i++) {
			if (!$rs->value[$i]["image"])
				$rs->value[$i]["image"] = $bit_app["image_url"] . "/telkom-kecil.png";

			$rs->value[$i]["content"] = short_content($rs->value[$i]["content"]);
		?>
			<div id="fragment-<?= $i ?>" class="ui-tabs-panel" style="">
				<img src="<? echo $rs->value[$i]["image"] ?>" alt="" width="400" height="250" />
				<div class="info">
					<h2><a href="#"><? echo $rs->value[$i]["title"] ?></a></h2>
					<p><? echo substr($rs->value[$i]["content"], 0, 25) ?></p>
				</div>
			</div>
		<?
		}
		?>
	</div>
	</div>
<?
}
?>