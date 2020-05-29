<?
	/*
		Created by Afiat Darmawan
		11-06-2006
	*/
	class clsResult {
  		var $namakolom;
 		var $value;
 		var $jumkolom;
 		var $jumrec;
 	}
	
	class clsMysql {

		private $conn;
 	
		function init($user="",$pass="",$host="") {
			//$host=DB_HOST.":".DB_PORT;
			
			$this->logon($user,$pass,$host);
			if (mysql_error()) 
				return false;
			else
				return true;

			
		}
		
		function show_error() {
			if (mysql_error()) {
				echo mysql_error();
				return false;			
			} else
				return true;
		}

		function logon($user="",$pass="",$host="") {
 			$this->user=DB_PORTALSAS_USER;
			$this->pass=DB_PORTALSAS_PASS;
			$this->host=DB_PORTALSAS_HOST;
			$this->conn=@mysql_connect($this->host,$this->user,$this->pass);
			$this->db=@mysql_select_db(DB_PORTALSAS_NAME, $this->conn);
			#$this->show_error();
		}
		
		function parse($qry,$db) {
			//$con = $this->conn;
			//mysql_select_db($db, $this->conn);
			//echo $qry;
			$this->stmt=mysql_query($qry,$this->conn);
			if (!$this->stmt) {
				echo mysql_error();
			}
			return $this->stmt;
 		}
		
		function sql_no_fetch($qry,$db) {
			$err=$this->parse($qry,$db);
			if (!$err) 
				return 0;
			
			return 1;
		}
		
		function sql_fetch($qry,$db) {
			$err=$this->parse($qry,$db);
			if (!$err) 
				return 0;
	
			$rs=$this->fetch();
			$this->free();
			
			return $rs;
		}
		
 		function fetch() {
 			$result=new clsResult;
 			$j=0;
			 while ($row = mysql_fetch_array($this->stmt, MYSQL_NUM)) { 
				$j++;
 				for ($i=1;$i<=mysql_num_fields($this->stmt);$i++) {
 					$result->namakolom[$i]=strtoupper(mysql_field_name($this->stmt,$i-1));	
 					$result->value[$j][strtolower($result->namakolom[$i])]=$row[$i-1];
 					$result->value[$j][$i]=$row[$i-1];
 				}
 			}
 			$result->jumrec=$j;
 			return $result;
 		}

		function fetchArray() {
 			$rs=new clsResult;
 			$j=1;
 			while ($row = mysql_fetch_array($this->stmt, MYSQL_NUM)) {
 				$col1=trim($row[0]);
 				$col2=trim($row[1]);
				$rs->value[$col1][$col2]=$row[2];
				$j++;	
 			}
 			$rs->jumrec=$j-1;
 			return $rs;
 		}
		
		function fetchKhusus() {
 			$rs=new clsResult;
 			$j=1;
 			while ($row = mysql_fetch_array($this->stmt, MYSQL_NUM)) {
 				$col1=trim($row[0]);
 				$rs->value[$col1]=$row[1];
				$j++;	
 			}
 			$rs->jumrec=$j-1;
 			return $rs;
 		}
		
		function read_sql($file) {
			$fp    = fopen($file, 'r');
			$query = fread($fp, filesize($file));
			fclose($fp);
			$this->parse($query);
		}		

		function backup($db) {
			for ($j=0;$j<count($tbl);$j++) {
				$result .= "# Dump of $table \n";
				$result .= "# Dump DATE : " . date("d-M-Y") ."\n\n";
				$query = mysql_query("select * from ".$tbl[$i]);
				$num_fields = @mysql_num_fields($query);
				$numrow = mysql_num_rows($query);

				for ($i =0; $i<$numrow; $i++) {
				  $result .= "INSERT INTO ".$tbl[$i]." VALUES(";
					  for($j=0; $j<$num_fields; $j++) {
					  $row[$j] = addslashes($row[$j]);
					  $row[$j] = preg_replace("/\n/","\\n",$row[$j]);
					  if (isset($row[$j])) $result .= "\"$row[$j]\"" ; else $result .= "\"\"";
					  if ($j<($num_fields-1)) $result .= ",";
					 }   
				  $result .= ");\n\n\n";
				 }
			 }
		}

		function kill() {
		
			set_time_limit(30000); 
			$result=mysql_query("show processlist"); 
			while ($row=mysql_fetch_array($result)) 
			{ 
			$process_id=$row["Id"]; 
			if (($row["Time"] > 100 ) || ($row["Command"]=="Sleep") ) 
			{ 
			print $row["Id"]; 
			$sql="kill $process_id"; 
			mysql_query($sql); 
			} 
			} 
		}

		function create_database($db) {
			mysql_create_db($db);
		}

		function drop_database($db) {
			$qry="drop database $db";
			mysql_query($qry);
		}

		function rownum() {
 			return mysql_affected_rows();
 		}

		function free() {
			mysql_free_result($this->stmt);
		}

		function logoff() {
			mysql_close($this->conn);
		}
 	}

?>