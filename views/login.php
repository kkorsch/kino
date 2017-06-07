<?php
session_start();

if (isset($_SESSION['loggedIn'])) {
    header("Location: home.php");
}

include('partials/header.php');
?>

      <div class="row">
        <h1 class="text-center">Moje Kino</h1>
        <h4 class="text-center">Zaloguj się</h4>
        <form class="col-md-offset-4 col-md-3" action="../app/loggingIn.php" method="post">
          <div class="form-group <?php if (isset($_SESSION['loginError'])) echo 'has-error'; ?>">
            <?php if (isset($_SESSION['loginError'])): ?>
              <span class="help-block"><?php echo $_SESSION['loginError']; ?></span>
            <?php endif; ?>
            <label class="control-label">Nazwa użytkownika</label>
            <input type="text" name="username" class="form-control">
          </div>
          <div class="form-group <?php if (isset($_SESSION['loginError'])) echo 'has-error'; ?>">
            <label class="control-label">Hasło</label>
            <input type="password" name="password" class="form-control">
          </div>
          <input type="submit" value="Zaloguj" class="btn btn-primary">
        </form>
      </div>

<?php
unset($_SESSION['loginError']);
include('partials/footer.php');
