<?php
require('../db.php');
include('partials/isLoggedIn.php');

//validation
  if (empty($_POST['username']) || empty($_POST['password']) ) {
    $_SESSION['flash'] = "Żadne pole nie może pozostać puste!";
  } else {
    $user = $db->query("SELECT password FROM users WHERE username='{$_SESSION['admin']}'");
    if (!$user) {
      $_SESSION['flash'] = "Bład krytyczny";
      header("Location: ../../views/index.php");
      die();
    }
    //preapare data
    $password = $_POST['password'];
    $user = mysqli_fetch_object($user);
    $correctPassword = $user->password;

    //check if password matches
    if (!password_verify($password, $correctPassword)) {
      $_SESSION['flash'] = "Niepoprawne hasło";
    } else {
      $username = $db->real_escape_string($_POST['username']);

      //check if user we want to delete exists
      $check = $db->query("SELECT id FROM users WHERE username='{$username}'");

      if (!$check->num_rows) {
        $_SESSION['flash'] = "Nie ma admina o tej nazwie!";
      } else {
        //deleting user
        $delete = $db->query("DELETE FROM users WHERE username='{$username}'");
        if ($delete) {
          $_SESSION['flash'] = "Usunięto admina!";
          header("Location: ../../views/admin.php");
          die();
        }
        $_SESSION['flash'] = "Wystąpił bład, spróbuj ponownie.";
      }
    }
  }
  header("Location: ../../views/deleteAdmin.php");
