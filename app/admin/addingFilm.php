<?php
require('../db.php');
include('partials/isLoggedIn.php');
include('slugConvert.php');

$today = date('Y-m-d');
$nextMonth = date('Y-m-d', strtotime($today.'+1month'));

//validation
if (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['check_list']) || empty($_POST['since']) || empty($_POST['to'])) {
  include('partials/savePostData.php');
  $_SESSION['flash'] = "Żadne pole nie może pozostać puste!";
  header("Location: ../../views/addFilm.php");
} elseif ($_POST['since'] < $today || $_POST['since'] > $nextMonth) {
  include('partials/savePostData.php');
  $_SESSION['flash'] = 'Data Rozpoczęcia musi być w ciągu najbliższego miesiąca!';
  header("Location: ../../views/addFilm.php");
} elseif ($_POST['to'] < $today || $_POST['to'] < $_POST['since'] || $_POST['to'] > date('Y-m-d', strtotime($_POST['since'].'+1month')) ) {
  include('partials/savePostData.php');
  $_SESSION['flash'] = 'Data Zakończenia może być maksymalnie miesiąc od Rozpoczęcia!';
  header("Location: ../../views/addFilm.php");
} elseif (strlen($_POST['title']) > 250) {
  include('partials/savePostData.php');
  $_SESSION['flash'] = 'Tytuł może mieć maksymalnie 250 znaków!';
  header("Location: ../../views/addFilm.php");
} else {

  //prapare data
  $title = $db->real_escape_string($_POST['title']);
  $slug = slugify($title);
  $description = $db->real_escape_string($_POST['description']);
  $shows = $_POST['check_list'];
  $since = $db->real_escape_string($_POST['since']);
  $to = $db->real_escape_string($_POST['to']);

  //check if film already exists
  $check = $db->query("SELECT id FROM films WHERE slug='{$slug}'");

  if ($check->num_rows) {
    $_SESSION['flash'] = "Ten film został już dodany!";
    header("Location: ../../views/addFilm.php");
    die();
  }

  //adding film to database
  $save = $db->query("INSERT INTO films(title, slug, description, start, finish) VALUES ('{$title}', '{$slug}', '{$description}', '{$since}', '{$to}')");

  if ($save) {
    $film = $db->query("SELECT id FROM films WHERE slug='{$slug}'");
    $film = mysqli_fetch_assoc($film);
    $id = $film['id'];

    $days = (strtotime($to)-strtotime($since))/86400;

    //adding shows to database
    for ($i=0; $i<=$days; $i++) {
      $day = date('Y-m-d', strtotime($since.'+'.$i.'days'));
      foreach ($shows as $h) {
        $db->query("INSERT INTO shows(film_id, hour, show_date) VALUES ('{$id}', '{$h}', '{$day}')");
      }
    }
    
    $_SESSION['flash'] = 'Film został dodany!';
    header("Location: ../../views/admin.php");
  }
}
