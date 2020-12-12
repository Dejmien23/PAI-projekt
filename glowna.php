<section class="wrapper">
  <div class="inner">
    <header class="special">
      <h2>Najnowsze foty</h2>
      <p>Ostatnio dodane wpisy</p>
    </header>
    <div class="highlights">

    <?php

      $sql3 = 'SELECT `id`, `nazwa`, `opis`, `img`
               FROM `produkty`
               ORDER BY `id` DESC LIMIT 3';

      $wynik3 = mysqli_query($conn, $sql3);

      if (mysqli_num_rows($wynik3) > 0) 
      {
        while ($wiersz = @mysqli_fetch_array($wynik3))
        {
          $id = $wiersz['id'];
          $img = $wiersz['img'];
          $nazwa = $wiersz['nazwa'];
          $opis = $wiersz['opis'];


          echo'<section>
              <div class="content">
                <header>
                  <h3>'.$nazwa.'</h3>';
                  if((isset($_SESSION['admin'])) && ($_SESSION['admin']==true))
                      {
                          echo"<form style='float: left;' method='post' action='edytuj_prod.php'>
                            <input type='hidden' name='id' value='".$id."' >
                            <input type='hidden' name='tytul' value='".$nazwa."' >
                            <input type='hidden' name='opis' value='".$opis."' >
                            <button style='box-shadow: none;'class='button small' type='submit'> <i class='fa fa-cog'></i>&nbsp;Edytuj</button>
                          </form>";
                          echo"<form style='float: right;' method='post' action='usun_prod.php'>
                            <input type='hidden' name='id' value='".$id."' >
                            <button style='box-shadow: none;' class='button small' type='submit'><i class='fa fa-trash' aria-hidden='true'></i>&nbsp;Usuń</button>
                          </form>";
                      }
              echo'<a class="example-image-link" href="'.$img.'" data-lightbox="example-set"><img width="100%" src="'.$img.'"></a>
                </header>
                <button style="box-shadow: none;" type="button" class="button small" data-toggle="collapse" data-target="#tekst'.$id.'">Przeczytaj...</button>
                  <div id="tekst'.$id.'" class="collapse">'.$opis.'</div>
              </div>
            </section>'; 
        }
      }

    ?>

    </div>
  </div>
</section>