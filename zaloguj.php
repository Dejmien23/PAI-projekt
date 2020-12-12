<?php

	session_start();
	
	if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
	{
		header('Location:'.$_SERVER['HTTP_REFERER']);
		exit();
	}
	
	$polaczenie = @new mysqli('localhost', 'root', '', 'pai_dziedzic');
	
	if($polaczenie->connect_errno!=0)
	{
		echo "Error:".$polaczenie->connect_errno;
	}
	else
	{
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");

		if ($rezultat = @$polaczenie->query(
		sprintf("SELECT * FROM uzytkownicy WHERE login='%s'", 
		mysqli_real_escape_string($polaczenie,$login))))
		{
			$ilu_userow = $rezultat->num_rows;
			if($ilu_userow>0)
			{
				$wiersz = $rezultat->fetch_assoc();
				
				if(password_verify($haslo, $wiersz['haslo']))
				{
					if($wiersz['admin'] == 1)
					{
						$_SESSION['admin'] = true;
					}
					$_SESSION['zalogowany'] = true;
					$_SESSION['user'] = $wiersz['login'];
					$_SESSION['id'] = $wiersz['id'];
					unset($_SESSION['blad']);
					$rezultat->close();
					header('Location: '.$_SERVER['HTTP_REFERER']);
					
				} else 
				{
			
					$_SESSION['blad'] = '<span style="font-size: 18px; color:red"> Nieprawidłowy login lub hasło! </span>';	
					header('Location:'.$_SERVER['HTTP_REFERER']);
				}
			
				
			} else {
			
				$_SESSION['blad'] = '<span style="font-size: 18px; color:red"> Nieprawidłowy login lub hasło! </span>';	
				header('Location:'.$_SERVER['HTTP_REFERER']);
			}
			
		}
		
		$polaczenie->close();
	}
	
?>