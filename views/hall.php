<?php
require('../app/db.php');

//validation get data
if (isset($_GET['film'], $_GET['date'], $_GET['h']) && !empty($_GET['film']) && !empty($_GET['date']) && !empty($_GET['h'])) {
  //prapare data
  $id = $db->real_escape_string($_GET['film']);
  $date = $db->real_escape_string($_GET['date']);
  $h = $db->real_escape_string($_GET['h']);

  if ($date > date('Y-m-d', strtotime(date('Y-m-d').'+1week')) ) {
    $_SESSION['flash'] = "Wystąpił bład.";
    header("Location: home.php");
  }

  //select details and reservations of current show
  $show = $db->query("SELECT id FROM shows WHERE film_id='{$id}' AND show_date='{$date}' AND hour='{$h}'");
  $show = mysqli_fetch_object($show);
  $reservations = $db->query("SELECT seat FROM reservations WHERE show_id='{$show->id}'");
  $reservations = mysqli_fetch_all($reservations, MYSQLI_ASSOC);
  $seats = [];

  foreach ($reservations as $k) {
    $seats[] = $k['seat'];
  }

  //create seats list
  if (!isset($_SESSION['seats'])) {
    $_SESSION['seats'] = [];
  }

  //adding seat
  if (isset($_POST['seat']) && !empty($_POST['seat']) && !in_array($_POST['seat'], $_SESSION['seats']) && count($_SESSION['seats']) < 6) {
    $_SESSION['seats'][$_POST['seat']] = $_POST['seat'];
    ksort($_SESSION['seats']);
    unset($_POST['seat']);
  }

  //deleting seat
  if (isset($_POST['delete']) && !empty($_POST['delete'])) {
      unset($_SESSION['seats'][$_POST['delete']]);
      unset($_POST['delete']);
  }

}
include('partials/header.php');
 ?>

 <link rel="stylesheet" href="css/style.css">
 <div class="row">
   <h1 class="text-center">Moje Kino</h1>
   <br>
   <h4 class="col-md-offset-2">Ekran</h4>
   <form class="col-md-6" action="#" method="post">
     <div class="audience-container">
       <?php for ($i = 1; $i <= 50; $i++): ?>
       <button type="submit" class="btn btn-<?php if (in_array($i, $_SESSION['seats'])) {echo 'warning'; } elseif (in_array($i, $seats)) { echo 'danger'; } else {echo 'default';} ?>" name="seat" value="<?php echo $i; ?>" <?php if (in_array($i, $_SESSION['seats']) || in_array($i, $seats)) echo 'disabled'; ?> ><?php echo $i; ?></button>
     <?php endfor; ?>
     </div>
   </form>
   <div class="col-md-offset-8 ">
     <?php if (count($_SESSION['seats']) >= 6): ?>
     <h4 class="text-danger"><strong>Osiągnieto maksymalna ilość miejsc!</strong></h4>
   <?php endif; ?>
     <h4><strong>Wybrane miejsca:</strong></h4>
     <?php if (!empty($_SESSION['seats'])): $i =0;?>
       <form action="#" method="post">
       <ul  class="list-group">
            <?php foreach ($_SESSION['seats'] as $m):?>
       <li class="list-group-item">Miejsce nr <?php echo $m; ?><button class="pull-right btn btn-xs btn-danger" type="submit" name="delete" value="<?php echo $m; ?>">Usuń</button></li>
     <?php
     $i++;
   endforeach; ?>
   </ul>
   </form>
     <?php else: ?>
       <p>Brak</p>
     <?php endif; ?>

     <?php if (!empty($_SESSION['seats'])): ?>
       <form action="../app/booking.php" method="post">
         <input type="hidden" name="film" value="<?php echo $id; ?>">
         <input type="hidden" name="date" value="<?php echo $date; ?>">
         <input type="hidden" name="h" value="<?php echo $h; ?>">
         <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
           <label class="control-label">Nazwisko:</label>
           <input type="text" name="name" class="form-control">
         </div>
         <input type="submit" value="Zatwierdź" class="btn btn-primary">
       </form>
     <?php endif; ?>
   </div>
 </div>

<?php
include('partials/footer.php');
