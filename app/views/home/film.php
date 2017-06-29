<?php
include('../app/views/partials/header.php');
 ?>

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
        <form class="col-md-offset-5 col-md-3" action="shows.php" method="get">
          <input type="hidden" name="film" value="<?php echo $data['film']->id; ?>">
          <div class="form-group">
            <label class="control-label">Dni</label>
            <?php foreach ($data['days'] as $day): ?>
              <button type="radio" name="date" value="<?php echo date('Y-m-d', strtotime($day)); ?>"class="form-control btn btn-default"><?php echo $day; ?></button>
            <?php endforeach; ?>
          </div>
        </form>
      <?php else: ?>
        <p class="text-center">Brak seansów</p>
      <?php endif;
    endif; ?>
 </div>

<?php
include('../app/views/partials/footer.php');
