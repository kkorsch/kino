<?php
require('../app/db.php');

include('partials/header.php');
 ?>

      <div class="row">
        <h1 class="text-center">Moje Kino</h1>

        <h3 class="text-center">Rezerwacja zakończona</h3>
        <h4 class="text-center"><?php echo $_SESSION['end']; ?></h4>
      </div>
<?php
unset($_SESSION['end']);
  include('partials/footer.php');
