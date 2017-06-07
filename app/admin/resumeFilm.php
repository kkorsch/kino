<?php
require('../db.php');
include('partials/isLoggedIn.php');

if (!empty($_GET['film'])) {
  $slug = $db->real_escape_string($_GET['film']);
  $today = date('Y-m-d');
  $nextMonth = date('Y-m-d', strtotime($today.'+1month'));

  //validtion
  if (empty($_POST['since']) || empty($_POST['to']) || empty($_POST['check_list'])) {
    include('partials/savePostData.php');
    $_SESSION['flash'] = "Żadne pole nie może pozostać puste!";
    header("Location: ../../views/wznowFilm.php?film={$slug}");
  } elseif ($_POST['since'] < $today || $_POST['since'] > $nextMonth) {
    include('partials/savePostData.php');
    $_SESSION['flash'] = 'Data Rozpoczęcia musi być w ciągu najbliższego miesiąca!';
    header("Location: ../../views/wznowFilm.php?film={$slug}");
  } elseif ($_POST['to'] < $today || $_POST['to'] < $_POST['since'] || $_POST['to'] > date('Y-m-d', strtotime($_POST['since'].'+1month')) ) {
    include('partials/savePostData.php');
    $_SESSION['flash'] = 'Data Zakończenia może być maksymalnie miesiąc od Rozpoczęcia!';
    header("Location: ../../views/wznowFilm.php?film={$slug}");
  } else {

    //prapare data
    $since = $db->real_escape_string($_POST['since']);
    $to = $db->real_escape_string($_POST['to']);
    $shows = $_POST['check_list'];


    //check if film exists
    $check = $db->query("SELECT id FROM films WHERE slug='{$slug}'");

    if ($check) {
      //update film in database
      $save = $db->query("UPDATE films SET start='{$since}', finish='{$to}' WHERE slug='{$slug}'");
      if ($save) {
        $check = mysqli_fetch_assoc($check);
        $id = $check['id'];

        $days = (strtotime($to)-strtotime($since))/86400;

        //adding shows to database
        for ($i=0; $i<=$days; $i++) {
          $day = date('Y-m-d', strtotime($since.'+'.$i.'days'));
          foreach ($shows as $h) {
            $db->query("INSERT INTO shows(film_id, hour, show_date) VALUES ('{$id}', '{$h}', '{$day}')");
          }
        }

        $_SESSION['flash'] = 'Film został wznowiony!';
        header("Location: ../../views/admin.php");
      }
    }
  }
}
