<?php
require('../app/db.php');

$today = date('Y-m-d');
$hour = date('H');

//deleteing old shows from database
$db->query("DELETE FROM shows WHERE show_date<'{$today}'");
$db->query("DELETE FROM shows WHERE show_date='{$today}' AND hour<'{$hour}'");

//delete session seats
unset($_SESSION['seats']);

//validation
if (isset($_GET['film'], $_GET['date']) && !empty($_GET['film']) && !empty($_GET['date'])) {
  $id = $db->real_escape_string($_GET['film']);
  $date = $db->real_escape_string($_GET['date']);

  if ($date > date('Y-m-d', strtotime($today.'+1week')) || $date < $today) {
    $_SESSION['flash'] = "Wystąpił bład.";
    header("Location: home.php");
  }

  //selecting shows of the day
  if ($date == $today) {
    $shows = $db->query("SELECT hour FROM shows WHERE film_id='{$id}' AND show_date='{$date}' AND hour>'{$hour}'");
  } else {
    $shows = $db->query("SELECT hour FROM shows WHERE film_id='{$id}' AND show_date='{$date}'");

  }
  $shows = mysqli_fetch_all($shows, MYSQLI_ASSOC);
}

include('partials/header.php');

 ?>

      <div class="row">
        <h1 class="text-center">Moje Kino</h1>
        <h4 class="text-center">Rezerwacja</h4>
      </div>
      <div class="row">
      <?php if (!empty($shows)): ?>
      <form class="col-md-offset-5 col-md-4" action="hall.php" method="get">
        <input type="hidden" name="film" value="<?php echo $id; ?>">
        <input type="hidden" name="date" value="<?php echo $date; ?>">

        <div class="form-group">
          <label class="control-label">Wybierz godzinę</label>
          <br>
          <?php foreach ($shows as $h): ?>
            <button class="btn btn-default" type="radio" name="h" value="<?php echo $h['hour']; ?>"><?php echo $h['hour']; ?>:00</button>
          <?php endforeach; ?>
        </div>

      </form>
      <?php endif; ?>
      </div>

<?php
include('partials/footer.php');
