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
  <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body>

<?php include 'naglowek.php';?>

<?php include 'menu.php';?>

<div id="heading" >
  <h1>Usuwanie wpisu</h1>
</div>

  <section id="main" class="wrapper">
    <div class="inner">
      <div class="content">
        <div class="row">      
        <?php 
          $id = $_POST['id'];
        ?>

          <form action='<?php usun_prod($conn)?>' method='post' enctype="multipart/form-data">
            <input type='hidden' name='postID' value='<?php echo $_POST['id'];?>'>
            <p><label>Czy na pewno chcesz usunąć wpis?</label><br />
              <input value="Usuń" class="primary" type='submit' name='submit'>
          </form>

        </div>
      </div>
    </div>
  </section>

<?php include 'footer.php';?>

<script src="lightbox-plus-jquery.min.js"></script>

<?php
      ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

  function usun_prod($conn)  
      {
        if(isset($_POST['submit']))
        {
          $id = $_POST['postID'];

          $sql = "DELETE FROM produkty WHERE id = '$id'";
          $result = mysqli_query($conn,$sql);
          
          header('Location: index.php?wpis-usuniety');   

        }
      }  
  ?>

</body>
</html>