<?php
require('db.php');

//validation
if (empty($_SESSION['seats']) || empty($_POST['name'])) {
  $_SESSION['flash'] = 'Żadne pole nie może pozostać puste!';
  header("Location: ../views/hall.php?film={$_POST['film']}&data={$_POST['date']}&h={$_POST['h']}");
} elseif (count($_SESSION['seats']) > 6) {
  $_SESSION['flash'] = 'Nieprawidłowa ilość miejsc.';
  header("Location: ../views/hall.php?film={$_POST['film']}&data={$_POST['date']}&h={$_POST['h']}");
} else {
  //prepare data
  $id = $db->real_escape_string($_POST['film']);
  $date = $db->real_escape_string($_POST['date']);
  $h = $db->real_escape_string($_POST['h']);
  $name = $db->real_escape_string($_POST['name']);
  $seats = $_SESSION['seats'];

  if ($date > date('Y-m-d', strtotime(date('Y-m-d').'+1week')) ){
    $_SESSION['flash'] = "Wystąpił bład.";
    header("Location: ../views/home.php");
    die();
  }

  //check if show exists
  $showCheck = $db->query("SELECT id FROM shows WHERE film_id='{$id}' AND show_date='{$date}' AND hour='{$h}'");

  if ($showCheck) {
    //check if chosen seats are not already taken
    $seatsCheck = [];
    $show = mysqli_fetch_object($showCheck);

    foreach ($seats as $m) {
      $x = $db->query("SELECT id FROM reservations WHERE seat='{$m}' AND show_id='{$show->id}'");
      if ($x->num_rows) $seatsCheck[] = $x;
    }

    if (!empty($seatsCheck)) {
      $_SESSION['flash'] = 'Wystąpił bład. Spróbuj ponownie.';
      header("Location: ../views/home.php");
      die();
    }

    //seats reservating (adding to database)
    foreach ($seats as $m) {
      $db->query("INSERT INTO reservations(show_id, seat, name) VALUES ('{$show->id}', '{$m}', '{$name}')");
    }
    //delete session
    unset($_SESSION['seats']);

    $_SESSION['end'] = "Rezerwacja na nazwisko '{$name}'!";
    header("Location: ../views/thanks.php");
  } else {
    $_SESSION['flash'] = 'Wystąpił bład. Spróbuj ponownie.';
    header("Location: ../views/home.php");
    die();
  }
}
