<?php
require('../db.php');
include('partials/isLogged.php');

//validation
  if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['password_again']) || empty($_POST['password_confirm'])) {
    $_SESSION['flash'] = "Żadne pole nie może pozostać puste!";
  } elseif ($_POST['password'] != $_POST['password_again']) {
    $_SESSION['flash'] = "Nowe hasło i powtorzone muszą być identyczne!";
  } elseif (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 30) {
    $_SESSION['flash'] = "Hasło musi mieć od 6 do 30 znaków!";
  } elseif (strlen($_POST['username']) < 3 || strlen($_POST['username']) > 30) {
    $_SESSION['flash'] = "Nazwa użytkownika musi mieć od 3 do 30 znaków!";
  } else {
    $password_confirm = $_POST['password_confirm'];

    //check if logged user exists and select data from database
    $user = $db->query("SELECT password FROM users WHERE username='{$_SESSION['admin']}'");
    if (!$user) {
      $_SESSION['flash'] = "Bład krytyczny";
      header("Location: ../../views/home.php");
      die();
    }

    $user = mysqli_fetch_object($user);

    //check if password matches
    if (!password_verify($password_confirm, $user->password)) {
      $_SESSION['flash'] = "Niepoprawne hasło";
    } else {
      $username = $db->real_escape_string($_POST['username']);

      //check if user we want to add already exists
      $check = $db->query("SELECT id FROM users WHERE username='{$username}'");

      if ($check->num_rows) {
        $_SESSION['flash'] = "Istnieje już admin o tej nazwie!";
      } else {
        $password = $db->real_escape_string($_POST['password']);
        $passwordHashed = password_hash($password, PASSWORD_BCRYPT);

        //adding user to database
        $save = $db->query("INSERT INTO users(username, password) VALUES ('{$username}', '{$passwordHashed}')");
        if ($save) {
          $_SESSION['flash'] = "Dodano admina!";
          header("Location: ../../views/admin.php");
          die();
        }
        $_SESSION['flash'] = "Wystąpił bład, spróbuj ponownie.";
      }
    }
  }
  header("Location: ../../views/addAdmin.php");
