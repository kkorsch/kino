      <div class="row">
        <h1 class="text-center">Moje Kino</h1>
        <h4 class="text-center">Lista filmów</h4>
      </div>
      <div class="row">
        <?php if (!$data): ?>
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
              <?php foreach ($data as $film): ?>
                <tr>
                  <td><?php echo htmlspecialchars($film['title']); ?></td>
                  <td><?php echo htmlspecialchars($film['start']); ?></td>
                  <td><?php echo htmlspecialchars($film['finish']); ?></td>

                  <?php if ($film['finish'] < date('Y-m-d')): ?>
                  <td><a href="resumeFilm.php?film=<?php echo htmlspecialchars($film['slug']); ?>" class="btn btn-danger">Przywróć seanse</a></td>
                <?php else: ?>
                  <td><a href="prolongFilm.php?film=<?php echo htmlspecialchars($film['slug']); ?>" class="btn btn-warning">Przedłuż film</a></td>
                <?php endif; ?>
                  <td><a href="edit/<?php echo htmlspecialchars($film['slug']); ?>" class="btn btn-primary">Edytuj tytuł/opis</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
      </div>
