<!DOCTYPE html>

<?php 

	include 'connect.php'; 
	include 'zdjecie.php'; 
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

<div id="heading" >
	<h1>Panel użytkownika</h1>
</div>

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
                            <button style='box-shadow: none;' class='button small' type='submit'> <i class='fa fa-cog'></i>&nbsp;Edytuj</button>
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

        	if(!isset($_SESSION['zalogowany']))
        		{
              	echo '<section id="main" class="wrapper">
					    <div class="inner">
					      <div class="content">
					        <div class="row"> 
					        	<div class="col-6 col-12-medium">
							      <form action="zaloguj.php" method="post">
							      	<p>
							     	<legend> Jeśli posiadasz już konto: </legend>
							     	</p><p>
							        <input type="text" id="login" name="login" placeholder="Login">
							     	</p><p>
							        <input type="password" id="haslo" name="haslo" placeholder="Hasło">
							        </p><p>
							        <input type="submit" value="Zaloguj się">
							        </p>
							      </form>
							     </div>';
					}
		?>

		 <?php if(!isset($_SESSION['zalogowany'])) : ?>
						
				<div class="col-6 col-12-medium">	
					<form action="rejestracja.php" method="post">
					<fieldset>
					<p>
						<legend> Jeśli jesteś nowy: </legend>
					</p>
					<p>
						<input type="text" class="fadeIn third" value="<?php 
							if(isset($_SESSION['fr_nick']))
							{
								echo $_SESSION['fr_nick'];
								unset($_SESSION['fr_nick']);
							}
						?>" name="login" placeholder="Login" onfocus="this.placeholder=''" onblur="this.placeholder='Login'"/>
						
							<?php
								if(isset($_SESSION['e_nick']))
								{
									echo'<div class="error">'.$_SESSION['e_nick'].'</div>';
									unset($_SESSION['e_nick']);
								}
							?>
					</p><p>
						<input type="text" class="fadeIn third" value="<?php 
							if(isset($_SESSION['fr_email']))
							{
								echo $_SESSION['fr_email'];
								unset($_SESSION['fr_email']);
							}

						?>" name="email" placeholder="E-mail" onfocus="this.placeholder=''" onblur="this.placeholder='E-mail'"/>
							<?php
								if(isset($_SESSION['e_email']))
								{
									echo'<div class="error">'.$_SESSION['e_email'].'</div>';
									unset($_SESSION['e_email']);
								}
							?>
					</p><p>
						<input type="password" class="fadeIn third" value="<?php 
							if(isset($_SESSION['fr_haslo1']))
							{
								echo $_SESSION['fr_haslo1'];
								unset($_SESSION['fr_haslo1']);
							}

						?>" name="haslo1" placeholder="Hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Hasło'"/>
							<?php
								if(isset($_SESSION['e_haslo']))
								{
									echo'<div class="error">'.$_SESSION['e_haslo'].'</div>';
									unset($_SESSION['e_haslo']);
								}
							?>
					</p><p>
						<input type="password" class="fadeIn third" value="<?php 
							if(isset($_SESSION['fr_haslo2']))
							{
								echo $_SESSION['fr_haslo2'];
								unset($_SESSION['fr_haslo2']);
							}

						?>" name="haslo2" placeholder="Powtórz hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Powtórz hasło'"/>
					</p><p>
						<input type="submit" value="Zarejestruj się" />
					</p>							
					</fieldset>
					</form>
				</div>
		     </div>
	      </div>
	    </div>
	  </section>

		<?php endif; ?>



		<?php

				if(isset($_SESSION['zalogowany']))
				{
					echo '<section id="main" class="wrapper">
					    	<div class="inner">
					     	 <div class="content">
					        	<div class="row"> 
					        		<div class="col-6 col-12-medium">';
					    echo'<div class="author">
									<div class="image">
										<img src="'.sprawdz_zdjecie($conn).'" alt="" />
									</div>
									<p class="credit">- <strong>'.$_SESSION['user'].'</strong></p>
								</div>';
						    	echo '<form role="form" class="form" action="'.zmien_zdjecie_profilowe($conn).'" method="POST" enctype="multipart/form-data">		
										<div class="custom-file">	
											<input type="file" class="custom-file-input" name="postImage" value="">
											<label class="custom-file-label" for="customFile">Wybierz zdjęcie</label>
											<br/><br/>
											<input name="submit-profilowe" type="submit" class="fadeIn fourth" value="Zmień profilowe">
										</div>
										</form>
										<form action="dodaj_kategorie.php" method="post">
										    <input type="submit" class="fadeIn fourth" value="Dodaj / usuń kategorie">
										</form>	
								    	<form action="dodaj_prod.php" method="post">
										    <input type="submit" class="fadeIn fourth" value="Dodaj wpis">
										</form>

								    	<form action="logout.php" method="post">
										    <input type="submit" class="primary" value="Wyloguj się">
										</form>
						   			 </div>
							     </div>
						      </div>
						    </div>
						  </section>';
				}
            }

        mysqli_close($conn);

      ?>

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