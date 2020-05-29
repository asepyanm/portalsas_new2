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
		global $db;
		
		$ora=new clsMysql;
		$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	
		$qry="select * from menu";
		$rsMenu=$ora->sql_fetch($qry,$bit_app["db"]);
		?>
		<div id="myMenuID"></div>
		<script language="JavaScript" type="text/javascript">
		var myMenu =
		[
			<?
				for ($i=1;$i<=$rsMenu->jumrec;$i++) {
					
					$qry="select * from sub_menu where menu_id=".$rsMenu->value[$i]["menu_id"];
					$rsSubMenu=$ora->sql_fetch($qry,$bit_app["db"]);
					if ($rsSubMenu->jumrec==0) 
						echo "	[null,'| ".$rsMenu->value[$i]["menu_name"]."','?form=content.userList','',null],";
					else {
						echo "	[null,'| ".$rsMenu->value[$i]["menu_name"]."','?form=content.userList','',null,";
						for ($j=1;$j<=$rsSubMenu->jumrec;$j++) {
						
							$qry="select * from sub_menu_ where menu_id=".$rsSubMenu->value[$j]["sub_menu_id"];
							$rsSubMenu_=$ora->sql_fetch($qry,$bit_app["db"]);
							
							if ($rsSubMenu_->jumrec==0) 
								echo "['','".$rsSubMenu->value[$j]["sub_menu_name"]."', '?form=content.banList', null, ''],";
							else {
								echo "	['','".$rsSubMenu->value[$j]["sub_menu_name"]."','?form=content.userList','',null,";
								for ($k=1;$k<=$rsSubMenu_->jumrec;$k++) {
									echo "['','".$rsSubMenu_->value[$k]["sub_menu_name"]."', '?form=content.banList', null, ''],";
								}	
								echo "],";
							}
					
						}
						echo "],";
					}
				}
			?>
		];
		cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
		</script>
	<?php
	}
}

clsMenu::show();

?>
