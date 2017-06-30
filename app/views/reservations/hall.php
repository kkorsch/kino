<style>
.audience-container {
    width: 500px;
    height: 500px;
}

.audience-container button {
    width: 7%;
    height: 7%;
    margin: 1%;
}
</style>
<div class="row">
  <h1 class="text-center">Moje Kino</h1>
  <br>
  <h4 class="col-md-offset-2">Ekran</h4>
  <form class="col-md-6" action="<?php echo constant("URL"); ?>/Reservation/hall/<?php echo htmlspecialchars($data['film']).'/'.htmlspecialchars($data['date']).'/'.htmlspecialchars($data['hour']); ?>" method="post">
    <div class="audience-container">
      <?php for ($i = 1; $i <= 50; $i++): ?>
      <button type="submit" class="btn btn-<?php if (in_array($i, $_SESSION['seats'])) { echo 'warning'; } elseif (in_array($i, $data['seats'])) { echo 'danger'; } else {echo 'default';} ?>" name="seat" value="<?php echo $i; ?>" <?php if (in_array($i, $_SESSION['seats']) || in_array($i, $data['seats'])) echo 'disabled'; ?> ><?php echo $i; ?></button>
    <?php  endfor; ?>
    </div>
  </form>
  <div class="col-md-offset-8 ">
    <?php if (count($_SESSION['seats']) >= 6): ?>
    <h4 class="text-danger"><strong>Osiągnieto maksymalna ilość miejsc!</strong></h4>
  <?php endif; ?>
    <h4><strong>Wybrane miejsca:</strong></h4>
    <?php if (!empty($_SESSION['seats'])): $i =0;?>
      <form action="<?php echo constant("URL"); ?>/Reservation/hall/<?php echo htmlspecialchars($data['film']).'/'.htmlspecialchars($data['date']).'/'.htmlspecialchars($data['hour']); ?>" method="post">
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
      <form action="<?php echo constant("URL"); ?>/Reservation/booking" method="post">
        <input type="hidden" name="film" value="<?php echo $data['film']; ?>">
        <input type="hidden" name="date" value="<?php echo $data['date']; ?>">
        <input type="hidden" name="h" value="<?php echo $data['hour']; ?>">
        <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
          <label class="control-label">Nazwisko:</label>
          <input type="text" name="name" class="form-control">
        </div>
        <input type="submit" value="Zatwierdź" class="btn btn-primary">
      </form>
    <?php endif; ?>
  </div>
</div>
