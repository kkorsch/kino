<?php

use App\Models\Admin;

class Auth extends Controller
{
  public function index()
  {
    if (Admin::isLoggedIn()) {
      return header("Location: home");
    }
    return $this->view('auth/index');
  }

  public function login()
  {
    //validation
    if (!isset($_POST['username'], $_POST['password']) || empty($_POST['username']) || empty($_POST['password'])) {
      $_SESSION['loginError'] = 'Nieprawidłowa nazwa użytkownika lub hasło';
    } else {
      $db = new Database;
      $username =  $_POST['username'];
      $password = $_POST['password'];

      //check if such a user exists
      $query = $db->prepare("SELECT password FROM users WHERE username=:username");

      if (!$query->execute([':username' => $username])) {
        $_SESSION['loginError'] = 'Nieprawidłowa nazwa użytkownika lub hasło';
      } else {
        $user = $query->fetchObject();
        $passwordHashed = $user->password;

        //check if password matches
        if (password_verify($password, $passwordHashed)) {
          $_SESSION['loggedIn'] = true;
          $_SESSION['admin'] = $username;
          $_SESSION['flash'] = "Zalogowaleś się jako '{$username}'";
          return header("Location: ". constant("URL"));
        } else {
          $_SESSION['loginError'] = 'Nieprawidłowa nazwa użytkownika lub hasło';
        }
      }
    }
    return header("Location: ".constant('URL')."/Auth");
  }
}
