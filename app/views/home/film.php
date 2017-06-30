 <div class="row">
   <h1 class="text-center">Moje Kino</h1>
   <br>
    <?php if (empty($data['film'])): ?>
      <h2 class="text-center">Nie znaleziono filmu</h2>
    <?php else: ?>
      <h3 class="text-center"> <strong><?php echo htmlspecialchars($data['film']->title); ?></strong> </h3>
      <hr>
      <h4 class="text-center"> <strong>Opis</strong> </h4>
      <p class="text-center"><?php echo htmlspecialchars($data['film']->description); ?></p>
      <hr>
      <h4 class="text-center">Rezerwacja</h4>
        <?php if (!empty($data['days'])): ?>
          <div class="col-md-offset-5 col-md-2">
            <label class="control-label">Dni</label><br>
            <?php foreach ($data['days'] as $day): ?>
              <a  href="<?php echo constant("URL"); ?>/Reservation/shows/<?php echo htmlspecialchars($data['film']->id).'/'.htmlspecialchars($day); ?>" class="btn btn-default"><?php echo $day; ?></a>
            <?php endforeach; ?>
          </div>
      <?php else: ?>
        <p class="text-center">Brak seansów</p>
      <?php endif;
    endif; ?>
 </div>
