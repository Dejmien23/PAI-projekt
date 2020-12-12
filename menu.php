<nav id="menu">
  <ul class="links">
    <li><a href="index.php">Home</a></li>
    <li><a href="#" class="dropdown-btn">Kategorie <i class="fa fa-caret-down"></i></a>
      <div class="dropdown-container">
      <?php
              @mysqli_query($conn, 'SET NAMES utf8');

              $sqll = 'SELECT `id`, `nazwa`
                           FROM `kategorie`
                           ORDER BY `nazwa`';
              $wynikk = mysqli_query($conn, $sqll);
              if (mysqli_num_rows($wynikk) > 0) {
                while ($kategoria = @mysqli_fetch_array($wynikk)) {
                  echo '<a class="dropdown-item" href="' . $_SERVER["PHP_SELF"] . '?kat_id=' . $kategoria['id'] . '">' . $kategoria['nazwa'] . '</a>' . PHP_EOL;
                }
              }
            ?>
    </div>
    </li>

    <li><a href="kontakt.php">Kontakt</a></li>

    <li><a href="logowanie.php">
      <?php
        if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
        {
          echo $_SESSION['user'];
        }else{
          echo "Zaloguj się";
        }
      ?>
    </a></li>
  </ul>
</nav>