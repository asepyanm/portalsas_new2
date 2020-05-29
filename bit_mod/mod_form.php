<?php
	class clsForm {
			function textbox($name,$id,$value='',$class='',$size=10,$max=10,$funct='',$param='') {
				$max=255;
				echo "<input type='textbox' 
						name='$name'
						id='$id'
						class='$class'
						size='$size'
						maxlength='$max'
						value='$value'
						onKeyPress=\"$funct\"
						$param
						>";
			}
			
			function password($name,$id,$value='',$class='',$size=10,$max=10,$funct='',$param='') {
				echo "<input type='password' 
						name='$name'
						id='$id'
						class='$class'
						size='$size'
						maxlength='$max'
						value='$value'
						onKeyPress=\"$funct\"
						$param
						>";
			}
			
			function select($name,$id,$data='',$value='',$class='',$onChange='',$size,$params) {
				$ret = "<select
						name='$name'
						id='$id'
						class=\"$class\"
						onChange=\"$onChange\"
						size=$size
						$params
						>";
				if ($params=="multiple") {
					for ($i=0;$i<count($data);$i++) {
						reset($value);
						$ketemu=false;
						while (list($k,$v)=each($value)) {
							if ($data[$i][0]==$v && $data[$i][0]!="")
								$ketemu=true;
						}
						if ($ketemu)
							$ret .="<option selected value='".$data[$i][0]."'>".$data[$i][1]."</option>";
						else
							$ret .="<option value='".$data[$i][0]."'>".$data[$i][1]."</option>";
					}		
				} else {
					if ($data[0][1])
						$ret .="<option value='".$data[0][0]."'>".$data[0][1]."</option>";
					for ($i=1;$i<count($data);$i++) {
						if ($value==$data[$i][0])
							$ret .="<option selected value='".$data[$i][0]."'>".$data[$i][1]."</option>";
						else
							$ret .="<option value='".$data[$i][0]."'>".$data[$i][1]."</option>";
					}
				}
				$ret .="</select>";
				echo $ret;
			}
			
			function selectMonth($name,$id,$value='',$class='') {
				$arrMonth=array(""=>"",
					"01"=>"Januari",
					"02"=>"Februari",
					"03"=>"Maret",
					"04"=>"April",
					"05"=>"Mei",
					"06"=>"Juni",
					"07"=>"Juli",
					"08"=>"Agustus",
					"09"=>"September",
					"10"=>"Oktober",
					"11"=>"November",
					"12"=>"Desember");
				echo "<select
						name='$name'
						id='$id'
						class='$class'
						>";
				while (list($key,$val)=each($arrMonth)) {
					if ($value==$key)
						echo "<option selected value='".$key."'>".$val."</option>";
					else
						echo "<option value='".$key."'>".$val."</option>";
				}		
				echo "</select>&nbsp;&nbsp;";
			}
			
			function selectYear($name,$id,$value='',$class='') {
				$arrYear[0]="";
				for ($i=2000;$i<=date("Y")+1;$i++) {
					$arrYear[]=$i;
				}
				echo "<select
						name='$name'
						id='$id'
						class='$class'
						>";
				while (list($key,$val)=each($arrYear)) {
					if ($value==$val)
						echo "<option selected value='".$val."'>".$val."</option>";
					else
						echo "<option value='".$val."'>".$val."</option>";
				}		
				echo "</select>&nbsp;&nbsp;";
			}
			
			function textarea($name,$id,$value='',$class='',$row=2,$col=10) {
				echo "<textarea 
							name='$name'
							id='$id'
							rows=$row
							cols=$col
							class='$class'
						>$value</textarea>&nbsp;";
			}
			
			function hidden($name,$id,$value='') {
				echo "<input
							type='hidden'
							name='$name'
							id='$id'
							value='$value'
						>";
			}
			
			function  radio($name,$id,$value,$class,$display,$param) {
				echo "<input 
						type='radio'
						name='$name'
						id='$id'
						value='$value'
						class='$class'
						$param
					  >$display &nbsp; ";
			}
			
			function file($name,$id,$value='',$class='',$size=40,$max=40,$onChange="") {
				echo "<input
							type='file' 
							name='$name'
							id='$id'
							value='$value'
							class='$class'
							size='$size'
							maxlength='$max'
							onChange=\"$onChange\"
						>";
			}
			function submit($name,$value='submit',$class='',$onClick='') {
				echo "<input 
							type='submit'
							name='$name'
							class='$class'
							onClick=\"$onClick\"
							value='$value'>&nbsp;";
			}
			
			function clear($name,$value='bersih',$class='') {
				echo "<input 
							type='reset'
							name='$name'
							class='$class'
							value='$value'
						>&nbsp;";
			}
			
			function button($name,$value='bersih',$class='',$funct='') {
				echo "<input 
							type='button'
							name='$name'
							class='$class'
							value='$value'
							onClick=\"$funct\"
						>&nbsp;";
			}
			function tanggal($name,$id,$value='',$class='',$size=10,$max=10) {
				$this->textbox($name,$id,$value,$class,$size,$max,'',"readonly='yes'");
				echo "&nbsp;";
				echo "<input 
							name='reset' 
							type='reset' 
							class='button' 
							onClick=\"return showCalendar('$id', 'y-mm-dd');\" 
							value='...'
						>";
			}
			
			function openForm($name='',$id='',$action='',$method='POST',$enctype='',$onSub="") {
				echo "<form 
						name='$name'
						id='$id'
						method='$method'
						enctype='$enctype'
						action='$action'
						$onSub
						>";
			}
			
			function closeForm() {
				echo "</form>";
			}
	}
?>