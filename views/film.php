<?php
require('../app/db.php');

if (isset($_GET['film']) && !empty($_GET['film'])) {
  $slug = $db->real_escape_string($_GET['film']);
  $today = date('Y-m-d');
  $nextWeek = date('Y-m-d', strtotime($today.'+1week'));

  //check if film exists and select from database
  $film = $db->query("SELECT * FROM films WHERE slug='{$slug}' AND finish>='{$today}' AND start<='{$nextWeek}'");
  if ($film->num_rows) {
    $film = mysqli_fetch_object($film);
    $days = [];

    //list of show days
    if ($today < $film->start) {
      $daysCount = (strtotime($today.'+6days')-strtotime($film->start))/86400;
      $start = $film->start;
    } elseif ($today >= $film->start && $today < $film->finish) {
      $daysCount = (strtotime($today.'+6days')-strtotime($today))/86400;
      $start = $today;
    } else {
      $daysCount = 0;
      $start = $today;
    }

    for ($i=0; $i<=$daysCount; $i++) {
      $day = date('Y-m-d, l', strtotime($start.'+'.$i.'days'));
      $days[$i] = $day;
    }
  } else {
    $film = null;
  }
}
include('partials/header.php');
 ?>

 <div class="row">
   <h1 class="text-center">Moje Kino</h1>
   <br>
    <?php if (empty($film)): ?>
      <h2 class="text-center">Nie znaleziono filmu</h2>
    <?php else: ?>
      <h3 class="text-center"> <strong><?php echo htmlspecialchars($film->title); ?></strong> </h3>
      <hr>
      <h4 class="text-center"> <strong>Opis</strong> </h4>
      <p class="text-center"><?php echo htmlspecialchars($film->description); ?></p>
      <hr>
      <h4 class="text-center">Rezerwacja</h4>
        <?php if (!empty($days)): ?>
        <form class="col-md-offset-5 col-md-3" action="shows.php" method="get">
          <input type="hidden" name="film" value="<?php echo $film->id; ?>">
          <div class="form-group">
            <label class="control-label">Dni</label>
            <?php foreach ($days as $day): ?>
              <button type="radio" name="date" value="<?php echo $day; ?>"class="form-control btn btn-default"><?php echo $day; ?></button>
            <?php endforeach; ?>
          </div>
        </form>
      <?php else: ?>
        <p class="text-center">Brak seansów</p>
      <?php endif;
    endif; ?>
 </div>

<?php
include('partials/footer.php');
