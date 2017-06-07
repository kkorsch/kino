<?php
require('db.php');

//validation
if (!isset($_POST['username'], $_POST['password']) || empty($_POST['username']) || empty($_POST['password'])) {
  $_SESSION['loginError'] = 'Nieprawidłowa nazwa użytkownika lub hasło';
  header("Location: ../views/login.php");
}
$username =  $db->real_escape_string($_POST['username']);
$password = $_POST['password'];

//check if such a user exists
$query = $db->query("SELECT password FROM users WHERE username='{$username}'");

if ($query->num_rows != 1) {
  $_SESSION['loginError'] = 'Nieprawidłowa nazwa użytkownika lub hasło';
}
$query = mysqli_fetch_assoc($query);
$passwordHashed = $query['password'];

//check if password matches
if (password_verify($password, $passwordHashed)) {
  $_SESSION['loggedIn'] = true;
  $_SESSION['admin'] = $username;
  $_SESSION['flash'] = "Zalogowaleś się jako '{$username}'";
  header("Location: ../views/admin.php");
  die();
}else {
  $_SESSION['loginError'] = 'Nieprawidłowa nazwa użytkownika lub hasło';
}
header("Location: ../views/login.php");
