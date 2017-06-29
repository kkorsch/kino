<?php
include('../app/views/partials/header.php');
 ?>

      <div class="row">
        <h1 class="text-center">Moje Kino</h1>
        <h4 class="text-center">Repertuar</h4>
        <br><br>
        <?php if ($data): ?>
        <ul class="list-group col-md-offset-5 col-md-3">
          <?php foreach($data as $film):  ?>
              <a href="home/film/<?php echo htmlspecialchars($film['slug']); ?>" class="list-group-item list-group-item-info" type="submit" name="film"><?php echo htmlspecialchars($film['title']); ?></a>
              <hr>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <h3 class="text-center">Brak filmów</h3>
      <?php endif; ?>
      </div>
<?php
  include('../app/views/partials/footer.php');
