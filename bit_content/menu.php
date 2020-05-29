<?
	include_once('config.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/standart.css">
<link rel="stylesheet" href="css/template.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />
<script language="JavaScript" src="mod/JSCookMenu.js" type="text/javascript"></script>
<script language="JavaScript" src="mod/ThemeOffice/theme.js" type="text/javascript"></script>
<script language="JavaScript" src="mod/mambojavascript.js" type="text/javascript"></script>


<!--
<SCRIPT language=JavaScript src="menu/milonic_src.js" type=text/javascript></SCRIPT>	
<script	language=JavaScript>
if(ns4)_d.write("<scr"+"ipt language=JavaScript src=menu/mmenuns4.js><\/scr"+"ipt>");		
  else _d.write("<scr"+"ipt language=JavaScript src=menu/mmenudom.js><\/scr"+"ipt>"); 
</script>
-->

</head>
<body leftmargin="0" rightmargin="0" topmargin="0">
<?
	$eF=getForm($_GET["form"]);
?>
<center>
<?
	#include_once("menu/menu_data.php");
?>
<table width="800" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td colspan="2" align="left"><? include('mod/mod_menu.php') ?></td>
	</tr>
</table>	
</center>
	
</body>
</html>
