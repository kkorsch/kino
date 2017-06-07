<?php
session_start();
include('partials/isLoggedIn.php');
include('partials/header.php');
?>

      <div class="row">
        <h1 class="text-center">Moje Kino</h1>
        <h4 class="text-center">Usuń admina</h4>
        <form class="col-md-offset-4 col-md-3" action="../app/admin/deletingAdmin.php" method="post">
          <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
            <label class="control-label">Nazwa admina</label>
            <input type="text" name="username" class="form-control">
          </div>
          <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
            <label class="control-label">Podaj swoje hasło</label>
            <input type="password" name="password" class="form-control">
          </div>
          <input type="submit" value="Dodaj" class="btn btn-primary">
        </form>
      </div>

<?php
include('partials/footer.php');
