<?php
require('../app/db.php');

//selecting active films from database
$today = date('Y-m-d');
$nextWeek = date('Y-m-d', strtotime($today.'+1week'));
$films = $db->query("SELECT title, slug FROM films WHERE finish>='{$today}' and start<='{$nextWeek}' ORDER BY start DESC");
if ($films) $films = mysqli_fetch_all($films, MYSQLI_ASSOC);

include('partials/header.php');
 ?>

      <div class="row">
        <h1 class="text-center">Moje Kino</h1>
        <h4 class="text-center">Repertuar</h4>
        <br><br>
        <?php if ($films): ?>
        <ul class="list-group">
          <?php foreach($films as $film):  ?>
            <form class="col-md-offset-5 col-md-3" action="film.php" method="get">
              <button class="list-group-item list-group-item-info" type="submit" name="film" value="<?php echo htmlspecialchars($film['slug']); ?>"><?php echo htmlspecialchars($film['title']); ?></button>
              <hr>

            </form>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <h3 class="text-center">Brak filmów</h3>
      <?php endif; ?>
      </div>
<?php
  include('partials/footer.php');
