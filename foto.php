<?php
	session_start();
	include_once("bit_config.php");
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
	if (!$_GET["id"]) {
		$qry="select max(id) from m_foto where publish_flag=1";
		$rsID=$ora->sql_fetch($qry,$bit_app["db"]);
		$_GET["id"]=$rsID->value[1][1];
	}
	
	$qry="select min(id) from m_foto_detail where foto_id=".$_GET["id"];
	$rsIDChild=$ora->sql_fetch($qry,$bit_app["db"]);
	$_GET["idChild"]=$rsIDChild->value[1][1];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><? echo $bit_app["title"]?></title>
<link rel="stylesheet" href="<? echo $bit_app["css"]?>/css.css" type="text/css" >
<script type="text/javascript" src="bit_mod/mod_js.js"></script>
<link rel="shortcut icon" href="bit_images/favicon.ico" type="image/x-icon" />

<!-- Third -->	
<script type="text/javascript" src="bit_third/flowplayer web/anchors_files/flowplayer-3.js"></script>

<link type="text/css" media="screen" rel="stylesheet" href="bit_third/colorbox/colorbox/example1/colorbox.css" />
<script type="text/javascript" src="bit_third/colorbox/colorbox/example1/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="bit_third/colorbox/colorbox/colorbox/jquery.colorbox.js"></script>

<script type="text/javascript" src="bit_third/menu/milonic_src.js"></script>	
<script type="text/javascript" src="bit_third/menu/mmenudom.js"></script>


<style>
	html {
		height:100%;
	}
	body {
		height:100%;
		margin:0;
		font-family:Geneva, Arial, Helvetica, sans-serif;
		font-size:12px;
		background-color:#0C2937;
	}
	.bit_table {
		background-color:#FFFFFF;;
		height:100%;
		margin-left: auto;
  		margin-right: auto;
		border:3px solid #0B618B;
		vertical-align:middle;
		vertical-align:middle;
	}
	
	#marq {
		font-family:Geneva, Arial, Helvetica, sans-serif;
		color:#FFFFFF;
	}
	
	object {
		display:block;
		margin:0px;
	}
	
	.icon_title {
		color:#F9F4E6;
		font-weight:bold;
		font-size:12px;
		vertical-align:middle;
		cursor:pointer;
	}
</style>

<link rel="stylesheet" type="text/css" href="bit_third/superfish-1.4.8/css/superfish.css" media="screen">
<script type="text/javascript" src="bit_third/superfish-1.4.8/js/hoverIntent.js"></script>
<script type="text/javascript" src="bit_third/superfish-1.4.8/js/superfish.js"></script>
<script type="text/javascript">

// initialise plugins
jQuery(function(){
	jQuery('ul.sf-menu').superfish();
});

</script>
<link rel="stylesheet" type="text/css" href="bit_third/demo_3/css/screen.css" media="all" />
<script type="text/javascript" src="bit_third/demo_3/scripts/jquery-ui-1.7.1.custom.min.js"></script>

		
<script type="text/javascript" src="bit_third/jquery.qtip-1.0.0-rc3.custom/jquery-qtip-1.0.0-rc3082617/jquery.qtip-1.0.0-rc3.min.js"></script>

<script type="text/javascript">
// Create the tooltips only on document load
/*
$(document).ready(function() 
{
 // Notice the use of the each() method to acquire access to each elements attributes
   $('#content li[tooltip]').each(function()
   {
      $(this).qtip({
         position: {
			  corner: {
				 target: 'topLeft',
			 	 tooltip: 'bottomLeft'
			  }
		   },
		content: $(this).attr('tooltip'), // Use the tooltip attribute of the element for the content
         style: 'dark' // Give it a crea mstyle to make it stand out
      });
   });
});
*/
</script>

<script type="text/javascript" src="bit_third/flowplayer/example/flowplayer-3.1.4.min.js"></script>

<script type="text/javascript" src="bit_third/jcarousel/lib/jquery.jcarousel.pack.js"></script>

<link rel="stylesheet" type="text/css" href="bit_third/jcarousel/lib/jquery.jcarousel.css" />
<link rel="stylesheet" type="text/css" href="bit_third/jcarousel/skins/tango/skin.css" />
<link rel="stylesheet" type="text/css" href="css.css" media="all" />


<script type="text/javascript">


function dtSearch() {
	var str = $("#frSearch").serialize();
	$.ajax({
	type: "POST",
	url: "bit_content/foto/list.php",
	data: str,
	success: function(msg){
			$("#dtList").html(msg);
	  }
	});
}


function dtComment(id,idChild) {
	if (idChild == undefined)
		idChild=0;
		
	var str = $("#frContent").serialize();
	$.ajax({
	type: "POST",
	url: "bit_content/foto/comment.php?id="+id+"&idChild="+idChild,
	data: str,
	success: function(msg){
			$("#dtComment").html(msg);
	  }
	});
}

function dtRate(id) {
	var str = $("#frRate").serialize();
	$.ajax({
	type: "POST",
	url: "bit_content/foto/rate.php?id="+id,
	data: str,
	success: function(msg){
			$("#dtRate").html(msg);
	  }
	});
}

function dtList() {
	var str = $("#frList").serialize();
	$.ajax({
	type: "POST",
	url: "bit_content/foto/list.php",
	data: str,
	success: function(msg){
			$("#dtList").html(msg);
	  }
	});
}

function dtShow(id) {
	var str = $("#frShow").serialize();
	$.ajax({
	type: "POST",
	url: "bit_content/foto/show.php?id="+id,
	data: str,
	success: function(msg){
			$("#dtShow").html(msg);
	  }
	});
}

function dtShowFoto(id,foto) {
	var str = $("#frShow").serialize();
	$.ajax({
	type: "POST",
	url: "bit_content/foto/show.php?id="+id+"&foto="+foto,
	data: str,
	success: function(msg){
			$("#dtShow").html(msg);
	  }
	});
}

function init() {
	dtShow(<?php echo format($_GET["id"])?>);
	dtComment(<?php echo format($_GET["id"])?>,<?php echo format($_GET["idChild"])?>);
	dtRate(<?php echo format($_GET["id"])?>);
	dtList();
}

</script>
<link rel="shortcut icon" href="bit_images/favicon.ico" type="image/x-icon" />
<!-- End -->
</head>
<?php
	checklogin();
	$dtUser=getUserDB();
	$id=$_GET["id"];
	
	include_once("bit_third/menu/menu_data.php");
?>
<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" onload="init()">
<!-- Licence -->
<a href=http://www.milonic.com/beginner.php><font color="#D1DDE3"></font></a>
<table class="bit_table" cellpadding="0" cellspacing="0" border="0" width="900">
	<tr>
		<td valign="top" height="80px" bgcolor="#CBF0AA">
		   <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="900" height="80">
            <param name="movie" value="bit_psd/banner.swf" />
            <param name="quality" value="high" />
            <param name="wmode" value="transparent">
			<embed src="bit_psd/banner.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="900" height="80"></embed>
	      </object>
	  </td>
	</tr>
	<tr>
		<td valign="middle" height="25px" background="bit_psd/banner_bottom.png">
		  <div id="marq"><?php runningText() ?></div>
		</td>
	</tr>
	<tr>
		<td valign="top" align="left" bgcolor="#F2F9F0" height="33">
		</td>
	</tr>
	<tr>
		<td colspan="2" height="0" align="center" valign="top" background="bit_images/green.jpg">
		<table cellpadding="10">
		<tr>
			<? menu_icon()	?>
		</tr>
		</table>
	</tr>
	<tr>
		<td height="100%" valign="top" align="center" background="bit_images/green.jpg">
			<? if ($_GET["file"]) { ?>
				<iframe width="100%" height="100%" src="<? echo "bit_folder/".$_GET["file"] ?>"></iframe>
			<? } elseif ($_GET["url"]) { ?>
				<iframe width="100%" height="100%" src="<? echo $_GET["url"] ?>"></iframe>
			<? } else { ?>
			<table>
				<tr>
					<td align="center"  valign="top" width="550px" bgcolor="#1F6C92">
						<div id="dtSearch"></div>
						<div id="dtShow"></div>
						<div id="dtRate"></div>
						<? echo "<br />"; ?>
						<div id="dtComment"></div>
					</td>
					<td></td>
					<td align="left" valign="top" width="300px">
						<div id="dtList"></div>
					</td>
				</tr>	
			</table>
			<? } ?>
		</td>
	</tr>
</table>

</body>
</html>
<?
	$ora->logoff();
?>
