<?php
require('../db.php');
include('partials/isLoggedIn.php');

$reservations = $db->query("SELECT show_id FROM reservations");
$reservations = mysqli_fetch_all($reservations);

$IDs = [];

foreach ($reservations as $r) {
  $IDs[] = $r['0'];
}

$IDs = array_unique($IDs);

foreach ($IDs as $id) {
  $x = $db->query("SELECT id FROM shows WHERE id='{$id}'");
  if (!$x->num_rows) $db->query("DELETE FROM reservations WHERE show_id='{$id}'");
}

$_SESSION['flash'] = "Rezerwacje wyczyszczone.";
header("Location: ../../views/admin.php");
