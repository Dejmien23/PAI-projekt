<?php

function kontakt_email($conn)
{
	if(isset($_POST['submit-email']))
	{
		$imie = $_POST['name'];
		$email = $_POST['email'];
		$temat = $_POST['temat'];
		$message = $_POST['textarea'];
		
		// Remove all illegal characters from email
		$emailFrom = filter_var($email, FILTER_SANITIZE_EMAIL);
				
		$emailTo = "dejmien23@gmail.com";
		$headers = "From: ".$emailFrom;
		$txt = "Otrzymałeś wiadomość od ".$imie.".\n\n".$message;
		
		mail($emailTo, $temat, $txt, $headers);
		header('Location: '.$_SERVER['HTTP_REFERER'].'?wiadomosc-wyslana');
		
	}
}

?>