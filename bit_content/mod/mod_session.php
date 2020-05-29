<?
	session_start();
	
	function checkSession() {
		return ($_SESSION["userid_portal"]?true:false);
	}

	function destroySession() {
		session_destroy();
	}	
	
	function registerSession($name,$val) {
		//session_register("$name");
		$_SESSION["$name"]=$val;
	}
	
	function getSessionName() {
		return $_SESSION["userid_portal"];
	}
		
?>