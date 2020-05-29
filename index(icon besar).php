<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>:: PORTAL SAS ::</title>
<style>
	html {
		height:100%;
	}
	body {
		height:100%;
		margin:0;
		background-image:url(bit_images/greenBubbleTile.png);
		font-family:Geneva, Arial, Helvetica, sans-serif;
		font-size:12px;
	}
	.bit_table {
		background-color:#FFFFFF;;
		height:100%;
		margin-left: auto;
  		margin-right: auto;
		border:3px solid #80CC92;
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
</style>

<link rel="stylesheet" type="text/css" href="bit_third/superfish-1.4.8/css/superfish.css" media="screen">
<script type="text/javascript" src="bit_third/superfish-1.4.8/js/jquery-1.2.6.min.js"></script>
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
<script type="text/javascript" src="bit_third/demo_3/scripts/execute.js"></script>
		
<script type="text/javascript" src="bit_third/jquery.qtip-1.0.0-rc3.custom/jquery-qtip-1.0.0-rc3082617/jquery.qtip-1.0.0-rc3.min.js"></script>

<script type="text/javascript">
// Create the tooltips only on document load
$(document).ready(function() 
{
 // Notice the use of the each() method to acquire access to each elements attributes
   $('#content a[tooltip]').each(function()
   {
      $(this).qtip({
         position: {
			  corner: {
				 target: 'center',
			 	 tooltip: 'bottomLeft'
			  }
		   },
		content: $(this).attr('tooltip'), // Use the tooltip attribute of the element for the content
         style: 'dark' // Give it a crea mstyle to make it stand out
      });
   });
});
</script>

</head>
<body>

<table class="bit_table" cellpadding="0" cellspacing="0" border="0" width="900">
	<tr>
		<td valign="top" height="95px" bgcolor="#CBF0AA">
		  <object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=8,5,0,0" width="900" height="95px">
          <param name="src" value="bit_psd/banner.swf" />
          <param name='wmode' value='transparent'></param>
		  <embed src="bit_psd/banner.swf" pluginspage="http://www.macromedia.com/shockwave/download/" width="900" height="95"></embed>
		  </object>		
		</td>
	</tr>
	<tr>
		<td valign="middle" height="25px" background="bit_psd/banner_bottom.png">
		  <div id="marq"><marquee width="100%" scrolldelay='200' id='marquee_birth' onmouseover="document.getElementById('marquee_birth').stop()"  onMouseOut="document.getElementById('marquee_birth').start()">		Welcome to PORTAL SAS (Security And Safety)</marquee>
		  </div>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#F2F9F0" height="10">
		<ul class="sf-menu">
			<li class="current">
				<a href="#a">Aplikasi</a>
				<ul>
					<li>
						<a href="#aa"><img src="bit_images/Stop.png" width="20" align="absmiddle" border="0" />&nbsp;Vandalisme</a>
					</li>
					<li class="current">
						<a href="#ab"><img src="bit_images/warning.png" width="20" align="absmiddle" border="0" />&nbsp;Early Warning On weB</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/security.png" width="20" align="absmiddle" border="0" />&nbsp;Kesemaptaan</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/phone-icon.png" width="20" align="absmiddle" border="0" />&nbsp;Laporan POSKO</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/cctv.png" width="20" align="absmiddle" border="0" />&nbsp;CCTV</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/icon-list.png" width="20" align="absmiddle" border="0" />&nbsp;SMK3</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/icon-list.png" width="20" align="absmiddle" border="0" />&nbsp;SROT</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/list.png" width="20" align="absmiddle" border="0" />&nbsp;Checklist</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/go-green.png" width="20" align="absmiddle" border="0" />&nbsp;Telkom Go Green </a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#">Berita</a>
				<ul>
					<li>
						<a href="#"><img src="bit_images/news.png" width="20" align="absmiddle" border="0" />&nbsp;News</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/text-icon.png" width="20" align="absmiddle" border="0" />&nbsp;Running Text</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/info.png" width="20" align="absmiddle" border="0" />&nbsp;Info Dir CRM</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/archive.png" width="20" align="absmiddle" border="0" />&nbsp;Arsip Berita (dikelompokkan per bulan)</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#">Informasi</a>
				<ul>
					<li>
						<a href="#"><img src="bit_images/foto.png" width="20" align="absmiddle" border="0" />&nbsp;Photo Gallery</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/video.png" width="20" align="absmiddle" border="0" />&nbsp;Video(s)</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/database.png" width="20" align="absmiddle" border="0" />&nbsp;Database Personil Security Nasional</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/phone.png" width="20" align="absmiddle" border="0" />&nbsp;Informasi Telepon Penting</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/phone.png" width="20" align="absmiddle" border="0" />&nbsp;Informasi Telepon Dinas POS Security Nasional</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/call.png" width="20" align="absmiddle" border="0" />&nbsp;Informasi Data Call Sign RIG - HT</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/peta.png" width="20" align="absmiddle" border="0" />&nbsp;Peta Area Daerah Operasi</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#">Kebijakan / Policy</a>
				<ul>
					<li>
						<a href="#"><img src="bit_images/policy.png" width="20" align="absmiddle" border="0" />&nbsp;Kebijakan SAS terbaru</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/guard.png" width="20" align="absmiddle" border="0" />&nbsp;Mars SAS</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/uniform.png" width="20" align="absmiddle" border="0" />&nbsp;Pakaian Dinas</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#">Link</a>
				<ul>
					<li>
						<a href="#"><img src="bit_images/link.png" width="20" align="absmiddle" border="0" />&nbsp;BMKG</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/link.png" width="20" align="absmiddle" border="0" />&nbsp;TQMS</a>
					</li>
					<li>
						<a href="#"><img src="bit_images/link.png" width="20" align="absmiddle" border="0" />&nbsp;Milis Sekar</a>
					</li>
				</ul>
			</li>
		</ul>
		</td>
	</tr>
	<tr>
		<td height="100%" valign="top" background="bit_images/green.jpg">
		<div id="content">
			<ul id="nav-shadow">
				<li class="button-color-1"><a href="#" tooltip="Vandalisme"></a></li>
				<li class="button-color-2"><a href="#" tooltip="Early Warning On weB"></a></li>
				<li class="button-color-3"><a href="#" tooltip="Kesemaptaan"></a></li>
				<li class="button-color-4"><a href="#" tooltip="Laporan POSKO"></a></li>
				<li class="button-color-5"><a href="#" tooltip="CCTV"></a></li>
				<li class="button-color-6"><a href="#" tooltip="SMK3"></a></li>
				<li class="button-color-7"><a href="#" tooltip="SROT"></a></li>
				<li class="button-color-8"><a href="#" tooltip="Checklist"></a></li>
				<li class="button-color-9"><a href="#" tooltip="Telkom Go Green"></a></li>
			</ul>
		</div>
	</tr>
</table>
</body>
</html>
