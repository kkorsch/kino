<?php
require('../db.php');
include('partials/isLoggedIn.php');

//validation
if (empty($_GET['film']) || empty($_POST['newTo']) || empty($_POST['check_list'])) {
  if (!empty($_POST['newTo'])) {
    $_SESSION['newTo'] = $_POST['newTo'];
  }
  if (!empty($_POST['check_list'])) {
    $_SESSION['check_list'] = $_POST['check_list'];
  }

  $_SESSION['flash'] = "Żadne pole nie może pozostać puste!";
  $film = htmlspecialchars($_GET['film']);
  header("Location: ../../views/prolongFilm.php?film={$film}");
} else {
  //prapare data
  $slug = $db->real_escape_string($_GET['film']);
  $newTo = $db->real_escape_string($_POST['newTo']);
  $shows = $_POST['check_list'];

  //date validation
  if ($newTo < $_SESSION['oldTo'] || $newTo == $_SESSION['oldTo'] || $newTo > date('Y-m-d', strtotime($_SESSION['oldTo'].'+1month')) ) {
    if (!empty($_POST['check_list'])) {
      $_SESSION['check_list'] = $_POST['check_list'];
    }

    $_SESSION['flash'] = "Nowa data musi być w ciągu miesiąca od obecnej daty zakończenia!";
    header("Location: ../../views/prolongFilm.php?film={$slug}");
    die();
  }

  //check if film exists
  $check = $db->query("SELECT id FROM films WHERE slug='{$slug}'");

  if ($check) {
    //update 'to' date in database
    $save = $db->query("UPDATE films SET finish='{$newTo}' WHERE slug='{$slug}'");
    if ($save) {
      $check = mysqli_fetch_assoc($check);
      $id = $check['id'];

      $days = (strtotime($newTo)-strtotime($_SESSION['oldTo']))/86400;

      //adding shows to database
      for ($i=1; $i<=$days; $i++) {
        $day = date('Y-m-d', strtotime($_SESSION['oldTo'].'+'.$i.'days'));
        foreach ($shows as $h) {
          $db->query("INSERT INTO shows(film_id, hour, show_date) VALUES ('{$id}', '{$h}', '{$day}')");
        }
      }

      $_SESSION['flash'] = 'Data została zmieniona!';
      }
    } else {
      $_SESSION['flash'] = 'Wystąpił bład podczas edytowania.';
    }
  header("Location: ../../views/editFilms.php");

}
