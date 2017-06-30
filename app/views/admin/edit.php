 <div class="row">
   <h1 class="text-center">Moje Kino</h1>
   <h4 class="text-center">Edytuj film</h4>
 </div>
 <div class="row">
   <?php if (!$data): ?>
     <h2 class="text-center">Nie zanaleziono filmu</h2>
   <?php else: ?>
 <form class="col-md-offset-4 col-md-4" action="editingFilm/<?php echo htmlspecialchars($data->slug); ?>" method="post">
   <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
     <label class="control-label">Tytuł:</label>
     <input type="text" name="title" class="form-control" value="<?php echo isset($_SESSION['title']) ?  htmlspecialchars($_SESSION['title']) :  htmlspecialchars($data->title); ?>">
   </div>
   <div class="form-group <?php if (isset($_SESSION['flash'])) echo 'has-error'; ?>">
     <label class="control-label">Opis:</label>
     <textarea class="form-control" name="description" rows="8" cols="50"  ><?php echo isset($_SESSION['description']) ?  htmlspecialchars($_SESSION['description']) :  htmlspecialchars($data->description); ?></textarea>
   </div>
   <input type="submit" value="Edytuj" class="btn btn-danger">
 </form>
 <?php endif; ?>
 </div>

 <?php
 //delete session data
 unset($_SESSION['title']);
 unset($_SESSION['description']);
