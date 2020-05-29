<?

function sendemail($to,$content) {

$headers = "From: CBD<admin_cbd@telkom.co.id>\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$subject="Notifikasi dari Consumer Business Dashboard";
      

#echo "to :".$to."<br>";
#echo "subject :".$subject."<br>";
#echo $content .="<br><br>Terima Kasih atas kerjasamanya";

MailSend($to,$subject,$content,"admin_cbd@telkom.co.id","Consumer Business Dashboard","","");
#echo $to."<br>".$subject."<br>".$content."<br>".$headers;
}

function sendemailHelpdesk($from,$content,$subject) {

$headers = "From: CBD<admin_cbd@telkom.co.id>\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";


#echo "to :".$to."<br>";
#echo "from  :".$from."<br>";
#echo "subject :".$subject."<br>";
#echo $content;

MailSend("helpdesk.cbd@telkom.co.id",$subject,$content,$from,"Consumer Business Dashboard Helpdesk","","");
#echo $to."<br>".$subject."<br>".$content."<br>".$headers;
}


?>