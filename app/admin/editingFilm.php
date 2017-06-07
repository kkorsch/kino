<?php
require('../db.php');
include('partials/isLoggedIn.php');
include('slugConvert.php');

//validation
if (empty($_POST['title']) || empty($_POST['description'])) {
  if(!empty($_POST['title'])) {
    $_SESSION['title'] = $_POST['title'];
  }
  if (!empty($_POST['description'])) {
    $_SESSION['description'] = $_POST['description'];
  }
  $film = htmlspecialchars($_GET['film']);

  $_SESSION['flash'] = "Żadne pole nie może pozostać puste!";
  header("Location: ../../views/editFilm.php?film={$film}");
} elseif (strlen($_POST['title']) > 250) {
  if(!empty($_POST['title'])) {
    $_SESSION['title'] = $_POST['title'];
  }
  if (!empty($_POST['description'])) {
    $_SESSION['description'] = $_POST['description'];
  }
  $film = htmlspecialchars($_GET['film']);

  $_SESSION['flash'] = 'Tytuł może mieć maksymalnie 250 znaków!';
  header("Location: ../../views/editFilm.php?film={$film}");
} else {
  //prepare data
  $slug = $db->real_escape_string($_GET['film']);

  $title = $db->real_escape_string($_POST['title']);
  $description = $db->real_escape_string($_POST['description']);

  //check if film exists
  $check = $db->query("SELECT id FROM films WHERE slug='{$slug}'");

  if ($check) {
    //zaktualizowanie danych filmu w bazie
    $save = $db->query("UPDATE films SET title='{$title}', description='{$description}' WHERE slug='{$slug}'");
    if ($save) {
      $_SESSION['flash'] = 'Film został zedytowany!';
      }
    } else {
      $_SESSION['flash'] = 'Wystąpił bład podczas edytowania.';
    }
  header("Location: ../../views/editFilms.php");

}
