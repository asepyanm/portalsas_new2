<?

	/* class untuk database oracle */
 	class clsResult {
  		var $kolom;
 		var $value;
 		var $jumkolom;
 		var $jumrec;
 	}
 
 	class clsOracle {

 		function logon($user="portal",$pass="portal",$db="") {
 			$this->user=$user;
 			$this->pass=$pass;
			$this->db=$db;
			return $this->conn=OCIlogon($this->user,$this->pass,$this->db) or die('Koneksi ke database Gagal, mohon konfirmasi administrator');;
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
				
				// or die("eksekusi query data gagal, mohon konfirmasi administrator !");
 		}
		
 		function fetch() {
 			$result=new clsResult;
 			$j=0;
 			while (OCIFetch($this->stmt)) {
 				$j++;
 				for ($i=1;$i<=OCINumCols($this->stmt);$i++) {
 					$result->kolom[$i]=OCIColumnName($this->stmt,$i);	
 					$result->value[$j][$i]=OCIResult($this->stmt,$i);
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
			if (!$exec)
				return false;
			else
				return true;
		}
		
		function sql_no_fetch_clob($qry,$field,$content) {
			$stmt=$this->parse($qry);
			$clob = OCINewDescriptor($this->conn, OCI_D_LOB); 
			OCIBindByName ($stmt, ":$field", &$clob, -1, OCI_B_CLOB); 
			$exec=OCIExecute($stmt,OCI_DEFAULT); 
			
			if (!$exec)
				return false;
			else {
				$clob->save($content); 
				OCICommit($this->conn); 
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