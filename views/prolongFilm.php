<?php
require('../app/db.php');
include('partials/isLoggedIn.php');

if(isset($_GET['film']) && !empty($_GET['film'])) {
  $slug = $db->real_escape_string($_GET['film']);
  //select film data
  $film = $db->query("SELECT title, finish FROM films WHERE slug='{$slug}'");
  if (!$film || $film->num_rows != 1) {
    $film = null;
  } else {
    $film = mysqli_fetch_object($film);
  }
}
$_SESSION['oldTo'] = $film->finish;

include('partials/header.php');
 ?>

 <div class="row">
   <h1 class="text-center">Moje Kino</h1>
   <h4 class="text-center">Przedłuż projekcję filmu</h4>
 </div>
 <div class="row">
   <?php if (empty($film)): ?>
     <h2 class="text-center">Nie zanaleziono filmu</h2>
   <?php else: ?>
     <h3 class="text-center"><strong><?php echo htmlspecialchars($film->title); ?></strong></h3>
     <p class="text-center">Obecna data zakończenia wyświetlania filmu:</p>
     <p class="text-center"><strong><?php echo htmlspecialchars($film->finish); ?></strong></p>
 <form class="col-md-offset-4 col-md-4" action="../app/admin/prolongFilm.php?film=<?php echo htmlspecialchars($slug); ?>" method="post">
   <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
     <label class="control-label">Nowa data zakończenia seansów</label>
     <input type="date" name="newTo" class="form-control" value="<?php if (isset($_SESSION['newTo'])) echo htmlspecialchars($_SESSION['newTo']); ?>">
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
 //delete session data
 unset($_SESSION['newTo']);
 unset($_SESSION['check_list']);
include('partials/footer.php');
