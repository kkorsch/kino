<div class="row">
  <h1 class="text-center">Moje Kino</h1>
  <h4 class="text-center">Rezerwacja</h4>
</div>
<div class="row">
<?php if (!empty($data['shows'])): ?>

  <div class="col-md-offset-5 col-md-3">
    <label class="control-label">Wybierz godzinę</label>
    <br>
    <?php foreach ($data['shows'] as $h): ?>
      <a href="<?php echo constant("URL"); ?>/Reservation/hall/<?php echo htmlspecialchars($data['film']).'/'.htmlspecialchars($data['date']).'/'.htmlspecialchars($h['hour']); ?>" class="btn btn-default" ><?php echo $h['hour']; ?>:00</a>
    <?php endforeach; ?>
  </div>

<?php else: ?>
<h5 class="text-center">Brak seansów</h5>
<?php endif; ?>
</div>
