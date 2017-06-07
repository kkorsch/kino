<?php
require('../app/db.php');
include('partials/isLoggedIn.php');

//select films from database
$films = $db->query("SELECT title, start, finish, slug FROM films ORDER BY start DESC");
if (!$films->num_rows) {
  $films = null;
} else {
  $films = mysqli_fetch_all($films, MYSQLI_ASSOC);
}
$today = date('Y-m-d');

include('partials/header.php');
 ?>

      <div class="row">
        <h1 class="text-center">Moje Kino</h1>
        <h4 class="text-center">Lista filmów</h4>
      </div>
      <div class="row">
        <?php if (empty($films)): ?>
          <h2 class="text-center">Brak filmów w bazie danych</h2>
        <?php else: ?>
        <div class="col-md-offset-2 col-md-8">
          <table class="table">
            <thead>
              <th>Tytuł</th>
              <th>Od</th>
              <th>Do</th>
            </thead>
            <tbody>
              <?php foreach ($films as $film): ?>
                <tr>
                  <td><?php echo htmlspecialchars($film['title']); ?></td>
                  <td><?php echo htmlspecialchars($film['start']); ?></td>
                  <td><?php echo htmlspecialchars($film['finish']); ?></td>

                  <?php if ($film['finish'] < $today): ?>
                  <td><a href="resumeFilm.php?film=<?php echo htmlspecialchars($film['slug']); ?>" class="btn btn-danger">Przywróć seanse</a></td>
                <?php else: ?>
                  <td><a href="prolongFilm.php?film=<?php echo htmlspecialchars($film['slug']); ?>" class="btn btn-warning">Przedłuż film</a></td>
                <?php endif; ?>
                  <td><a href="editFilm.php?film=<?php echo htmlspecialchars($film['slug']); ?>" class="btn btn-primary">Edytuj tytuł/opis</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
      </div>
<?php
include('partials/footer.php');
