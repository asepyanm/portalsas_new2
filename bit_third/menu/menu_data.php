<?php
// Parent Menu
$qry = "select user_profile_id from m_users where user_id='" . getUserID() . "'";
$rs = $ora->sql_fetch($qry, $bit_app["db"]);

if ($rs->value[1][1]) {
	$qry = "select akses from p_profile_user where profile_id='" . $rs->value[1][1] . "'";
	$rsProfile = $ora->sql_fetch($qry, $bit_app["db"]);
	$aksesMenu = preg_split("/\|/", $rsProfile->value[1][1]);
} else {
	$qry = "select akses from p_profile_user where profile_name='Default'";
	$rsProfile = $ora->sql_fetch($qry, $bit_app["db"]);
	$aksesMenu = preg_split("/\|/", $rsProfile->value[1][1]);
}

if ($aksesMenu[0] == "")
	$aksesMenu[0] = 0;

if ($aksesMenu[1] == "")
	$aksesMenu[1] = 0;

if ($aksesMenu[2] == "")
	$aksesMenu[2] = 0;

if ($aksesMenu[3] == "")
	$aksesMenu[3] = 0;

$qry = "select * from menu where menu_id in (" . $aksesMenu[0] . ") $where order by posisi";
$rsMenu = $ora->sql_fetch($qry, $bit_app["db"]);

for ($i = 1; $i <= $rsMenu->jumrec; $i++) {
	$child1 = getChild($rsMenu->value[$i]['menu_id'], 1);

	switch ($rsMenu->value[$i]["tipe_content"]) {
		case 1:
			switch ($rsMenu->value[$i]["target"]) {
				case "_blank":
					$addP = $rsMenu->value[$i]["content"];
					$targetMenu = ";target=_blank";
					break;
				case "_self":
					$addP = $rsMenu->value[$i]["content"];
					$targetMenu = ";target=_self";
					break;
				default:
					$addP = "?url=" . $rsMenu->value[$i]["content"] . "&menu=" . $rsMenu->value[$i]["menu_id"];
					break;
			}
			break;
		case 3:
			switch ($rsMenu->value[$i]["target"]) {
				case "_blank":
					$addP =  $rsMenu->value[$i]["content"];
					$targetMenu = ";target=_blank";
					break;
				case "_self":
					$addP =  $rsMenu->value[$i]["content"];
					$targetMenu = ";target=_self";
					break;
				default:
					$addP = "?menu=" . $rsMenu->value[$i]["menu_id"] . "&file=" . $rsMenu->value[$i]["content"];
					break;
			}
			break;
		case 4:
			$addP = "?menu=" . $rsMenu->value[$i]["menu_id"] . "&content=" . $rsMenu->value[$i]["content"];
			break;
	}

	if ($child1->jumrec == 0) {
		echo '<li class="nav-item">
                <a class="nav-link" href="' . $addP . '" ' . $rsMenu->value[$i]["target"] . '>' . $rsMenu->value[$i]["menu_name"] . '</a>
				</li>' . "\n";
	} else {
		echo '<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				' . $rsMenu->value[$i]["menu_name"] . '
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
		for ($x = 1; $x <= $child1->jumrec; ++$x) {
			echo '<a class="dropdown-item" href="' . $child1->value[$x]['content'] . '" target="' . $child1->value[$x]['target'] . '">' . $child1->value[$x]['sub_menu_name'] . '</a>' . "\n";
		}
		echo   '</div>
				</li>';
	}
	/*
	if ($_GET["menu"]==$rsMenu->value[$i]["menu_id"])
		echo 'aI("text=<b><font color:#00FF00>'.$rsMenu->value[$i]["menu_name"].'</font></b>;url='.$addP.';showmenu=menu'.$rsMenu->value[$i]["menu_id"].';'.$targetMenu.'");';	
	else
		echo 'aI("text='.$rsMenu->value[$i]["menu_name"].';url='.$addP.';showmenu=menu'.$rsMenu->value[$i]["menu_id"].';'.$targetMenu.'");';	
	*/
}

function getChild($menu_id, $level)
{
	global $ora;
	global $bit_app;

	if ($level == 1) {
		$qry = "select * from sub_menu_l1 where menu_id=" . $menu_id . " order by posisi ASC";
		$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	} elseif ($level == 2) {
		$qry = "select * from sub_menu_l2 where menu_id=" . $menu_id . " order by posisi ASC";
		$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	} elseif ($level == 3) {
		$qry = "select * from sub_menu_l3 where menu_id=" . $menu_id . " order by posisi ASC";
		$rs = $ora->sql_fetch($qry, $bit_app["db"]);
	}

	return $rs;
}
