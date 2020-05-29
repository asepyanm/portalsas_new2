<?php
/**
* @package Mambo Open Source
* @copyright (C) 2005 - 2006 Mambo Foundation Inc.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* Mambo was originally developed by Miro (www.miro.com.au) in 2000. Miro assigned the copyright in Mambo to The Mambo Foundation in 2005 to ensure
* that Mambo remained free Open Source software owned and managed by the community.
* Mambo is Free Software
*/ 

/** ensure this file is being included by a parent file */

/**
* Full DHTML Admnistrator Menus
*/
class clsMenu {
	/**
	* Show the menu
	* @param string The current user type
	* Tag 1 : icon , Tag 2 : Name , Tag 3 : '' , Tag 4 : Target
	*/
	
	function show() {
		global $bit_app;
		$ora=new clsMysql;
		$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
		$qry="select akses from p_profile where profile_id in (select user_adm_profile_id from m_users where user_id='".getUserID()."')";
		$rs=$ora->sql_fetch($qry,$bit_app["db"]);
		
		$arrAkses = explode(",",$rs->value[1][1]);	
		for ($i=0;$i<count($arrAkses);$i++) {
			$aksesMenu[$arrAkses[$i]]=1;
		}
		$ora->logoff();
		?>
		<div id="myMenuID"></div>
		<script language="JavaScript" type="text/javascript">
		var myMenu =
		[
			<? if ($aksesMenu[1]) { ?>
			[null,'Home','../index.php','',null],
			<? } ?>
			
			<? if ($aksesMenu[2]) { ?>
			[null,'| News', null, null, '',
				<? if ($aksesMenu[21]) { ?>
				['<img src="mod/ThemeOffice/mainmenu.png" />','Kategori', '?form=content.catList', null, ''],
				<? } ?>
				
				<? if ($aksesMenu[22]) { ?>
				['<img src="mod/ThemeOffice/mainmenu.png" />','Daftar News', '?form=content.contentList', null, ''],
				<? } ?>
				//['<img src="mod/ThemeOffice/mainmenu.png" />','Banner', '?form=content.banList', null, ''],
			],
			<? } ?>
			
			<? if ($aksesMenu[3]) { ?>
			[null,'| Menu', null, null, '',
				['<img src="mod/ThemeOffice/mainmenu.png" />','Daftar Menu', '?form=content.menuList', null, ''],
				['<img src="mod/ThemeOffice/mainmenu.png" />','Daftar Menu Icon', '?form=content.menuiconList', null, ''],
			],
			<? } ?>
			
			<? //if ($aksesMenu[4]) { ?>
			[null,'| Flow', null, null, '',
				
				<? if ($aksesMenu[42]) { ?>
				['<img src="mod/ThemeOffice/document.png" />','News / Video / Foto', '?form=content.publisherNews', null, ''],
				<? } ?>
			
			],
			<? //} ?>
			
			<? if ($aksesMenu[6]) { ?>
			[null,'| Module', null, null, '',
				<? if (getUserLevel()==4) { ?>
				['<img src="mod/ThemeOffice/document.png" />','Daftar Module', '?form=content.moduleList', null, ''],
				<? } ?>
				
					
				<? if ($aksesMenu[66]) { ?>
				['<img src="mod/ThemeOffice/document.png" />','Movie', '?form=content.vidList', null, ''],
				<? } ?>
				
				<? if ($aksesMenu[69]) { ?>
				['<img src="mod/ThemeOffice/document.png" />','Running Text', '?form=content.pengumumanList', null, ''],
				<? } ?>
				
				<? if ($aksesMenu[62]) { ?>
				['<img src="mod/ThemeOffice/document.png" />','Foto', '?form=content.fotList', null, ''],
				<? } ?>
			
				<? if ($aksesMenu[65]) { ?>
				['<img src="mod/ThemeOffice/document.png" />','Running Text Go Green', '?form=content.gogreenList', null, ''],
				<? } ?>
				
				<? if ($aksesMenu[61]) { ?>
				['<img src="mod/ThemeOffice/document.png" />','Nomor Telepon Personil SAS', '?form=content.telp1', null, ''],
				<? } ?>
				
				<? if ($aksesMenu[64]) { ?>
				['<img src="mod/ThemeOffice/document.png" />','Nomor Telepon Pejabat Telkom', '?form=content.telp2', null, ''],
				<? } ?>
				
				<? if ($aksesMenu[63]) { ?>
				['<img src="mod/ThemeOffice/document.png" />','Nomor Telepon Penting', '?form=content.telp3', null, ''],
				<? } ?>
				
				
			],
			<? } ?>
			
			<? if ($aksesMenu[8]) { ?>
			[null,'| User','?form=content.userList','',null],
			<? } ?>
			
			<? if ($aksesMenu[9]) { ?>
			[null,'| Profile', null, null, '',
				['<img src="mod/ThemeOffice/document.png" />','User','?form=content.profileUserList',null,''],
				['<img src="mod/ThemeOffice/document.png" />','Admin','?form=content.profileList',null,''],
				['<img src="mod/ThemeOffice/document.png" />','Menu Icon','?form=content.profileIconList',null,''],
			],
			<? } ?>
		];
		cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
		</script>
	<?php
	}
}

clsMenu::show();

?>
