<?
/*
	By Afiat Darmawan
*/
class bit_ldap {

function auth($PHP_AUTH_USER1,$PHP_AUTH_PW1){

 $auth=0;
 global $nama;
 //$ldapconfig['host'] = '10.96.2.50';
 //$ldapconfig['host'] = '10.2.12.86';
 $ldapconfig['host'] = LDAP_HOST;
 
 $ldapconfig['authrealm'] = 'User Intranet Telkom ND';
 if ($PHP_AUTH_USER1 != "" && $PHP_AUTH_PW1 != "") {
    $ds=@ldap_connect($ldapconfig['host']);
    $r = @ldap_search( $ds, " ", 'uid=' . $PHP_AUTH_USER1);
    if ($r) {
            $result = @ldap_get_entries( $ds, $r);
            if (isset($result[0])) {
                if (@ldap_bind( $ds, $result[0]['dn'], $PHP_AUTH_PW1) ) {
                    $auth=1;
                }
            }
    }
 }
 return $auth;

}

function info($PHP_AUTH_USER1){

	$auth=0;
	global $nama;
	//$ldapconfig['host'] = '10.96.2.50';
	//$ldapconfig['host'] = '10.2.40.40';

	$ldapconfig['host'] = 'ldap.telkom.co.id';
	$ldapconfig['authrealm'] = 'User Intranet Telkom ND';
	if ($PHP_AUTH_USER1) {
		$ds=@ldap_connect($ldapconfig['host']);
		$r = @ldap_search( $ds, " ", 'uid=' . $PHP_AUTH_USER1);
		if ($r) {
			$result = @ldap_get_entries( $ds, $r);
		}
	}
		return $result;
	}

}
?>