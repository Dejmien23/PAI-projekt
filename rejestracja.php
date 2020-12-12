<?php
	include 'connect.php';
	session_start();
	
	if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location:'.$_SERVER['HTTP_REFERER']);
		exit(); 
	}	
	
	if(isset($_POST['email']))
	{
		//udana walidacja
		$wszystko_OK = true;
		// sprawdzenie loginu/nicku
		$nick = $_POST['login'];
		//sprawdzenie dlugosci loginu/nicku
		if ((strlen($nick)< 3) || (strlen($nick)> 18))
		{
			$wszystko_OK = false;
			$_SESSION['e_nick'] = "Nick musi posiadać od 3 do 18 znaków!";
			header('Location:'.$_SERVER['HTTP_REFERER']);
		}
		
		if(ctype_alnum($nick)== false)
		{
			$wszystko_OK = false;
			$_SESSION['e_nick'] = "Login może składać się tylko z liter i cyfr (bez polskich znaków)";
			header('Location:'.$_SERVER['HTTP_REFERER']);
		}
		//sprawdzenie poprawnosci adresu email
		$email = $_POST['email'];
		$emailB = filter_var($email,FILTER_SANITIZE_EMAIL);
		
		if((filter_var($emailB, FILTER_VALIDATE_EMAIL)== false) || ($emailB != $email))
		{
			$wszystko_OK = false;
			$_SESSION['e_email'] = "Podaj poprawny adres e-mail";
			header('Location:'.$_SERVER['HTTP_REFERER']);
		}
		//sprawdzenie poprawnosci hasła
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
		
		if((strlen($haslo1)< 8) || (strlen($haslo1)> 20))
		{
			$wszystko_OK = false;
			$_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków";
			header('Location:'.$_SERVER['HTTP_REFERER']);
		}
		
		if ($haslo1 != $haslo2)
		{
			$wszystko_OK = false;
			$_SESSION['e_haslo'] = "Podane hasła nie są identyczne";
			header('Location:'.$_SERVER['HTTP_REFERER']);
		}
		
		$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT); 
		
		
		// zapamietaj wprowadzone dane
		$_SESSION['fr_nick']= $nick;
		$_SESSION['fr_email']= $email; 
		$_SESSION['fr_haslo1']= $haslo1;
		$_SESSION['fr_haslo2']= $haslo2;
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT); // zeby nie udostepniac raportow ludziom o localhost, root
	
		try
		{
			$polaczenie = new mysqli('localhost', 'root', '', 'pai_dziedzic');
			if($polaczenie->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());
				}
			else
			{
				// Czy email juz istnieje ?
				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email= '$email'");
				
				if(!$rezultat) throw new Exception($polaczenie->error);
				
				$ile_takich_maili = $rezultat->num_rows;
				if($ile_takich_maili>0)
				{
					$wszystko_OK = false;
					$_SESSION['e_email'] = "Istnieje już konto z takim adresem e-mail!";
					header('Location:'.$_SERVER['HTTP_REFERER']);
				}
				// Czy login juz istnieje ?
				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE login= '$nick'");
				
				if(!$rezultat) throw new Exception($polaczenie->error);
				
				$ile_takich_loginow = $rezultat->num_rows;
				if($ile_takich_loginow>0)
				{
					$wszystko_OK = false;
					$_SESSION['e_nick'] = "Istnieje już konto z podanym loginem!";
					header('Location:'.$_SERVER['HTTP_REFERER']);
				}
				
				if($wszystko_OK == true)
				{
					//rejestracja udana
					if($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL,'$nick','$haslo_hash','$email',NULL)"))
					{
						$sql = "SELECT * FROM uzytkownicy WHERE login = '$nick'";
						$result = mysqli_query($conn ,$sql);
						
						if(mysqli_num_rows($result) > 0)
						{
							while ($row = mysqli_fetch_assoc($result))
							{
								$id_uzytkownika = $row['id'];
								
								$sql2 = "INSERT INTO zdjecie_profilowe (id_uzytkownika, status_zdjecia) VALUES ('$id_uzytkownika', 1)";
								$result2 = mysqli_query($conn ,$sql2);
							}		
						} 
						else 
						{	
							echo "Error!";
						}
						
						$_SESSION['udanarejestracja'] = true;
						header('Location:'.$_SERVER['HTTP_REFERER'].'?udana-rejestracja');
					}
					else
					{
						throw new Exception($polaczenie->error);
					}
				}
				

				$polaczenie->close();
			}
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;"> Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestację w innym terminie.</span>';
			echo '</br><span style="color:black;">Informacja developerska:</span>'.$e;
		}
	}
?>