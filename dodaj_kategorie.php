<!DOCTYPE html>

<?php 


  session_start();
  
  date_default_timezone_set('Europe/Warsaw');
  
  if(  !((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))  )
  {
    //header('Location:'.$_SERVER['HTTP_REFERER']);
    header('Location: index.php');
    exit();
  }

  include 'connect.php'; 
  include 'zdjecie.php';

    function wez_kat2($conn)
	{
	  $kategorie = array();
	  
	  $sql = "SELECT * FROM kategorie";
	  $result = mysqli_query($conn,$sql);
	  
	  while($row = mysqli_fetch_array($result))
	  {
	    $kategorie[] = $row;
	  }
	  
	  return $kategorie;
	}

?>

<html lang="en">
<head>
  <title>Dyd - Fotografia i podróże</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="lightbox.min.css">
  <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body>

<?php include 'naglowek.php';?>

<?php include 'menu.php';?>

<div id="heading" >
  <h1>Dodaj lub usuń kategorię</h1>
</div>

 <section id="main" class="wrapper">
    <div class="inner">
      <div class="content">
        <div class="row">  
      	<div class="col-6 col-12-medium">
          <form action='<?php dodaj_cat($conn)?>' method='post' enctype="multipart/form-data">
        
            <p><label>Dodaj nową kategorię</label>
            <input  class="form-control" name='nazwa' value='<?php if(isset($error)){ echo $_POST['nazwa'];}?>'></p>
            
            <p><input type='submit' name='submit' value='Dodaj'></p>
          </form>
         </div>
         <div class="col-6 col-12-medium">
          
          <form action='<?php usun_cat($conn)?>' method='post' enctype="multipart/form-data">
        
           <p><label>Usuń kategorię</label>
           <select class="custom-select" name="kategorie">
	            <?php 
	            	foreach( wez_kat2($conn) as $kategorie )
	            { ?>
	              <option value="<?php echo $kategorie['id']; ?>">
	              	<?php echo $kategorie['nazwa']; ?> 
	              </option>
	            <?php 
	            } ?>
            </select></p>
            
            <p><input type='submit' name='submit2' value='Unuń'></p>
          </form>
         </div>
        </div>
      </div>
    </div>
  </section>

<?php include 'footer.php';?>

<script src="lightbox-plus-jquery.min.js"></script>

<?php

	function dodaj_cat($conn)
	{
		if(isset($_POST['submit']))
		{
			$nazwa = $_POST['nazwa'];

			$sql = "INSERT INTO kategorie SET nazwa='$nazwa'";
			$result = mysqli_query($conn,$sql);
			header('Location: index.php?kategoria-dodana');
		}
	}

	function usun_cat($conn)
	{
		if(isset($_POST['submit2']))
		{
			$kategorie = $_POST['kategorie'];

			$sql = "DELETE FROM kategorie WHERE id='$kategorie'";
			$result = mysqli_query($conn,$sql);
			header('Location: index.php?kategoria-usunieta');
		}
	}
?>
 

</body>
</html>