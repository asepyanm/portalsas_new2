<?
	/* By Afiat Darmawan*/
 	class bit_result {
  		var $kolom;
 		var $value;
 		var $jumkolom;
 		var $jumrec;
 	}
 
 	class bit_oracle {

 		function logon($user=DB_USER_ORA,$pass=DB_PASS_ORA,$db="1") {
 			$this->user=$user;
 			$this->pass=$pass;
			if ($db=="") {
				$this->db="ORACLE";
			} else {
				$this->db="(DESCRIPTION =
						(ADDRESS_LIST =
						  (ADDRESS = (PROTOCOL = TCP)(HOST = ".DB_HOST_ORA.")(PORT = ".DB_PORT_ORA."))
						)
						(CONNECT_DATA =
						  (SERVICE_NAME = ".DB_SERVICE_ORA.")
						))";
			}
			return $this->conn=OCIlogon($this->user,$this->pass,$this->db) or die('Koneksi ke database Gagal, mohon konfirmasi administrator');;
			//return ocierror();
		}
	
		
 		function parse($qry) {
			$this->stmt=OCIparse($this->conn,$qry); //die ('Parsing Data gagal, mohon konfirmasi administrator');
			return $this->stmt;
 		}

		function exec() {
			if (!OCIexecute($this->stmt))
				return false;
			else
				return true;
		}
		
 		function fetch() {
 			$result=new bit_result;
 			$j=0;
 			while (OCIFetch($this->stmt)) {
 				$j++;
 				for ($i=1;$i<=OCINumCols($this->stmt);$i++) {
 					$result->kolom[$i]=OCIColumnName($this->stmt,$i);	
 					$result->value[$j][$i]=OCIResult($this->stmt,$i);
					$result->value[$j][strtolower(OCIColumnName($this->stmt,$i))]=OCIResult($this->stmt,$i);
				}
 			}
 			$result->jumrec=$j;
 			return $result;
 		}
		
		
		
		function fetchClob($field="") {
 			$result=new bit_result;
 			$j=0;
			
 			while (OCIFetchInto($this->stmt,$arr,OCI_ASSOC)) {
				$j++;
 				for ($i=1;$i<=OCINumCols($this->stmt);$i++) {
					$result->kolom[$i]=OCIColumnName($this->stmt,$i);	
 					if (OCIColumnName($this->stmt,$i)==strtoupper($field)) { 
 						if ($arr[strtoupper($field)]->load()) {
 							$result->value[$j][$i]=$arr[strtoupper($field)]->load();
							$result->value[$j][strtolower($result->kolom[$i])]=$arr[strtoupper($field)]->load();
						}
					}
					else {
						$result->value[$j][$i]=OCIResult($this->stmt,$i);
 						$result->value[$j][strtolower($result->kolom[$i])]=OCIResult($this->stmt,$i);
					}									
				}
 			}
 			$result->jumrec=$j;
 			return $result;
 		}

    	function sql_no_fetch($qry) {
			$stmt=$this->parse($qry);
			if ($stmt) {
				$exec=$this->exec();
			}
			$this->free();
			if (!$exec)
				return false;
			else
				return true;
		}
		
		function sql_no_fetch_clob($qry,$field,$content) {
			$stmt=$this->parse($qry);
			$clob = OCINewDescriptor($this->conn, OCI_D_LOB); 
			OCIBindByName ($stmt, ":$field", $clob, -1, OCI_B_CLOB); 
			$exec=OCIExecute($stmt,OCI_DEFAULT); 
			if (!$exec)
				return false;
			else {
				$clob->save($content); 
				OCICommit($this->conn);
				$clob->free();
				return true;
			}
		}

    	function sql_fetch($qry) {
			$stmt=$this->parse($qry);
			if ($stmt) {
				$exec=$this->exec();
			}
			$_rs=$this->fetch();
			$this->free();
			return $_rs;
		}
		
		function error() {
			$err=ocierror($this->conn);
			return $err;
		}
		
		function sql_fetch_clob($qry,$field) {
			$stmt=$this->parse($qry);
			if ($stmt) {
				$exec=$this->exec();
			}
			$_rs=$this->fetchClob($field);
			$this->free();
			return $_rs;
		}

 		function rownum() {
 			return OCIRowcount($this->stmt);
 		}

 		function free() {
 			OCIFreestatement($this->stmt);
 		}

 		function logoff() {
 			OCIlogoff($this->conn);
 		}
 	}
?>
