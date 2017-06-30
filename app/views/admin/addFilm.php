      <div class="row">
        <h1 class="text-center">Moje Kino</h1>
        <h4 class="text-center">Dodaj film</h4>
      </div>
      <div class="row">
      <form class="col-md-offset-4 col-md-4" action="addingFilm" method="post">
        <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
          <label class="control-label">Tytuł:</label>
          <input type="text" name="title" class="form-control" value="<?php if (isset($_SESSION['title'])) echo htmlspecialchars($_SESSION['title']); ?>">
        </div>
        <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
          <label class="control-label">Opis:</label>
          <textarea class="form-control" name="description" rows="8" cols="50"  ><?php if (isset($_SESSION['description'])) echo htmlspecialchars($_SESSION['description']); ?></textarea>
        </div>
        <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
          <label class="control-label">Data rozpoczęcia seansów</label>
          <input type="date" name="start" class="form-control" value="<?php if (isset($_SESSION['start'])) echo $_SESSION['start']; ?>">
        </div>
        <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
          <label class="control-label">Data zakończenia seansów</label>
          <input type="date" name="finish" class="form-control" value="<?php if (isset($_SESSION['finish'])) echo $_SESSION['finish']; ?>">
        </div>
        <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
          <label class="control-label">Godziny seansów:</label>
          <br>
          <?php for ($x=9; $x<23; $x++): ?>
            <input type="checkbox" name="check_list[]" value="<?php echo $x; ?>" <?php if (isset($_SESSION['check_list'])) if (in_array($x, $_SESSION['check_list'])) echo 'checked'; ?>><?php echo $x; ?>
          <?php endfor; ?>
        </div>

        <input type="submit" value="Dodaj" class="btn btn-danger">
      </form>
      </div>
<?php
//delete session data
unset($_SESSION['title']);
unset($_SESSION['description']);
unset($_SESSION['check_list']);
unset($_SESSION['start']);
unset($_SESSION['finish']);
