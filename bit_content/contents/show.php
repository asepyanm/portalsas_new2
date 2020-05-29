<?php
session_start();
include_once("../../bit_config.php");
$ora = new clsMysql;
$ora->logon($bit_app["user_db"], $bit_app["pass_db"]);

$id = $_GET["id"];

#Hits
$qry = "select hits from m_contents where id=" . $id;
$rsHits = $ora->sql_fetch($qry, $bit_app["db"]);

#Hits
$qry = "update m_contents set hits=" . ($rsHits->value[1][1] + 1) . " where id=" . $id;
$ora->sql_no_fetch($qry, $bit_app["db"]);

#View
$qry = "select id,title,date_format(created_date,'%W, %d/%m/%Y %h:%i') created_date,content,hits,image,tag,created_by from m_contents
			where publish_flag=1
			and id=" . $id;
$rsN = $ora->sql_fetch($qry, $bit_app["db"]);

if ($rsN->value[1]["image"])
	$imgsrc = "bit_folder/" . $rsN->value[1]["image"];
else
	$imgsrc = "bit_folder/telkom.jpg";

if ($rsN->value[1]["image"]) {
	//echo "<img class='headimage' src='".$imgsrc."' width='200px' />";
?>
	<div class="feature-img">
		<img class="img-fluid" src="<? echo $imgsrc; ?>" alt="" width="700" style="border:5px solid #f5f5f5;">
	</div>
<?
}
echo "<div class='blog_details'>";
echo "<div class='headdate'>" . $rsN->value[1]["content_date"] . "</div>";
echo "<h2><a href='news.php?id=" . $rsN->value[1]["id"] . "'>" . $rsN->value[1]["title"] . "</a></h2>";
echo "<div class='headcontent'>" . $rsN->value[1]["content"] . " </div>";

echo "<br /><br />";
echo "<div class='headcreated'>Created By : " . getUser($rsN->value[1]["created_by"]) . "  <span class='headhits'>  [ " . format($rsN->value[1]["hits"]) . " hits ]</span></div>";

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
				where publish_flag=1 and id<>" . $id . " $whereTag
				limit 0,5";
	$rsTerkait = $ora->sql_fetch($qry, $bit_app["db"]);

	if ($rsTerkait->jumrec >= 1)
		echo "<div class='headterkait'>Berita terkait : </div>";
	for ($i = 1; $i <= $rsTerkait->jumrec; $i++) {
		echo "<a href='news.php?id=" . $rsTerkait->value[$i]["id"] . "' target='_parent' class='headlistterkait'>" . $i . ". " . $rsTerkait->value[$i]["title"] . "</a> <span class='headhits'>  [ " . format($rsTerkait->value[$i]["hits"]) . " hits ]</span><br />";
	}
}

echo "</div>";

//echo "<div id='dtNewsRate'></div>";

$ora->logoff();
?>