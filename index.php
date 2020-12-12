<!DOCTYPE html>

<?php 

  include 'connect.php'; 
  session_start();

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
  <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/main.css" />

</head>

<body>

<?php include 'naglowek.php';?>

<?php include 'menu.php';?>

<?php include 'baner.php';?>


<?php

  $kat_id = isset($_GET['kat_id']) ? (int)$_GET['kat_id'] : 1;

  $sql = 'SELECT `id`, `nazwa`, `opis`, `img`
               FROM `produkty`
               WHERE `kategoria_id` = ' . $kat_id .
               ' ORDER BY `id`';

  $wynik = mysqli_query($conn, $sql);

  $sql2 = 'SELECT `id`, `nazwa`
               FROM `kategorie`
               WHERE `id` = ' . $kat_id;

  $wynik2 = mysqli_query($conn, $sql2);

  if(isset($_GET['kat_id'])) {
    echo '<section class="wrapper">
            <div class="inner">
            <header class="special">
              <h2>Kategoria: '; 
                if (mysqli_num_rows($wynik2) > 0) 
                {
                  while ($nazwa_kat = @mysqli_fetch_array($wynik2)) 
                  {
                    echo $nazwa_kat['nazwa'];
                  }
                }

        echo '</h2>
            </header>
              <div class="highlights">';

    if (mysqli_num_rows($wynik) > 0) {
        while ($produkt = @mysqli_fetch_array($wynik)) {
            echo 
              '<section>
                <div class="content">
                  <header>
                    <h3>'.$produkt['nazwa'].'</h3>';
                    if((isset($_SESSION['admin'])) && ($_SESSION['admin']==true))
                      {
                          echo"<form style='float: left;' method='post' action='edytuj_prod.php'>
                            <input type='hidden' name='id' value='".$produkt['id']."' >
                            <input type='hidden' name='tytul' value='".$produkt['nazwa']."' >
                            <input type='hidden' name='opis' value='".$produkt['opis']."' >
                            <button style='box-shadow: none;'class='button small' type='submit'> <i class='fa fa-cog'></i>&nbsp;Edytuj</button>
                          </form>";
                          echo"<form style='float: right;' method='post' action='usun_prod.php'>
                            <input type='hidden' name='id' value='".$produkt['id']."' >
                            <button style='box-shadow: none;' class='button small' type='submit'><i class='fa fa-trash' aria-hidden='true'></i>&nbsp;Usuń</button>
                          </form>";
                      }
                    echo '<a class="example-image-link" href="'.$produkt['img'].'" data-lightbox="example-set"><img width="100%" src="
                    '.$produkt['img'].'"></a>
                  </header>';

               echo '<button style="box-shadow: none;" type="button" class="button small" data-toggle="collapse" data-target="#tekst'.$produkt['id'].'">Przeczytaj...</button>
                    <div id="tekst'.$produkt['id'].'" class="collapse">
                    '.$produkt['opis'].'
                    </div>
                </div>
              </section>';
        }
      }
      echo ' </div>
            </div>
          </section>';
    } 
  else {
        include 'glowna.php';
      }

  mysqli_close($conn);

?>

<?php include 'cytaty.php';?>

<?php include 'footer.php';?>

<script src="lightbox-plus-jquery.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/browser.min.js"></script>
<script src="assets/js/breakpoints.min.js"></script>
<script src="assets/js/util.js"></script>
<script src="assets/js/main.js"></script>

<script type="text/javascript">
    // Select all links with hashes
  $('a[href*="#"]')
    // Remove links that don't actually link to anything
    .not('[href="#"]')
    .not('[href="#0"]')
    .click(function(event) {
      // On-page links
      if (
        location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
        && 
        location.hostname == this.hostname
      ) {
        // Figure out element to scroll to
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        // Does a scroll target exist?
        if (target.length) {
          // Only prevent default if animation is actually gonna happen
          event.preventDefault();
          $('html, body').animate({
            scrollTop: target.offset().top
          }, 1000, function() {
            // Callback after animation
            // Must change focus!
            var $target = $(target);
            $target.focus();
            if ($target.is(":focus")) { // Checking if the target was focused
              return false;
            } else {
              $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
              $target.focus(); // Set focus again
            };
          });
        }
      }
    });
</script>

<script>
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
  this.classList.toggle("active");
  var dropdownContent = this.nextElementSibling;
  if (dropdownContent.style.display === "block") {
  dropdownContent.style.display = "none";
  } else {
  dropdownContent.style.display = "block";
  }
  });
}
</script>

</body>
</html>
