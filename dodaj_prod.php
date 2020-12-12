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

  function wez_kat($conn)
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

  <script>
      document.forms[0].submit()
  </script>
  <script src="//cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>


  <link rel="stylesheet" href="lightbox.min.css">
  <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body>

<?php include 'naglowek.php';?>

<?php include 'menu.php';?>

<div id="heading" >
  <h1>Dodaj wpis</h1>
</div>

 <section id="main" class="wrapper">
    <div class="inner">
      <div class="content">
        <div class="row">  
      
          <form action='<?php dodaj_prod($conn)?>' method='post' enctype="multipart/form-data">
        
            <p><label>Nazwa</label>
            <input  class="form-control" name='postTitle' value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>'></p>

            <p><label>Opis</label>
            <textarea id='editor1' name='postDesc' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postDesc'];}?></textarea></p>
            
            <p><div class="custom-file">
              <input type="file" name='postImage' class="custom-file-input" id="customFile" value='<?php if(isset($error)){ echo $_POST['postImage'];}?>'>
              <label class="custom-file-label" for="customFile">Wybierz zdjęcie</label>
            </div></p>

            <p><label>Wybierz kategorie</label>
            <select class="custom-select" name="kategorie">
            <?php foreach( wez_kat($conn) as $kategorie )
            { ?>
              <option value="<?php echo $kategorie['id']; ?>"><?php echo $kategorie['nazwa']; ?></option>
            <?php 
            } ?>
            </select></p>
          
            
            <p><input type='submit' name='submit' value='Opublikuj'></p>
            

             <script>
              CKEDITOR.replace( 'editor1' );
            </script>

          </form>

        </div>
      </div>
    </div>
  </section>

<?php include 'footer.php';?>

<script src="lightbox-plus-jquery.min.js"></script>

<?php
    
  function dodaj_prod($conn) 
  {
    if(isset($_POST['submit']))
    {
      if(!isset($error))
      { 
        //$obraz_path = "images/".$obraz_nazwa;
        $kategorie = $_POST['kategorie'];
        $tytul = $_POST['postTitle'];
        $opis = $_POST['postDesc'];
        
        $tytul_2 = preg_replace('/[^\00-\255]+/u', '', $tytul);
        
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
            if($obraz_size < 4194304) // do 4 mb
            {
              $obraz_nazwa_nowa = "prod".$tytul_2.".".$obraz_rzeczywiste_roz;
              $obraz_folder = 'obrazy/'.$obraz_nazwa_nowa;
              move_uploaded_file($obraz_path,$obraz_folder);    
            }       
          }     
        }
        
        $sql = "INSERT INTO produkty (kategoria_id, nazwa, opis, img) VALUES ('$kategorie','$tytul','$opis','$obraz_folder')";
        $result = mysqli_query($conn,$sql);
        
        header('Location: index.php?wpis-dodany');
      }
      if(isset($error))
      {
        foreach($error as $error)
        {
          echo '<p class="error">'.$error.'</p>';
        }
      }
    }
  
  }

      
  ?>

</body>
</html>