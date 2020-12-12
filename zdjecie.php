<?php
ob_start();

function sprawdz_zdjecie($conn)
{
	$sql = "SELECT * FROM uzytkownicy WHERE login = '".$_SESSION['user']."' ";
	$result = mysqli_query($conn, $sql);
	while ($row = mysqli_fetch_assoc($result))
	{
		$id_uzytkownika =  $row['id'];
		$sqlImg = "SELECT * FROM zdjecie_profilowe WHERE id_uzytkownika  = '$id_uzytkownika' ";
		$resultImg = mysqli_query($conn, $sqlImg);
		while ($rowImg = mysqli_fetch_assoc($resultImg))
		{
			if($rowImg['status_zdjecia'] == 0)
			{
				$filename = "obrazy/profile".$id_uzytkownika."*";
				$fileinfo = glob($filename);	
				$fileext = explode(".", $fileinfo[0]);
				$fileactualext = $fileext[1];
				echo "<br/><img style='width:40%; float:none !important;' class='rounded-circle' src='obrazy/profile".$id_uzytkownika.".".$fileactualext."?".mt_rand()."'>";
			} 
			else 
			{
				echo "<img style='width:40%; float:none !important;' class='rounded-circle' src='obrazy/user-avatar.png'>";
			}
		
		}
	}
}

function zdjecie_admina($conn)
{
	$sql = "SELECT * FROM uzytkownicy WHERE admin = '1' ";
	$result = mysqli_query($conn, $sql);
	while ($row = mysqli_fetch_assoc($result))
	{
		$id_uzytkownika =  $row['id'];
		$sqlImg = "SELECT * FROM zdjecie_profilowe WHERE id_uzytkownika  = '$id_uzytkownika' ";
		$resultImg = mysqli_query($conn, $sqlImg);
		while ($rowImg = mysqli_fetch_assoc($resultImg))
		{
			if($rowImg['status_zdjecia'] == 0)
			{
				$filename = "obrazy/profile".$id_uzytkownika."*";
				$fileinfo = glob($filename);	
				$fileext = explode(".", $fileinfo[0]);
				$fileactualext = $fileext[1];
				echo "obrazy/profile".$id_uzytkownika.".".$fileactualext."?".mt_rand()."";
			} 
			else 
			{
				echo "obrazy/user-avatar.png";
			}
		
		}
	}
}

function zmien_zdjecie_profilowe($conn)
{
	if(isset($_POST['submit-profilowe']))
	{
		usun_zdjecie_profilowe($conn);
		
		$sql = "SELECT * FROM uzytkownicy WHERE login = '".$_SESSION['user']."' ";
		$result = mysqli_query($conn, $sql);
		while ($row = mysqli_fetch_assoc($result))
		{
		$id_uzytkownika =  $row['id'];
		}
		
		$obraz = $_FILES['postImage'];
					
		$obraz_nazwa = $_FILES['postImage']['name'];
		$obraz_path = $_FILES['postImage']['tmp_name']; //tymczasowe
		$obraz_size = $_FILES['postImage']['size'];
		$obraz_error = $_FILES['postImage']['error'];
		$obraz_type = $_FILES['postImage']['type'];
		
		$obraz_roz = explode('.',$obraz_nazwa);
		$obraz_rzeczywiste_roz =  strtolower(end($obraz_roz));
		
		$pozwolenie = array('jpg','jpeg','png');
		
		if(in_array($obraz_rzeczywiste_roz, $pozwolenie))
		{
			if($obraz_error === 0)
			{
				if($obraz_size < 2097152) // do 2 mb
				{
					$obraz_nazwa_nowa = "profile".$id_uzytkownika.".".$obraz_rzeczywiste_roz;
					$obraz_folder = 'obrazy/'.$obraz_nazwa_nowa;
					move_uploaded_file($obraz_path,$obraz_folder);
					
					$sql2 = "UPDATE zdjecie_profilowe SET status_zdjecia = 0 WHERE id_uzytkownika = '$id_uzytkownika' ";
					$result2 = mysqli_query($conn, $sql2);
				//	header('Location: ewangelia-apologetyka-kalwinizm?uploadsuccess');
					
				} else{
					echo "Zbyt duży plik!";
				}			
			} else {
				echo "Wystąpił błąd podczas uploadu obrazu!";
			}			
		} else {
			echo "Nie ten typ obrazu!";
		}
	header('Location: '.$_SERVER['HTTP_REFERER']);
	}
}

function usun_zdjecie_profilowe($conn)
{
	if(isset($_POST['usun-profilowe']))
	{
		$sql = "SELECT * FROM uzytkownicy WHERE login = '".$_SESSION['user']."' ";
		$result = mysqli_query($conn, $sql);
		while ($row = mysqli_fetch_assoc($result))
		{
		$id_uzytkownika =  $row['id'];
		}
		$filename = "obrazy/profile".$id_uzytkownika."*";
		$fileinfo = glob($filename);	
		$fileext = explode(".", $fileinfo[0]);
		$fileactualext = $fileext[1];
		
		//print_r($fileinfo);
		
		$file = "obrazy/profile".$id_uzytkownika.".".$fileactualext;
		
		if (!unlink($file))
		{
			echo "Nie usunięto!";
		}
		else
		{
			echo "Usunięto!";
		}
		$sql2 = "UPDATE zdjecie_profilowe SET status_zdjecia = 1 WHERE id_uzytkownika = '$id_uzytkownika' ";
		$result2 = mysqli_query($conn, $sql2);
		header('Location: '.$_SERVER['HTTP_REFERER']);
	}
}

?>