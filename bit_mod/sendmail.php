<?

function MailSend($to,$subject,$body,$from,$sender,$cc,$bcc){

   //--------Initial variable---------

   $sess["user"]="";

   $sess["pass"]="";

   $sess["email"]=$from;

   

   $is_html = "true";

   $footer="";

   $real_name=$sender;

   $smtp_server="10.2.40.86";

   //$smtp_server="";

   //$cc="";

   //$bcc="";

   

   //---------------------------------

   $md = new mime_decode();

   $ARTo = $md->get_names(stripslashes($to));

   $ARCc = $md->get_names(stripslashes($cc));

   $ARBcc = $md->get_names(stripslashes($bcc));



   if((count($ARTo)+count($ARCc)+count($ARBcc)) > 0) {



		$mail = new phpmailer;



		// for password authenticated servers



		if($use_password_for_smtp)

			$mail->UseAuthLogin($sess["user"],$sess["pass"]);

		// if using the advanced editor

		if($is_html == "true")  {

			$mail->IsHTML(1);

			if($footer != "") $body .= nl2br($footer);

		} elseif ($footer != "") $body .= $footer;



		$mail->From = $sess["email"];

		$mail->FromName = $md->mime_encode_headers($real_name);

		$mail->AddReplyTo($reply_to, $md->mime_encode_headers($real_name));

		$mail->Host = $smtp_server;

		$mail->WordWrap = 76;



		if(count($ARTo) != 0) {

			for($i=0;$i<count($ARTo);$i++) {

				$name = $ARTo[$i]["name"];

				$email = $ARTo[$i]["mail"];

				if($name != $email)

					$mail->AddAddress($email,$md->mime_encode_headers($name));

				else

					$mail->AddAddress($email);

			}

		}



		if(count($ARCc) != 0) {

			for($i=0;$i<count($ARCc);$i++) {

				$name = $ARCc[$i]["name"];

				$email = $ARCc[$i]["mail"];

				if($name != $email)

					$mail->AddCC($email,$md->mime_encode_headers($name));

				else

					$mail->AddCC($email);

			}

		}



		if(count($ARBcc) != 0) {

			for($i=0;$i<count($ARBcc);$i++) {

				$name = $ARBcc[$i]["name"];

				$email = $ARBcc[$i]["mail"];

				if($name != $email)

					$mail->AddBCC($email,$md->mime_encode_headers($name));

				else

					$mail->AddBCC($email);

			}

		}



		if(is_array($attachs = $sess["attachments"])) {

			for($i=0;$i<count($attachs);$i++) {

				if(file_exists($attachs[$i]["localname"])) {

					$mail->AddAttachment($attachs[$i]["localname"], $attachs[$i]["name"], $attachs[$i]["type"]);

				}

			}

		}



		$mail->Subject = $md->mime_encode_headers(stripslashes($subject));

		$mail->Body = stripslashes($body);



		if(($resultmail = $mail->Send()) === false) {



			$err = $mail->ErrorAlerts[count($mail->ErrorAlerts)-1];

			echo $err;

			

		} else {

            //echo "Sending email success !";

        }

   }  

}      
	 	

?>

