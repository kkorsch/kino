<?php
require('../app/db.php');
include('partials/isLoggedIn.php');

if(isset($_GET['film']) && !empty($_GET['film'])) {
  $slug = $db->real_escape_string($_GET['film']);
  //select film data
  $film = $db->query("SELECT title FROM films WHERE slug='{$slug}'");
  if (!$film || $film->num_rows != 1) {
    $film = null;
  } else {
    $film = mysqli_fetch_object($film);
  }
}

include('partials/header.php');
 ?>

 <div class="row">
   <h1 class="text-center">Moje Kino</h1>
   <h4 class="text-center">Wznów projekcję filmu</h4>
 </div>
 <div class="row">
   <?php if (empty($film)): ?>
     <h2 class="text-center">Nie zanaleziono filmu</h2>
   <?php else: ?>
     <h3 class="text-center"><strong><?php echo htmlspecialchars($film->title); ?></strong></h3>
 <form class="col-md-offset-4 col-md-4" action="../app/admin/resumeFilm.php?film=<?php echo htmlspecialchars($slug); ?>" method="post">
   <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
     <label class="control-label">Data rozpoczęcia seansów</label>
     <input type="date" name="since" class="form-control">
   </div>
   <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
     <label class="control-label">Data zakończenia seansów</label>
     <input type="date" name="to" class="form-control">
   </div>
   <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
     <label class="control-label">Godziny seansów:</label>
     <br>
     <?php for ($x=9; $x<23; $x++): ?>
       <input type="checkbox" name="check_list[]" value="<?php echo $x; ?>" <?php if (isset($_SESSION['check_list'])) if (in_array($x, $_SESSION['check_list'])) echo 'checked'; ?>><?php echo $x; ?>
     <?php endfor; ?>
   </div>
   <input type="submit" value="Edytuj" class="btn btn-danger">
 </form>
 <?php endif; ?>
 </div>

 <?php
 unset($_SESSION['since']);
 unset($_SESSION['to']);
 unset($_SESSION['check_list']);

include('partials/footer.php');
