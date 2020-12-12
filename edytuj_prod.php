<!DOCTYPE html>

<?php 


  session_start();
  
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
  <h1>Edycja wpisu</h1>
</div>

 <section id="main" class="wrapper">
    <div class="inner">
      <div class="content">
        <div class="row">  

          <?php 
            $id = $_POST['id'];
            $postTitle = $_POST['tytul'];
            $postDesc = $_POST['opis'];
          ?>

          <form action='<?php edytuj_prod($conn)?>' method='post' enctype="multipart/form-data">
        
            <p><label>Zmień nazwę</label>
            <input type='hidden' name='postID' value='<?php echo $_POST['id'];?>'>

            <input type='text' name='postTitle' value='<?php echo $_POST['tytul']; ?>'></p>

            <p><label>Zmień opis</label>
            <textarea id='editor1' name='postDesc' cols='60' rows='10'><?php echo $_POST['opis']; ?></textarea></p>
          
            <p><label>Zmień kategorie</label>
            <select name="kategorie">
            <?php foreach( wez_kat($conn) as $kategorie )
            { ?>
              <option value="<?php echo $kategorie['id']; ?>"><?php echo $kategorie['nazwa']; ?></option>
            <?php 
            } ?>
            </select></p>
            
            <p><input type='submit' name='submit' value='Zmień'></p>
            
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
    
  function edytuj_prod($conn)  
  {
    if(isset($_POST['submit']))
    {
      $id = $_POST['postID'];     
      $kategorie = $_POST['kategorie'];
      $nazwa = $_POST['postTitle'];
      $opis = $_POST['postDesc'];
      
      $sql = "UPDATE produkty SET kategoria_id='$kategorie', nazwa='$nazwa', opis='$opis' WHERE id = '$id'";
      $result = mysqli_query($conn,$sql);
      
      header('Location: index.php?wpis-zmieniony');      
    }
  
  }

  ?>

</body>
</html>