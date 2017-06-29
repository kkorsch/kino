<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Kino</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <?php if(isset($_SESSION['flash'])): ?>
          <h4 class="text-center text-danger"><?php echo $_SESSION['flash']; ?></h4>
        <?php endif; ?>
        <nav >

          <a href="home" class="pull-left btn btn-primary">Strona główna</a>
          <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true): ?>
            <a href="#" class="pull-right btn btn-sm btn-warning">Wyloguj</a>
            <a href="Admin" class="pull-right btn btn-sm btn-warning">Admin panel</a>
            <p class="text-center pull-right">Witaj <?php echo $_SESSION['admin']; ?>   .</p>

          <?php else: ?>
          <a class="pull-right btn btn-sm btn-warning" href="Auth">Log in</a>
        <?php endif; ?>
        </nav>
      </div>
